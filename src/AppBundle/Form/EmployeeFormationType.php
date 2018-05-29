<?php

namespace AppBundle\Form;

use AppBundle\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmployeeFormationType extends AbstractType
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

            ->add('formation', EntityType::class, array(

                'class' => 'AppBundle:Formation',
                'choice_label' => 'fullName',
                'placeholder' => 'Veuillez selectionner une formation',
                'query_builder' => function ( \AppBundle\Repository\FormationRepository $fr) {
                    return $fr->createQueryBuilder('f')
                        ->orderBy('f.level', 'ASC')
                        ->addOrderBy('f.diploma', 'ASC')
                        ->addOrderBy('f.speciality', 'ASC')
                        ;}
            ));

    }




    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\EmployeeFormation'
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
