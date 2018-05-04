<?php
// src/AppBundle/Repository/VacationRepository.php
namespace AppBundle\Repository;

use AppBundle\Entity\Employee;
use Doctrine\ORM\EntityRepository;


class VacationRepository extends EntityRepository
{

    public function findOverlappingWithRange(\DateTime $startDate, \DateTime $endDate)
    {
        $qb = $this->createQueryBuilder('v');

        return $qb->andWhere('v.startDate < :endDate AND v.endDate > :startDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->execute()
            ;
    }

	public function findAllByUserId($userId)
	{
	  $qb = $this->createQueryBuilder('l');

	  $qb
	    ->where('l.employee = :employeeId')
	    ->setParameter('employeeId', $userId)
	  ;
	  return $qb
	    ->getQuery()
	    ->getResult()
	  ;
	}

    public function findByStatusByUser($status ,Employee $employee)
    {
        $qb = $this->createQueryBuilder('v');

        $qb
            ->where('v.employee = :employeeId')
            ->andWhere('v.validationStatus =  :status')
            ->setParameter('employeeId', $employee->getId())
            ->setParameter('status', $status)
        ;
        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

	public function findAllByUser(Employee $employee)
	{
	  $qb = $this->createQueryBuilder('l');

	  $qb
	    ->where('l.employee = :employeeId')
	    ->setParameter('employeeId', $employee->getId())
	  ;
	  return $qb
	    ->getQuery()
	    ->getResult()
	  ;
	}
}