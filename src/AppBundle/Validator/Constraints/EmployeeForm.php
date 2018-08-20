<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 8/13/18
 * Time: 16:10
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class EmployeeForm extends Constraint
{
    const UNIQUE_VACATION_DATE_MESSAGE = 'unique.vacation.date.error';
    const START_DATE_GREATER_THAN_END_DATE_MESSAGE = 'start.date.greater.than.end.date.error';
    const VACATION_BALANCE_NOT_ENOUGH_MESSAGE = 'vacation.balance.not.enough.error';
    const NOT_ENOUGH_DAYS_UNTIL_START_DAY_MESSAGE = 'not.enough.days.until.start.day.error';
    const NOW_GREATER_THAN_START_DATE_MESSAGE = 'now.greater.than.start.date.error';



    public function validatedBy()
    {
        return 'employee_form_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }


}



