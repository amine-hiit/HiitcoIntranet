<?php
/**
 * Created by PhpStorm.
 * User: younes
 * Date: 5/4/18
 * Time: 9:15 AM
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use AppBundle\Manager\VacationManager;
use Symfony\Component\Validator\Constraint;

class UniqueVacationDateValidation extends ConstraintValidator
{
    private $em;
    private $vm;

    public function __construct(EntityManager $em,VacationManager $vm )
    {
        $this->em = $em;
        $this->vm = $vm;

    }


    public function validate($object, Constraint $constraint)
    {
        $conflicts = $this->vm->findOverlappingWithRange($object);
        $vacationDuration = $this->vm->vacationDuration($object);

        if ($vacationDuration) {

            $this->context->buildViolation($constraint::UNIQUE_VACATION_DATE_MESSAGE)
                ->addViolation();
        }

        if (count($conflicts) > 0) {

            $this->context->buildViolation($constraint::UNIQUE_VACATION_DATE_MESSAGE)
                ->addViolation();
        }
    }

}

