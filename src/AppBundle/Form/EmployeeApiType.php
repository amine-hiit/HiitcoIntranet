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

class EmployeeApiType extends AbstractType
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
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'required' => true))
            ->add('dependentChild', IntegerType::class ,array(
                'attr' => array('min' => 0)))
            ->add('phoneNumber',TextType::class,array(
                'required' => true,
            ))
            ->add('address',TextType::class,array('by_reference' => false))


            ->add('formations', CollectionType::class, array(
                'entry_type' => FormationType::class,
                'allow_add' => true,
                'delete_empty' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__name__',
                'by_reference' => false,
            ))
            ->add('experiences', CollectionType::class, array(
                'required' => false,
                'entry_type' => ExperienceType::class,
                'allow_add' => true,
                'delete_empty' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__name__',
                'by_reference' => false,

            ))
            ->add('employee_languages', CollectionType::class, array(

                'entry_type' => EmployeeLanguageType::class,
                'allow_add' => true,
                'delete_empty' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__name__',
                'by_reference' => false,

            ))
            ->add('projects', CollectionType::class, array(
                'required' => false,
                'entry_type' => ProjectType::class,
                'allow_add' => true,
                'delete_empty' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__name__',
                'by_reference' => false,


            ));




        //->add('employeeformation', FormationType::class);

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
