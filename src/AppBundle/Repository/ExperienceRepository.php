<?php

namespace AppBundle\Repository;


use AppBundle\Entity\Employee;

class ExperienceRepository extends \Doctrine\ORM\EntityRepository
{
    public function findEmployeeAllExperiences(Employee $employee)
    {
        $qb = $this->createQueryBuilder('e');
        $qb
            ->where('e.employee = :employeeId')
            ->setParameter('employeeId',  $employee->getId())
            ->orderBy('e.endDate','DESC')

        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
}


