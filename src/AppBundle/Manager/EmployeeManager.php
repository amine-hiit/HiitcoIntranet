<?php


namespace AppBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\EmployeeFormation;
use AppBundle\Entity\Experience;
use AppBundle\Entity\Employee;

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


    public function findEmployeeLastFormation(Employee $employee)
    {
        return $this->em->getRepository(EmployeeFormation::class)->findEmployeeLastFormation($employee);
    }

    public function findEmployeeLastExperience(Employee $employee)
    {
        return $this->em->getRepository(Experience::class)->findEmployeeLastFormation($employee);
    }

    public function findEmployeeAllExperiences(Employee $employee)
    {
        return $this->em->getRepository(Experience::class)->findEmployeeAllExperiences($employee);
    }


    public function findEmployeeAllFormations(Employee $employee)
    {
        return $this->em->getRepository(EmployeeFormation::class)->findEmployeeAllFormations($employee);
    }

    public function create()
    {
        return new EmployeeFormation();
    }

    public function completeEmployeeForm(Employee $user)
    {
        $user->getAvatar()->upload();
        $user->getAvatar()->setAlt($user->getUserName().'_Avatar');

        $experiences = $user->getExperiences();
        foreach ($experiences as $experience )
        {
            $experience->setEmployee($user);
        }
        $user->setValid(true);
        $this->em->persist($user);
        $this->em->flush();
    }

}