<?php

namespace AppBundle\Repository;


use AppBundle\Entity\Employee;

class ProjectRepository extends \Doctrine\ORM\EntityRepository
{
    public function findEmployeeAllProjects(Employee $employee)
    {
        $qb = $this->createQueryBuilder('e');
        $qb
            ->where('e.employee = :employeeId')
            ->setParameter('employeeId',  $employee->getId())
            ->orderBy('e.date','DESC')

        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
}


