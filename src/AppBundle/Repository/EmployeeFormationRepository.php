<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Employee;
/**
 * EmployeeFormationRepository
 *
 */
class EmployeeFormationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllByEmployeeId($employeeId)
    {
        $qb = $this->createQueryBuilder('e');
        $qb
            ->where('e.employee = :employeeId')
            ->setParameter('employeeId', $employeeId)
        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
    public function findLastFormationByEmployeeId(Employee $employee)
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
