<?php
/**
 * Created by PhpStorm.
 * User: younes
 * Date: 5/4/18
 * Time: 9:15 AM
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Vacation;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use AppBundle\Manager\VacationManager;
use Symfony\Component\Validator\Constraint;

class VacationPeriodeValidator extends ConstraintValidator
{
    private $em;
    private $vm;
    private $ts;

    public function __construct(EntityManager $em, VacationManager $vm, TokenStorageInterface $ts)
    {
        $this->em = $em;
        $this->vm = $vm;
        $this->ts = $ts;
    }


    public function validate($object, Constraint $constraint)
    {

        $startDate = $object->getStartDate();
        $dayPeriod = $object->getDayPeriod();
        $endDate = $object->getEndDate();

        if(null === $endDate || $dayPeriod !== Vacation::ALL_DAY )
            $endDate = $object->setEndDate(clone($startDate));

        $employee = $this->ts->getToken()->getUser();
        $conflicts = $this->vm->findOverlappingWithRange($object, $employee);
        $vacationDuration = $this->vm->calculateDuration($object);
        $vacationBalance = $this->vm->calculateVacationBalance(
            $employee,
            true
        );
        $daysUntilStartDate = $this->vm
            ->calculateDaysUntilStartDate($object);
        $now = new \DateTime();


        if ($now > $startDate) {

            $this->context->buildViolation($constraint::NOW_GREATER_THAN_START_DATE_MESSAGE)
                ->addViolation();
        }

        if ($endDate < $startDate) {

            $this->context->buildViolation($constraint::START_DATE_GREATER_THAN_END_DATE_MESSAGE)
                ->addViolation();
        }

        if (count($conflicts) > 0) {

            $this->context->buildViolation($constraint::UNIQUE_VACATION_DATE_MESSAGE)
                ->addViolation();
        }
        /** absence type is not concerned by the rest of constraints*/
        if ($object->getType() === Vacation::ABSENCE) {
            return;
        }

        if ($daysUntilStartDate < 30) {

            $this->context->buildViolation($constraint::NOT_ENOUGH_DAYS_UNTIL_START_DAY_MESSAGE)
                ->addViolation();
        }

        if ($vacationDuration > $vacationBalance) {

            $this->context->buildViolation($constraint::VACATION_BALANCE_NOT_ENOUGH_MESSAGE)
                ->addViolation();
        }


    }

}

