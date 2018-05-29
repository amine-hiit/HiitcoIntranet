<?php
// src/AppBundle/Repository/VacationRepository.php
namespace AppBundle\Repository;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Vacation;
use Doctrine\ORM\EntityRepository;


class VacationRepository extends EntityRepository
{

    public function findOverlappingWithRange(\DateTime $startDate, \DateTime $endDate, Employee $employee)
    {
        $qb = $this->createQueryBuilder('v');

        return $qb->andWhere('v.startDate <= :endDate AND v.endDate >= :startDate')
            ->andWhere('v.employee = :employeeId')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('employeeId', $employee->getId())
            ->getQuery()
            ->execute()
            ;
    }

	public function findAllByUserId($userId)
	{
	  $qb = $this->createQueryBuilder('v');

	  $qb
	    ->where('v.employee = :employeeId')
	    ->setParameter('employeeId', $userId)
	  ;
	  return $qb
	    ->getQuery()
	    ->getResult()
	  ;
	}

    public function findByStatusAndUser($status ,Employee $employee)
    {
        $qb = $this->createQueryBuilder('v');

        $qb
            ->where('v.employee = :employeeId')
            ->andWhere('v.validationStatus =  :status')
            ->andWhere('v.type =  :type')
            ->setParameter('employeeId', $employee->getId())
            ->setParameter('status', $status)
            ->setParameter('type', Vacation::VACATION)
        ;
        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

	public function findAllByUser(Employee $employee)
	{
	  $qb = $this->createQueryBuilder('v');

	  $qb
	    ->where('v.employee = :employeeId')
	    ->setParameter('employeeId', $employee->getId())
	  ;
	  return $qb
	    ->getQuery()
	    ->getResult()
	  ;
	}
}