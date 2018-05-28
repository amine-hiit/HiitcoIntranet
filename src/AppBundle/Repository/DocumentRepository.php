<?php

namespace AppBundle\Repository;

use FOS\UserBundle\Model\User;

/**
 * DocumentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DocumentRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllByUser(User $employee)
    {
        return  $this->createQueryBuilder('d')
            ->where('d.employee = :employee')
            ->setParameter('employee', $employee)
            ->getQuery()
            ->getResult();
    }
}