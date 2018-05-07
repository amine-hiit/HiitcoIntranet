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
    const UNIQUE_VACATION_DATE_MESSAGE = 'Vous avez déjà une demande de congé dans cette periode.';
    const START_DATE_GREATER_THAN_END_DATE_MESSAGE = 'La date de début ne doit pas être supérieur que celle de la fin';
    const VACATION_BALANCE_NOT_ENOUGH_MESSAGE = 'Vous n\'avez pas assez de solde de congé pour demander un congé de cette durée';
    const NOT_ENOUGH_DAYS_UNTIL_START_DAY_MESSAGE = 'Le congé doit être demandé 30 jour à l\'avance';
    const NOW_GREATER_THAN_START_DATE_MESSAGE = 'Vous ne pouvez pas demander un congé dont le début est avant aujourd\'hui';


    public function validatedBy()
    {
        return 'vacation_date_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}



