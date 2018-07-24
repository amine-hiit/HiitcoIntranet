<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Vacation;
use AppBundle\Entity\Employee;
use PhpOffice\PhpSpreadsheet\Calculation\DateTime;
use Psr\Log\LoggerInterface;


class VacationManager
{
    const VACATION_REQUEST_NOTIF = 'vacation_request';

    const VACATION_ADMIN_ACCEPTATION_NOTIF = 'vacation_admin_acceptation';
    const VACATION_ADMIN_REFUSE_NOTIF = 'vacation_admin_refuse';

    const ADMIN_NOTIF_HRM_ACCEPTATION = 'admin_notification_vacation_hrm_acceptation';
    const EMPLOYEE_NOTIF_REFUSE = 'employee_notification_vacation_refuse';

    const ADMIN_NOTIF_REFUSE = 'admin_notification_vacation_refuse';

    const EMPLOYEE_NOTIF_HRM_ACCEPTATION = 'employee_notification_vacation_rhm_acceptation';
    const EMPLOYEE_NOTIF_ADMIN_ACCEPTATION = 'employee_notification_vacation_admin_acceptation';

    const VACATION_HRM_REFUSE_NOTIF = 'vacation_hrm_refuse';


    const HOLLIDAYS = [
        'NEW_YEAR_S_DAY' => ['d' => '01' , 'm' => '01'],
        'THE_INDEPENDENCE_MANIFESTO' => ['d' => '11' , 'm' => '01'],
        'LABOUR_DAY' => ['d' => '01' , 'm' => '05'],
        'FEAST_OF_THE_THRONE' => ['d' => '30' , 'm' => '07'],
        'THE_RECOVERY_OUED_ED_DAHAB' => ['d' => '14' , 'm' => '08'],
        'REVOLUTION_OF_THE_KING_AND_THE_PEOPLE' => ['d' => '20' , 'm' => '08'],
        'YOUTH_DAY' => ['d' => '21' , 'm' => '08'],
        'GREEN_MARCH' => ['d' => '06' , 'm' => '11'],
        'INDEPENDENCE_DAY' => ['d' => '18' , 'm' => '11'],
    ];

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var NotificationManager
     */
    private $nm;

    /**
     * @var EmailManager
     */
    private $emailManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * VacationManager constructor.
     * @param EntityManagerInterface $em
     * @param NotificationManager $nm
     * @param EmailManager $emailManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $em,
        NotificationManager $nm,
        EmailManager $emailManager,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->nm = $nm;
        $this->emailManager = $emailManager;
        $this->logger = $logger;}


    public function findAllByUserId($userId)
    {
        return $this->em->getRepository('AppBundle:Vacation')->findAllByUserId($userId);
    }

    public function findAllByUser(Employee $employee)
    {
        return $this->em->getRepository('AppBundle:Vacation')->findAllByUser($employee);
    }

    public function findOverlappingWithRange(Vacation $vacation, Employee $employee)
    {

        return $this->em
            ->getRepository('AppBundle:Vacation')
            ->findOverlappingWithRange($vacation->getStartDate(), $vacation->getEndDate(), $employee);
    }

    public function findOneById($vacationId)
    {
        return $this->em->getRepository('AppBundle:Vacation')->find($vacationId);

    }

    public function findByStatusAndUser($status, Employee $employee)
    {
        return $this->em->getRepository('AppBundle:Vacation')->findByStatusAndUser($status, $employee);

    }

    public function findByStatusAndUserAndDateInterval($status, Employee $employee, \DateTime $end)
    {
        return $this->em->getRepository('AppBundle:Vacation')
            ->findByStatusAndUserAndDateInterval($status, $employee,$end);

    }

    public function findAll()
    {
        return $this->em->getRepository('AppBundle:Vacation')->findAll();

    }

    public function adminValidation(Vacation &$vacation, $isValid, $refuseReason)
    {
        $admins = $this->em->getRepository('AppBundle:Employee')
            ->findByRole(Employee::ROLE_ADMIN);
        $employee = $vacation->getEmployee();

        if ('accepter' === $isValid) {

            $vacation->setValidationStatus('2');
            $vacation->setRefuseReason('');

            try {
                $this->generateNotification(
                    self::EMPLOYEE_NOTIF_ADMIN_ACCEPTATION,
                    [],
                    'url',
                    $employee
                );

                $this->generateNotification(
                    self::ADMIN_NOTIF_HRM_ACCEPTATION,
                    array('le service rh'),
                    'url',
                    $admins
                );
            }catch (\Exception $exception){
                $this->logger->error($exception->getMessage());
            }
        }
        elseif ('refuser' === $isValid) {
            $vacation->setValidationStatus('-1');
            $vacation->setRefuseReason($refuseReason);
            try {
                $this->generateNotification(
                    self::EMPLOYEE_NOTIF_REFUSE,
                    [],
                    'url',
                    $employee);
            }catch (\Exception $exception){
                $this->logger->error($exception->getMessage());
            }

        }

        $this->flush();
    }

    public function hrmValidation(Vacation &$vacation, $isValid, $refuseReason)
    {
        $admins = $this->em->getRepository('AppBundle:Employee')
            ->findByRole(Employee::ROLE_ADMIN);

        $employee = $vacation->getEmployee();

        if ('accepter' === $isValid) {
            $vacation->setValidationStatus('1');
            $vacation->setRefuseReason('');


            try {

                $this->generateNotification(
                    self::EMPLOYEE_NOTIF_HRM_ACCEPTATION,
                    ['Le responsable rh'],
                    'url',
                    $employee
                );
                $this->generateNotification(
                    self::ADMIN_NOTIF_HRM_ACCEPTATION,
                    ['Le responsable rh'],
                    'url',
                    $admins
                );
            }catch (\Exception $exception){
                $this->logger->error($exception->getMessage());
            }



        }

        elseif ('refuser' === $isValid) {
            $vacation->setValidationStatus('-1');
            $vacation->setRefuseReason($refuseReason);

            $this->generateNotification(self::ADMIN_NOTIF_REFUSE,[], 'url', $admins);

            try {

                $this->generateNotification(
                    self::EMPLOYEE_NOTIF_REFUSE,
                    [''],
                    'url',
                    $employee
                );
            }catch (\Exception $exception){
                $this->logger->error($exception->getMessage());
            }
        }
        $this->flush();
    }


    /**calculation of the vacation days number*/
    public function calculateDurationV2(
        \DateTime $start,
        \DateTime $end,
        $weekEndIncluded = true,
        $hollidayIncluded = true )
    {

        if($start == $end && $this->isWeekEnd($end))
            return 0;
        if($start == $end && $this->isHolliDay($end))
            return 0;

        $duration = $weekEndIncluded && $hollidayIncluded ? $end->diff($start)->days
            - $this->calculateWeekEndDaysNumberIncludedV2($start, $end)
            - $this->calculateHolliDaysNumberIncludedV2($start, $end)+1
            : $end->diff($start)->days+1;

        return $duration;
    }


    /**calculation of the vacation days number*/
    public function calculateDuration(
        Vacation $vacation,
        $weekEndIncluded = true,
        $hollidayIncluded = true )
    {
        if ($vacation->getDayPeriod() !== 'allDay') {
            return $weekEndIncluded && $hollidayIncluded
            && boolval($this->calculateWeekEndDaysNumberIncluded($vacation))
            && boolval($this->calculateHolliDaysNumberIncluded($vacation)) ?
                0 : 0.5;
        }

        $endDate = $vacation->getEndDate();
        $startDate = $vacation->getStartDate();

        if($startDate == $endDate && $this->isWeekEnd($startDate))
            return 0;

        if($startDate == $endDate && $this->isHolliDay($startDate))
            return 0;


        $duration = $weekEndIncluded && $hollidayIncluded ? $endDate->diff($startDate)->days
            - $this->calculateWeekEndDaysNumberIncluded($vacation)
            - $this->calculateHolliDaysNumberIncluded($vacation)+1
            : $endDate->diff($startDate)->days+1;


        return $duration;
    }

    /**calculation of the vacation days number before and/andNot within a specific*/
    public function calculateDurationBeforeMonth(
        Vacation $vacation,
        \DateTime $month,
        $weekEndIncluded = true,
        $hollidayIncluded = true,
        $monthIncluded = true
    )
    {
        if($vacation->getStartDate() == $vacation->getEndDate() && $this->isWeekEnd($vacation->getStartDate()))
            return 0;

        $monthpp = clone $month;
        $monthpp->modify("+1 month");

        if (!$monthIncluded){
            if (strtotime($vacation->getStartDate()->format("Y-m-d"))
                > strtotime($month->format("Y-m-d")))
                return 0;
            else {
                if ($vacation->getDayPeriod() !== 'allDay') {
                    if ($monthIncluded) {
                        return $weekEndIncluded && $hollidayIncluded
                        && boolval($this->calculateWeekEndDaysNumberIncluded($vacation))
                        && boolval($this->calculateHolliDaysNumberIncluded($vacation))
                        && !($month->format("m") >= $vacation->getStartDate()->format("m")
                            && $month->format("Y") >= $vacation->getStartDate()->format("m"))
                            ? 0 : 0.5;
                    } else {
                        return $weekEndIncluded && $hollidayIncluded
                        && boolval($this->calculateWeekEndDaysNumberIncluded($vacation))
                        && boolval($this->calculateHolliDaysNumberIncluded($vacation))
                        && !($month->format("m") > $vacation->getStartDate()->format("m")
                            && $month->format("Y") >= $vacation->getStartDate()->format("m"))
                            ? 0 : 0.5;
                    }
                }
                return $this->calculateDuration($vacation);
            }
        }
        else {
            if (strtotime($vacation->getStartDate()->format("Y-m-d"))
                > strtotime($monthpp->format("Y-m-d")))
                return 0;
            else {
                if ($vacation->getDayPeriod() !== 'allDay') {
                    if ($monthIncluded) {
                        return $weekEndIncluded && $hollidayIncluded
                        && boolval($this->calculateWeekEndDaysNumberIncluded($vacation))
                        && boolval($this->calculateHolliDaysNumberIncluded($vacation))
                        && !($month->format("m") >= $vacation->getStartDate()->format("m")
                            && $month->format("Y") >= $vacation->getStartDate()->format("m"))
                            ? 0 : 0.5;
                    } else {
                        return $weekEndIncluded && $hollidayIncluded
                        && boolval($this->calculateWeekEndDaysNumberIncluded($vacation))
                        && boolval($this->calculateHolliDaysNumberIncluded($vacation))
                        && !($month->format("m") > $vacation->getStartDate()->format("m")
                            && $month->format("Y") >= $vacation->getStartDate()->format("m"))
                            ? 0 : 0.5;
                    }
                }
                return $this->calculateDuration($vacation);
            }
        }
    }

    public function calculateDaysUntilStartDate($vacation)
    {
        $now = new \DateTime();
        $startTime = strtotime($vacation->getStartDate()->format('d-m-Y'));
        $nowTime = strtotime($now->format('d-m-Y'));

        return ($startTime - $nowTime) / (60 * 60 * 24);
    }

    private function isWeekEnd(\DateTime $day){
       return ($day->format('D') == 'Sat' || $day->format('D') == 'Sun');
    }

    private function isHolliDay(\DateTime $day){

        foreach (self::HOLLIDAYS as $key => $value)
        {
            if ($day->format('d') == $value['d'] &&  $day->format('m') == $value['m'])
                return true;
        }
        return false;
    }

    public function calculateWeekEndDaysNumberIncluded(Vacation $vacation)
    {
        $WeekEndDaysNumber = 0;
        $startDate = $vacation->getStartDate();
        $endDate = $vacation->getEndDate();
        $endDate->modify('+1 day');
        //because the end date is not included

        $period = new \DatePeriod($startDate,
            new \DateInterval('P1D'),
            $endDate);

        foreach ($period as $day) {

            if($this->isWeekEnd($day)) {
                $WeekEndDaysNumber++;
            }
        }
        $endDate->modify('-1 day');

        return $WeekEndDaysNumber;
    }

    public function calculateWeekEndDaysNumberIncludedV2(\DateTime $start, \DateTime $end)
    {
        $WeekEndDaysNumber = 0;
        $end->modify('+1 day');
        //because the end date is not included

        $period = new \DatePeriod($start,
            new \DateInterval('P1D'),
            $end);

        foreach ($period as $day) {

            if($this->isWeekEnd($day)) {
                $WeekEndDaysNumber++;
            }
        }
        $end->modify('-1 day');

        return $WeekEndDaysNumber;
    }

    public function calculateHolliDaysNumberIncluded(Vacation $vacation)
    {
        $holliDaysNumber = 0;
        $startDate = $vacation->getStartDate();
        $endDate = $vacation->getEndDate();
        //because the end date is not included

        $period = new \DatePeriod($startDate,
            new \DateInterval('P1D'),
            $endDate);

        foreach ($period as $day) {
            if($this->isHolliDay($day)) {
                $holliDaysNumber++;
            }
        }

        return $holliDaysNumber;
    }

    public function calculateHolliDaysNumberIncludedV2(\DateTime $start, \DateTime $end)
    {
        $holliDaysNumber = 0;
        $end->modify('+1 day');
        //because the end date is not included

        $period = new \DatePeriod($start,
            new \DateInterval('P1D'),
            $end);

        foreach ($period as $day) {

            if($this->isHolliDay($day)) {
                $holliDaysNumber++;
            }
        }
        $end->modify('-1 day');

        return $holliDaysNumber;
    }

    function calculateVacationBalance(Employee $employee, $afterRequestApprove = false)
    {

        $startDate = $employee->getStartDate();
        $hrmApprovedVacations = $this->findByStatusAndUser(1, $employee);
        $adminApprovedVacations = $this->findByStatusAndUser(2, $employee);
        $untreatedVacations = $this->findByStatusAndUser(0, $employee);
        $vacationBalance = 0;
        $now = new \DateTime();

        $currentMonth = date('m', strtotime($now->format('Y-m-d')));
        $currentYear = date('Y', strtotime($now->format('Y-m-d')));

        $startMonth = date('m', strtotime($startDate->format('Y-m-d')));
        $startYear = date('Y', strtotime($startDate->format('Y-m-d')));
        $startDay = date('d', strtotime($startDate->format('Y-m-d')));


        if ($currentMonth == $startMonth && $currentYear == $startYear) {
            return $vacationBalance;
        }
        else {
            if(date_diff( new \DateTime("now"), $startDate)->days  >= 365*2)
            {
                $vacationBalance += (int)((date_diff( new \DateTime("now"),$startDate )
                            ->days- 365*2 ) / 183);
            }

            if ($startDay > 0 && $startDay <= 10) {
                $vacationBalance += 1.5;
            } elseif ($startDay > 10 && $startDay <= 20) {
                $vacationBalance += 1;
            } else {
                $vacationBalance += 0.5;
            }

                foreach ($adminApprovedVacations as $adminApprovedVacation) {
                    $vacationBalance -= $adminApprovedVacation->getDuration();
                }
                foreach ($hrmApprovedVacations as $hrmApprovedVacation) {
                    $vacationBalance -= $hrmApprovedVacation->getDuration();
                }

            if ($afterRequestApprove) {
                    foreach ($untreatedVacations as $untreatedVacation) {
                        $vacationBalance -= $untreatedVacation->getDuration();
                    }
                }


            if ($currentMonth == $startMonth + 1 && $currentYear == $startYear) {
                return $vacationBalance;
            }
            else {

                if ($currentMonth >= $startMonth) {
                    $vacationBalance += (($currentYear - $startYear) * 12
                            + $currentMonth - 1 - $startMonth)
                        * Vacation::VACATION_DAYS_PER_MONTH;

                }
                else {
                    $vacationBalance += (($currentYear - $startYear - 1) * 12 + (12 - $startMonth + $currentMonth -1 ))
                        * Vacation::VACATION_DAYS_PER_MONTH;

                }
                return $vacationBalance;
            }
        }
    }

    function calculateMonthlyVacationBalance(Employee $employee)
    {

        $now = new \DateTime();
        $startDate = $employee->getStartDate();
        $currentYear = date('Y', strtotime($now->format('Y-m-d')));
        $startYear = date('Y', strtotime($startDate->format('Y-m-d')));

        $years = [];

        for($i = $startYear; $i <= $currentYear; $i++){
            $months = $this->calculateYearBalances($i, $employee);
            $years[$i] = $months;
        }

        return $years;

    }

    public function calculateYearBalances($i, Employee $employee){

        $months = [];
        for ($j = 01; $j < 13 ; $j++){
            $month = $this->calculateMonthBalance($i, $j,$employee);
            $months[$j] =  $month;
        }
        return $months;
    }

    public function calculateMonthBalance($year, $month, Employee $employee){

        $monthDate = new \DateTime($year."-".$month."-1");
        $monthTime = strtotime($monthDate->format("Y-m-d"));
        $now = new \DateTime();
        $startDate = $employee->getStartDate();
        $startTime = strtotime($startDate->format("Y-m-d"));

        $monthYear = $monthDate->format('Y');
        $startYear =$startDate->format('Y');
        $currentMonth = $now->format('m');
        $currentYear = $now->format('Y');
        $startMonth = $startDate->format('m');

        $monthIniBalance = 0;
        $monthBalance = 0;

        $monthpp = clone $monthDate;
        $monthpp->modify("+1 month");


        if ($year > $now->format("Y") || $year == $now->format("Y") && $month > $now->format("m"))
            return ["X", "X"];

        if ($monthTime <= $startTime )
            return [$monthBalance, $monthIniBalance];

        $vacations = $this->findByStatusAndUser(2,$employee);
        foreach ($vacations as $vacation){

            $monthIniBalance -= $this->calculateDurationBeforeMonth(    $vacation,
                $monthDate,
                true,
                true,
                false
            );
            $monthBalance -= $this->calculateDurationBeforeMonth($vacation, $monthDate);
        }
        if(date_diff( $monthDate, $startDate)->days  >= 365*2)
        {
            $monthIniBalance += (int)((date_diff( $monthDate,$startDate )
                        ->days- 365*2 ) / 183)+1;

            $monthBalance += (int)((date_diff( $monthDate,$startDate )
                        ->days- 365*2 ) / 183)+1;
        }

        $startDay = $startDate->format('d');

        if ($startDay > 0 && $startDay <= 10) {
            $monthIniBalance += 1.5;
            $monthBalance += 1.5;

        } elseif ($startDay > 10 && $startDay <= 20) {
            $monthIniBalance += 1;
            $monthBalance += 1;
        } else {
            $monthIniBalance += 0.5;
            $monthBalance += 0.5;
        }

        if ($currentMonth == (int)$startMonth + 1 && $currentYear == $startYear) {
            return [$monthIniBalance ,$monthBalance ];
        }
        else {

            if ($month >= $startMonth) {
                $monthIniBalance += (($monthYear - $startYear) * 12
                        + $month - 1 - $startMonth)
                    * Vacation::VACATION_DAYS_PER_MONTH;
                $monthBalance += (($monthYear - $startYear) * 12
                        + $month - 1 - $startMonth)
                    * Vacation::VACATION_DAYS_PER_MONTH;
            }
            else {
                $monthIniBalance += (($monthYear - $startYear - 1) * 12 + (12 - $startMonth + $month -1 ))
                    * Vacation::VACATION_DAYS_PER_MONTH;
                $monthBalance += (($monthYear - $startYear - 1) * 12 + (12 - $startMonth + $month -1 ))
                    * Vacation::VACATION_DAYS_PER_MONTH;
            }
            return [$monthIniBalance,$monthBalance ];
        }
    }

    public function generateNotification($notifType,array $args = null, $url, $employees)
    {
        $this->nm->generateNotification($notifType, $args, '/intranet/my-docs', $employees);
    }

    public function request(Vacation &$vacation)
    {
        $startDate = $vacation->getStartDate();
        $dayPeriod = $vacation->getDayPeriod();

        if ( Vacation::ALL_DAY !== $dayPeriod)
            $vacation->setEndDate($startDate);

        $notifConcerned = [];
        $notifConcerned = array_merge($notifConcerned,$this->em->getRepository('AppBundle:Employee')
            ->findByRole(Employee::ROLE_HR));
        $notifConcerned = array_merge($notifConcerned,$this->em->getRepository('AppBundle:Employee')
            ->findByRole(Employee::ROLE_ADMIN));



        try {

            $this->generateNotification(
                self::VACATION_REQUEST_NOTIF,
                array($vacation->getEmployee()->getUsername()),
                'url',
                $notifConcerned
            );
        }catch (\Exception $exception){
            $this->logger->error($exception->getMessage());
        }


        $this->persist($vacation);
        $this->flush();
    }

    public function persist(Vacation $vacation)
    {
        //set duration attribute before persisting
        $vacation->setDuration($this->calculateDuration($vacation, true));
        $this->em->persist($vacation);
    }

    public function flush()
    {
        $this->em->flush();
    }
}






