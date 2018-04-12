<?php
// src/AppBundle/Repository/LeaveRepository.php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class LeaveRepository extends EntityRepository
{
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
}