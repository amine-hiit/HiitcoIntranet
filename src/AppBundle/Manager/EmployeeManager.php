<?php


namespace AppBundle\Manager;


use AppBundle\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeManager
{

    /**
     * @var EntityManagerInterface
     */
    private $em;


    /**
     * EmployeeManager constructor.
     * @param EntityManagerInterface $em
     */

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     *
     */
    public function completeEmployeeForm(Employee $user)
    {
        $user->getAvatar()->upload();
        $user->getAvatar()->setAlt($user->getUserName().'_Avatar');

        $user->setValid(true);
        $this->em->persist($user);
        $this->em->flush();
    }

}