<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 8/13/18
 * Time: 16:13
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;

class EmployeeFormValidator extends ConstraintValidator
{
    public function validate($object, Constraint $constraint)
    {

        $birthday = $object->getBirthday();

        if ($birthday > new \DateTime("now")) {

            $this->context->buildViolation($this->translator->trans($constraint::NOW_GREATER_THAN_BIRTHDAY_MESSAGE))
                ->addViolation();
        }

    }

}

