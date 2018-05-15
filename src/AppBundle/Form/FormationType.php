<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('diploma')
            ->add('level')
            ->add('speciality')
        ;



        $formModifier = function (FormInterface $form, Formation $formation = null) {
            $form->add('formation', EntityType::class, array(
                'class' => 'AppBundle:Formation',
                'choice_label' => 'fullName',
                'placeholder' => $formation,
                'query_builder' => function ( \AppBundle\Repository\FormationRepository $fr) {
                    return $fr->createQueryBuilder('f')
                        ->orderBy('f.level', 'ASC')
                        ->addOrderBy('f.diploma', 'ASC')
                        ->addOrderBy('f.speciality', 'ASC')
                        ;}
            ));
        };




    }/**
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
        return 'appbundle_formation';
    }
}

