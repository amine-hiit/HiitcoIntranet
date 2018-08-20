<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Notification;
use Assetic\Exception\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * EmployeeNotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EmployeeNotificationRepository extends \Doctrine\ORM\EntityRepository
{
    public function countUnseenByEmployee(Employee $employee){
        try
        {
            return $this->createQueryBuilder('e')
                ->select('COUNT(e)')
                ->where('e.employee = :employee')
                ->andWhere('e.seen = false')
                ->andWhere('e.archived = false')
                ->setParameter('employee', $employee)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function findOneEmployeeNotification(Notification $notification, Employee $employee)
    {
        try
        {
            return $this->createQueryBuilder('e')
                ->where('e.employee = :employee')
                ->andWhere('e.notification = :notification')
                ->setParameter('notification', $notification)
                ->setParameter('employee', $employee)
                ->setMaxResults('1')
                ->getQuery()
                ->getSingleResult();
        }catch(NoResultException $nre){
        }catch (NonUniqueResultException $nure){
        }
    }

    public function findAllByEmployeeQuery(
        $orderBy = null,
        $direction = 'desc',
        Employee $employee = null,
        $filters = []
    ){
        $qb = $this->createQueryBuilder('en')
            ->innerJoin('en.notification', 'n')
            ->where('en.employee = :employee')
            ->setParameter('employee', $employee);
        if (is_array($filters) && $this->count($filters) > 0) {

            foreach ($filters as $filter => $value) {
                $qb->andWhere('n.'.$filter.'=.value')
                    ->setParameter('value', $value);
            }
        }
        return $qb->getQuery()->getResult();
    }

    public function findLastTeenByEmployee(Employee $employee)
    {
        $qb = $this->createQueryBuilder('en')
            ->innerJoin('en.notification', 'n')
            ->andWhere('en.employee = :employee')
            ->andWhere('en.archived = false')

            ->setParameter('employee', $employee)
            ->orderBy('n.createdAt', 'DESC')
            ->setMaxResults(10)
        ;
        return $qb->getQuery()
            ->getResult();
    }

    public function findLastByEmployeeWithOffset(Employee $employee, $offset)
    {
        $qb = $this->createQueryBuilder('en')
            ->select('en')
            ->addSelect('n')
            ->innerJoin('en.notification', 'n')
            ->andWhere('en.employee = :employee')
            ->andWhere('en.archived = false')

            ->setParameter('employee', $employee)
            ->orderBy('n.createdAt', 'DESC')
            ->setMaxResults(10)
            ->setFirstResult( $offset )
        ;
        return  $qb->getQuery()->getResult();

    }

}
