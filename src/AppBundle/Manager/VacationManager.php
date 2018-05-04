<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Vacation;
use AppBundle\Entity\Employee;



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
    public function __construct(EntityManagerInterface $em )
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


    public function findByStatusByUser($status, Employee $employee)
    {
        return $this->em->getRepository('AppBundle:Vacation')->findByStatusByUser($status, $employee);

    }

    public function findAll()
    {
        return $this->em->getRepository('AppBundle:Vacation')->findAll();

    }

    public function validation(&$vacation, $isValid, $refuseReason)
    {
        if ('accepter'===$isValid){
            $vacation->setValidationStatus('1');
            $vacation->setRefuseReason('');
        }
        elseif ('refuser'===$isValid) {
            $vacation->setValidationStatus('-1');
            $vacation->setRefuseReason($refuseReason);
        }
    }

    public function isDemandeValid($vacation)
    {
        $errors=array();
        if ($vacation->getType()== Vacation::VACATION)
        {
            $vacationDaysLeft = $vacation->getvacationDaysLeft();
            $now = time();
            $startTime = strtotime($vacation->getStartDate()->format('d-m-Y'));

            $endTime = strtotime($vacation->getEndDate()->format('d-m-Y'));
            $untilStartDay = ($startTime - $now)/(60 * 60 * 24);
            $vacationDuration = ($endTime - $startTime)/(60 * 60 * 24);

            if (( 10 < $vacationDuration ))
            {
                $errors = ["endDate" => "vous n'avez pas assez de jour dans votre solde"];
            }

            if ( $untilStartDay - 30 < 0)
            {
                $errors += ["startDate" => "Le congé doit être demandé 30 jour à l'avance"];
            }
        }
        return $errors;
    }

    //calculation of the vacation days number
    public function calculateDuration($vacation)
    {
        $startTime = strtotime($vacation->getStartDate()->format('d-m-Y'));
        $endTime = strtotime($vacation->getEndDate()->format('d-m-Y'));
        return ($endTime-$startTime)/(60 * 60 * 24);
    }


    public function calculateLeftDay($vacation)
    {
        $startTime = strtotime($vacation->getStartDate()->format('d-m-Y'));
        $endTime = strtotime($vacation->getEndDate()->format('d-m-Y'));
        return ($endTime-$startTime)/(60 * 60 * 24);
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



    function calculateSold(Employee $employee, $afterRequestAprovement)
    {
        $startDate = $employee->getStartDate();
        $approvedVacations = $this->findByStatusByUser(1 ,$employee);
        $untreatedVacations = $this->findByStatusByUser(0 ,$employee);

        $sold = 0;
        $now = new \DateTime();

        $currentMonth = date('m',strtotime($now->format('Y-m-d')));
        $currentYear = date('Y',strtotime($now->format('Y-m-d')));
        $currentDay = date('Y',strtotime($now->format('Y-m-d')));

        $startMonth = date('m',strtotime($startDate->format('Y-m-d')));
        $startYear = date('Y',strtotime($startDate->format('Y-m-d')));
        $startDay = date('d',strtotime($startDate->format('Y-m-d')));


        if ($currentMonth == $startMonth && $currentYear == $startYear)
        {
            return $sold;
        }
        else
        {

            if ($startDay > 0 && $startDay <= 10)
                $sold += 1.5;

            elseif ($startDay > 10 && $startDay <= 20)
                $sold += 1;

            else
                $sold += 0.5;

            if ($afterRequestAprovement)
            {
                foreach ($approvedVacations as $approvedVacation)
                //return $approvedVacation->getDuration();
                    $sold -= $approvedVacation->getDuration();
            }
            else
            {
                foreach ($approvedVacations as $approvedVacation)
                    $sold -= $approvedVacation->getDuration();
                foreach ($untreatedVacations as $untreatedVacation)
                    $sold -= $approvedVacation->getDuration();
            }

            if ($currentMonth == $startMonth+1 && $currentYear == $startYear)
            {
                return $sold;
            }
            else
            {
                if ($currentMonth >= $startMonth)
                {	$sold +=  (($currentYear - $startYear)*12 + $currentMonth - 1 - $startMonth) *1.5 ;

                }else
                    $sold +=  (($currentYear - $startYear)*12 + 12 - ($currentMonth - 1 - $startMonth)) * 1.5 ;

                return $sold;
            }
        }
    }
}

