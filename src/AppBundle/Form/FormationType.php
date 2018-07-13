<?php

namespace AppBundle\Form;

use AppBundle\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FormationType extends AbstractType
{
    Private $builder;
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->builder = $builder;
        $this->modifyForm();
    }





    public function modifyForm()
    {
        $this->builder->add('startDate',DateType::class, array(
            'format' => 'MM/dd/yyyy',
            'widget' => 'single_text'))
            ->add('endDate',DateType::class, array(
                'format' => 'MM/dd/yyyy',
                'widget' => 'single_text'))
            ->add('organization',TextType::class)
            ->add('country',TextType::class)
            ->add('employee')

            ->add('level', ChoiceType::class,[
                'required'   => true,
                'choices'  => array(
                    'choose' => '',
                    'bac+1' => 1,
                    'bac+2' => 2,
                    'bac+3' => 3,
                    'bac+4' => 4,
                    'bac+5' => 5,
                    'other' => -1,
                )
            ])
            ->add('diploma', TextType::class)
            ->add('speciality', TextType::class);

    }




    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Formation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_employeeformation';
    }


}
