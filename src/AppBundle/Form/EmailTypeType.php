<?php

namespace AppBundle\Form;

use AppBundle\Entity\Employee;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailTypeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employees', EntityType::class, array(
                'class' => 'AppBundle:Employee',
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.username', 'ASC');
                },
                'choice_label' => 'username',
                'multiple' => true,
                'by_reference' => false

            ))
            ->add('roles', ChoiceType::class,array(
                'required' => false,
                'choices'  => array(
                    'role.user' => 'ROLE_USER',
                    'role.employee' => 'ROLE_EMPLOYEE',
                    'role.hr' => 'ROLE_HR',
                    'role.admin' => 'ROLE_ADMIN',
                ),
                'multiple' => true,
                )
            )
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\EmailType'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_emailtype';
    }


}
