<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Employee;
/**
 * EmployeeFormationRepository
 *
 */
class EmployeeLanguageRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllByUser(Employee $employee)
    {
        return $this->createQueryBuilder('el')
            ->where('el.employee = :employee')
            ->setParameter('employee', $employee)
            ->getQuery()->getResult();
    }

    public function findAllQuery(
        $orderBy = null,
        $direction = 'desc'
    )
    {
        $qb = $this->createQueryBuilder('el');

        if ($orderBy)
            $qb->orderBy('el.'.$orderBy, $direction);

        return $qb->getQuery();
    }

    public function findAllByEmployeeQuery(
        $orderBy = null,
        $direction = 'desc',
        Employee $employee = null
    )
    {


        $qb = $this->createQueryBuilder('el');

        if ($orderBy)
            $qb->orderBy('el.'.$orderBy, $direction);
        if (null !== $employee)
            $qb->where('el.employee = :employee')
                ->setParameter('employee', $employee);

        return $qb->getQuery();
    }
}
