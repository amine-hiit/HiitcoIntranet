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

    public function findAllQuery(
        $orderBy = null,
        $direction = 'desc'
    )
    {
        $qb = $this->createQueryBuilder('p');

        if ($orderBy)
            $qb->orderBy('p.'.$orderBy, $direction);

        return $qb->getQuery();
    }


    public function findAllByEmployeeQuery(
        $orderBy = null,
        $direction = 'desc',
        Employee $employee = null
    )
    {


        $qb = $this->createQueryBuilder('e');

        if ($orderBy)
            $qb->orderBy('e.'.$orderBy, $direction);
        if (null !== $employee)
            $qb->where('e.employee = :employee')
                ->setParameter('employee', $employee);

        return $qb->getQuery();
    }

}


