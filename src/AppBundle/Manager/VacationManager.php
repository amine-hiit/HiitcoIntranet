<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Vacation;



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


    public function findOneById($vacationId)
    {
        return $this->em->getRepository('AppBundle:Vacation')->findOneById($vacationId);

    }

    public function findAll()
    {
        return $this->em->getRepository('AppBundle:Vacation')->findAll();

    }

    public function validation(&$vacation, $isValid, $refuseReason)
    {
        if ('accepter'===$isValid){
            $vacation->setValidationStatus('y');
            $vacation->setRefuseReason('');
        }
        elseif ('refuser'===$isValid) {
            $vacation->setValidationStatus('n');
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


    public function persist($task)
    {
        $this->em->persist($task);
    }

    public function flush()
    {
        $this->em->flush();
    }
}