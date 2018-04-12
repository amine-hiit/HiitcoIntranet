<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Leave;



class LeaveManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * LeaveManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em )
    {
        $this->em = $em;
    }

    public function findAllByUserId($userId)
    {
        return $this->em->getRepository('AppBundle:Leave')->findAllByUserId($userId);
    }


    public function findOneById($leaveId)
    {
        return $this->em->getRepository('AppBundle:Leave')->findOneById($leaveId);

    }

    public function findAll()
    {
        return $this->em->getRepository('AppBundle:Leave')->findAll();

    }

    public function validation(&$leave, $isValid, $refuseReason)
    {
        if ('accepter'===$isValid){
            $leave->setValidationStatus('y');
            $leave->setRefuseReason(''); 
        }
        elseif ('refuser'===$isValid) {
            $leave->setValidationStatus('n');
            $leave->setRefuseReason($refuseReason); 
        }
    }

    public function isDemandeValid($leave)
    {

        $errors=array();
        if ($leave->getType()== Leave::LEAVE ){

            $leaveDaysLeft = $leave->getLeaveDaysLeft();
            $now = time();
            $startTime = strtotime($leave->getStartDate()->format('d-m-Y'));

            $endTime = strtotime($leave->getEndDate()->format('d-m-Y'));
            $untilStartDay = ($startTime - $now)/(60 * 60 * 24);
            $leaveDuration = ($endTime - $startTime)/(60 * 60 * 24);
            

            if (( 10 < $leaveDuration ))
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