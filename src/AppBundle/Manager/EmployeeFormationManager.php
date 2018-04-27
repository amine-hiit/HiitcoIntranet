<?php


namespace AppBundle\Manager;


use AppBundle\Entity\Employee;
use AppBundle\Entity\EmployeeFormation;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeFormationManager
{

    /**
     * @var EntityManagerInterface
     */
    private $em;


    /**
     * EmployeeFormationManager constructor.
     * @param EntityManagerInterface $em
     */

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findEmployeeLastFormation($employee)
    {
        return $this->em->getRepository(EmployeeFormation::class)->findEmployeeLastFormation($employee);
    }

    public function findEmployeeLastExperience($employee)
    {
        return $this->em->getRepository(EmployeeFormation::class)->findEmployeeLastFormation($employee);
    }

    public function findAllEmployeeExperiences($employee)
    {
        return $this->em->getRepository(EmployeeFormation::class)->findAllEmployeeExperiences($employee);
    }


    public function findEmployeeAllFormations($employee)
    {
        return $this->em->getRepository(EmployeeFormation::class)->findEmployeeAllFormations($employee);
    }

    public function create()
    {
        return new EmployeeFormation();
    }




}