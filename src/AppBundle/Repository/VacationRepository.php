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


    public function findByStatusAndUserAndDateInterval(
        $status ,
        Employee $employee,
        \DateTime $end)
    {
        $qb = $this->createQueryBuilder('v');

        $qb
            ->where('v.employee = :employeeId')
            ->andWhere('v.validationStatus =  :status')
            ->andWhere('v.type = :type')
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

    public function findAllQuery(
        $orderBy = null,
        $direction = 'desc'
    )
    {
        $qb = $this->createQueryBuilder('v');

        if ($orderBy)
            $qb->orderBy('v.'.$orderBy, $direction);

        return $qb->getQuery();
    }

    public function findAllByEmployeeQuery(
        $orderBy = null,
        $direction = 'desc',
        Employee $employee = null,
        $filters = []
    )
    {

        $qb = $this->createQueryBuilder('v');

        if ($orderBy)
            $qb->orderBy('v.'.$orderBy, $direction);
        if (null !== $employee)
            $qb->where('v.employee = :employee')
                ->setParameter('employee', $employee);
        if($this->count($filters) > 0) {
            foreach ($filters as $filter => $value)
                $qb->andWhere('v.'.$filter.' = \''.$value.'\'');

        }

        return $qb->getQuery();
    }
    
}