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
class UniqueVacationDate extends Constraint
{
    const UNIQUE_VACATION_DATE_MESSAGE = 'Vous avez déjà une demande de congé dans cette periode.';


    public function validatedBy()
    {
        return 'unique_vacation_date';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
    public $message = 'Vous avez déjà une demande de congé dans cette periode.';

}



