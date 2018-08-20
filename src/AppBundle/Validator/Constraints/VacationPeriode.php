<?php
/**
 * Created by PhpStorm.
 * User: younes
 * Date: 5/4/18
 * Time: 9:01 AM
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class VacationPeriode extends Constraint
{
    const UNIQUE_VACATION_DATE_MESSAGE = 'unique.vacation.date.error';
    const START_DATE_GREATER_THAN_END_DATE_MESSAGE = 'start.date.greater.than.end.date.error';
    const VACATION_BALANCE_NOT_ENOUGH_MESSAGE = 'vacation.balance.not.enough.error';
    const NOT_ENOUGH_DAYS_UNTIL_START_DAY_MESSAGE = 'not.enough.days.until.start.day.error';
    const NOW_GREATER_THAN_START_DATE_MESSAGE = 'now.greater.than.start.date.error';



    public function validatedBy()
    {
        return 'vacation_date_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }


}



