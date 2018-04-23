<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('firstName')
            ->add('lastName')
            ->add('birthday')
            ->add('maritalStatus')
            ->add('dependentChild')
            ->add('cnssNumber')
            ->add('phoneNumber')
            ->add('address')
            ->add('startDate')
            ->add('currentPosition')
            ->add('status')
            ->add('employee_formations', CollectionType::class, array(
                // each entry in the array will be an "email" field
                'entry_type' => EmployeeFormationType::class,
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