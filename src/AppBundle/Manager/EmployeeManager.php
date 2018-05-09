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

    public function findByRole($role)
    {
        return $this->em->getRepository(Employee::class)->findByRole($role);
    }

    public function findEmployeeLastFormation(Employee $employee)
    {
        return $this->em->getRepository(EmployeeFormation::class)->findEmployeeLastFormation($employee);
    }

    public function findEmployeeLastExperience(Employee $employee)
    {
        return $this->em->getRepository(Experience::class)->findEmployeeLastFormation($employee);
    }

    public function findAll()
    {
        return $this->em->getRepository(Employee::class)->findAll();
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

    public function setUserToAtribut($atribut,Employee $user)
    {
        $atribut->setEmployee($user);
    }

    public function persistAtribut($atribut)
    {
        $this->em->persist($atribut);
    }

    public function flush()
    {
        $this->em->flush();
    }

    public function completeEmployeeForm(Employee $user)
    {
        $user->getAvatar()->upload();
        $user->getAvatar()->setAlt($user->getUserName().'_Avatar');

        $user->setValid(true);
        $this->em->persist($user);
        $this->flush();
    }


}