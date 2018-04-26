<?php

namespace AppBundle\Form;

use AppBundle\Entity\Experience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class, array(
                'required' => true))
            ->add('lastName',TextType::class, array(
                'required' => true))

            ->add('birthday',DateType::class, array(
                'format' => 'MM/dd/yyyy',
                'widget' => 'single_text',
                'required' => true))
            ->add('maritalStatus',ChoiceType::class, array(
                'choices'  => array(
                    'Choisir' => '' ,
                    'Célibataire' => 'Célibataire',
                    'veuf' => 'veuf',
                    'marié' => 'marié',
                    'Divorcé' => 'Divorcé',
                ),
            ))
            ->add('dependentChild', IntegerType::class ,array(
                'attr' => array('min' => 0)))
            ->add('cnssNumber')
            ->add('phoneNumber')
            ->add('address')

            ->add('startDate',DateType::class, array(
                'format' => 'MM/dd/yyyy',
                'widget' => 'single_text',
                'required' => true))
            ->add('avatar',     AvatarType::class)

            ->add('currentPosition',TextType::class, array(
                'required' => true))
            ->add('status')

            ->add('employee_formations', CollectionType::class, array(
                'entry_type' => EmployeeFormationType::class,
                'allow_add' => true,
                'delete_empty' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__name__',
            ))

            ->add('experiences', CollectionType::class, array(
                'entry_type' => ExperienceType::class,
                'allow_add' => true,
                'delete_empty' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__name__',

            ));




        //->add('employeeformation', EmployeeFormationType::class);

    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Employee'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_employee';
    }


}
