<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeLanguageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('level',ChoiceType::class, array(
            'choices'  => array(
                'level' => '' ,
                    'beginner' => 'beginner',
                    'intermediate' => 'intermediate',
                    'advanced' => 'advanced',
                    'native' => 'native',
            )))
            ->add('language', EntityType::class, array(

                'class' => 'AppBundle:Language',
                'choice_label' => 'fullName',
                'placeholder' => 'Langue',
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\EmployeeLanguage'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_employeelanguage';
    }


}
