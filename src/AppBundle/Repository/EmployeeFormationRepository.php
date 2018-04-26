<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Employee;
/**
 * EmployeeFormationRepository
 *
 */
class EmployeeFormationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findEmployeeAllFormations(Employee $employee)
    {
        $qb = $this->createQueryBuilder('e');
        $qb
            ->where('e.employee = :employeeId')
            ->setParameter('employeeId',  $employee->getId())
        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
    public function findEmployeeLastFormation(Employee $employee)
    {
        $qb =$this->createQueryBuilder('e');

        $qb
            ->where('e.employee = :employeeId')
            ->orderBy('e.startDate','DESC')
            ->setMaxResults(1)
            ->setParameter('employeeId', $employee->getId());

        ;

        return $qb
            ->getQuery()
            ->getOneOrNullResult();
        ;
    }

}
