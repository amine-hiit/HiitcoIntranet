<?php
/**
 * Created by PhpStorm.
 * User: younes
 * Date: 5/4/18
 * Time: 9:15 AM
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;


class StartDateValidator extends ConstraintValidator
{
    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * @var TranslatorInterface $translator
     */
    private $translator;

    /**
     * @var TokenStorageInterface $ts
     */
    private $ts;

    public function __construct(
        EntityManager $em,
        TokenStorageInterface $ts,
        TranslatorInterface $translator )
    {
        $this->translator = $translator;
        $this->em = $em;
        $this->ts = $ts;
    }


    public function validate($object, Constraint $constraint)
    {
        $comparedToCurrentDay = $constraint->comparedToCurrentDay;
        if ($comparedToCurrentDay === 'before' && $object->getStartDate() > new \DateTime("now")){
            $this->context->buildViolation($this->translator->trans('startDate.after.toDay'))
                ->addViolation();
        }elseif ($comparedToCurrentDay === 'after' && $object->getStartDate() < new \DateTime("now")){
            $this->context->buildViolation($this->translator->trans('startDate.after.toDay'))
                ->addViolation();
        }
        if (property_exists(get_class($object),'endDate') && $object->getStartDate() > $object->getEndDate()){
            $this->context->buildViolation($this->translator->trans('startDate.superior.than.endDate'))->addViolation();
        }

    }

}

