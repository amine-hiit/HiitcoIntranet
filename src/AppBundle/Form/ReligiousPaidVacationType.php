<?php

namespace AppBundle\Form;

use AppBundle\Entity\ReligiousPaidVacation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReligiousPaidVacationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('reference',ChoiceType::class,[
            'choices' => ReligiousPaidVacation::RELIGIOUS_VACATIONS,
        ])
            ->add('startDate',DateType::class, array(
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'required' => true)
            );
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ReligiousPaidVacation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_religiouspaidvacation';
    }


}
