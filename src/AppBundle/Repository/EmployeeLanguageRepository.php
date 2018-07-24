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

}
