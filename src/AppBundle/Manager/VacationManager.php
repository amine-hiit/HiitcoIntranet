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
        $this->logger = $logger;
    }


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
        return $this->em->getRepository('AppBundle:Vacation')->findOneById($vacationId);

    }


    public function findByStatusAndUser($status, Employee $employee)
    {
        return $this->em->getRepository('AppBundle:Vacation')->findByStatusAndUser($status, $employee);

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
    public function calculateDuration($vacation, $weekEndIncluded = true)
    {
        if ($vacation->getDayPeriod() !== 'allDay') {
            return $weekEndIncluded && boolval($this->calculateWeekEndDaysNumberIncluded($vacation)) ? 0 : 0.5;
        }

        $endDate = $vacation->getEndDate();
        $endDate->modify('+1 day');


        $startTime = strtotime($vacation->getStartDate()->format('d-m-Y'));
        $endTime = strtotime($vacation->getEndDate()->format('d-m-Y'));


        $duration = $weekEndIncluded ? ($endTime - $startTime) / (60 * 60 * 24)
            - $this->calculateWeekEndDaysNumberIncluded($vacation)
            : ($endTime - $startTime) / (60 * 60 * 24);

        $endDate->modify('-1 day');

        return $duration;
    }

    public function calculateDaysUntilStartDate($vacation)
    {
        $now = new \DateTime();
        $startTime = strtotime($vacation->getStartDate()->format('d-m-Y'));
        $nowTime = strtotime($now->format('d-m-Y'));

        return ($startTime - $nowTime) / (60 * 60 * 24);
    }






    public function calculateWeekEndDaysNumberIncluded($vacation)
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

            if ($day->format('D') == 'Sat' || $day->format('D') == 'Sun') {
                $WeekEndDaysNumber++;
            }
        }
        $endDate->modify('-1 day');

        return $WeekEndDaysNumber;
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
        } else {

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
            } else {
                if ($currentMonth >= $startMonth) {
                    $vacationBalance += (($currentYear - $startYear) * 12
                            + $currentMonth - 1 - $startMonth)
                        * Vacation::VACATION_DAYS_PER_MONTH;
                } else {
                    $vacationBalance += (($currentYear - $startYear) * 12 +
                            12 - ($currentMonth - 1 - $startMonth))
                        * Vacation::VACATION_DAYS_PER_MONTH;
                }

                return $vacationBalance;
            }
        }
    }

    public function generateNotification($notifType,array $args = null, $url, $employees)
    {
        $this->nm->generateNotification($notifType, $args, $url, $employees);
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

    public function persist($vacation)
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






