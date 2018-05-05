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


    public function findOverlappingWithRange(Vacation $vacation)
    {
        return $this->em
            ->getRepository('AppBundle:Vacation')
            ->findOverlappingWithRange($vacation->getStartDate(), $vacation->getEndDate());
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

    public function validation(&$vacation, $isValid, $refuseReason)
    {
        if ('accepter' === $isValid) {
            $vacation->setValidationStatus('1');
            $vacation->setRefuseReason('');
        } elseif ('refuser' === $isValid) {
            $vacation->setValidationStatus('-1');
            $vacation->setRefuseReason($refuseReason);
        }
    }

    public function calculateWeekEndDaysNumberIncluded($vacation)
    {
        $WeekEndDaysNumber = 0;
        $startDate = $vacation->getStartDate();
        $endDate = $vacation->getEndDate();

        //because the end date is not included
        $endDate->modify('+1 day');
        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate);

        foreach ($period as $day) {
            if ($day->format('D') == 'Sat' || $day->format('D') == 'Sun') {
                $WeekEndDaysNumber++;
            }
        }

        return $WeekEndDaysNumber;
    }


    //calculation of the vacation days number
    public function calculateDuration($vacation, $weekEndIncluded = true)
    {
        $startTime = strtotime($vacation->getStartDate()->format('d-m-Y'));
        $endTime = strtotime($vacation->getEndDate()->format('d-m-Y'));

        $duration = $weekEndIncluded ? ($endTime - $startTime) / (60 * 60 * 24)
            - $this->calculateWeekEndDaysNumberIncluded($vacation)
            : ($endTime - $startTime) / (60 * 60 * 24);

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
        $approvedVacations = $this->findByStatusAndUser(1, $employee);
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
                foreach ($approvedVacations as $approvedVacation) {
                    $vacationBalance -= $approvedVacation->getDuration();
                }
            } else {
                foreach ($approvedVacations as $approvedVacation) {
                    $vacationBalance -= $approvedVacation->getDuration();
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
        $vacation->setDuration($this->calculateDuration($vacation));
        $this->em->persist($vacation);
    }

    public function flush()
    {
        $this->em->flush();
    }
}









/*



    public function isDemandeValid($vacation)
    {
        $errors = array();
        if ($vacation->getType() == Vacation::VACATION) {
            $vacationDaysLeft = $vacation->getvacationDaysLeft();
            $now = time();
            $startTime = strtotime($vacation->getStartDate()->format('d-m-Y'));

            $endTime = strtotime($vacation->getEndDate()->format('d-m-Y'));
            $untilStartDay = ($startTime - $now) / (60 * 60 * 24);
            $vacationDuration = ($endTime - $startTime) / (60 * 60 * 24);

            if ((10 < $vacationDuration)) {
                $errors = ["endDate" => "vous n'avez pas assez de jour dans votre vacationBalancee"];
            }

            if ($untilStartDay - 30 < 0) {
                $errors += ["startDate" => "Le congé doit être demandé 30 jour à l'avance"];
            }
        }
        return $errors;
    }


    public function calculateLeftDay($vacation)
    {
        $startTime = strtotime($vacation->getStartDate()->format('d-m-Y'));
        $endTime = strtotime($vacation->getEndDate()->format('d-m-Y'));
        return ($endTime - $startTime) / (60 * 60 * 24);
    }

*/




