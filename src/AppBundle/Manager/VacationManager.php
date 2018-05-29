<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Vacation;
use AppBundle\Entity\Employee;
use PhpOffice\PhpSpreadsheet\Calculation\DateTime;


class VacationManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * VacationManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findAllByUserId($userId)
    {
        return $this->em->getRepository('AppBundle:Vacation')->findAllByUserId($userId);
    }

    public function findAllByUser(Employee $employee)
    {
        return $this->em->getRepository('AppBundle:Vacation')->findAllByUser($employee);
    }


    public function findOverlappingWithRange(Vacation $vacation ,Employee $employee)
    {

        return $this->em
            ->getRepository('AppBundle:Vacation')
            ->findOverlappingWithRange($vacation->getStartDate(), $vacation->getEndDate(),$employee);
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

    public function adminValidation(&$vacation, $isValid, $refuseReason)
    {
        if ('accepter' === $isValid) {
            $vacation->setValidationStatus('2');
            $vacation->setRefuseReason('');
        }

        elseif ('refuser' === $isValid) {
            $vacation->setValidationStatus('-1');
            $vacation->setRefuseReason($refuseReason);
        }
    }

    public function hrmValidation(&$vacation, $isValid, $refuseReason)
    {
        if ('accepter' === $isValid) {
            $vacation->setValidationStatus('1');
            $vacation->setRefuseReason('');
        }

        elseif ('refuser' === $isValid) {
            $vacation->setValidationStatus('-1');
            $vacation->setRefuseReason($refuseReason);
        }
    }


    public function calculateWeekEndDaysNumberIncluded($vacation)
    {
        $WeekEndDaysNumber = 0;
        $startDate = $vacation->getStartDate();
        $endDate = $vacation->getEndDate();
        $endDate->modify('+1 day');
        //because the end date is not included

        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate);

        foreach ($period as $day) {

            if ($day->format('D') == 'Sat' || $day->format('D') == 'Sun') {
                $WeekEndDaysNumber++;
            }
        }
        $endDate->modify('-1 day');
        return $WeekEndDaysNumber;
    }


    //calculation of the vacation days number
    public function calculateDuration($vacation, $weekEndIncluded = true)
    {
        if ($vacation->getDayPeriod() !== 'allDay')
            return $weekEndIncluded && boolval($this->calculateWeekEndDaysNumberIncluded($vacation)) ? 0: 0.5;

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

    function calculatevacationBalance(Employee $employee, $afterRequestAprovement = false)
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

            if ($afterRequestAprovement) {
                foreach ($adminApprovedVacations as $adminApprovedVacation) {
                    $vacationBalance -= $adminApprovedVacation->getDuration();
                }
                foreach ($hrmApprovedVacations as $hrmApprovedVacation) {
                    $vacationBalance -= $hrmApprovedVacation->getDuration();
                }
            } else {

                foreach ($adminApprovedVacations as $adminApprovedVacation) {
                    $vacationBalance -= $adminApprovedVacation->getDuration();
                }
                foreach ($hrmApprovedVacations as $hrmApprovedVacation) {
                    $vacationBalance -= $hrmApprovedVacation->getDuration();
                }
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

    public function persist($vacation)
    {
        //set duration attribute before persisting
        $vacation->setDuration($this->calculateDuration($vacation,true));

        $this->em->persist($vacation);
    }

    public function flush()
    {
        $this->em->flush();
    }
}






