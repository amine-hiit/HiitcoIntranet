<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Employee;

/**
 * NotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NotificationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllByEmployeeQuery(
        $orderBy = null,
        $direction = 'desc',
        Employee $employee = null,
        $filters = []
    )
    {

        $qb = $this->createQueryBuilder('n');

        if ($orderBy)
            $qb->orderBy('n.'.$orderBy, $direction);
        if (null !== $employee)
            $qb->where('n.employee = :employee')
                ->setParameter('employee', $employee);
        if($this->count($filters) > 0) {
            foreach ($filters as $filter => $value)
                $qb->andWhere('v.'.$filter.' = \''.$value.'\'');

        }

        return $qb->getQuery();
    }
}
