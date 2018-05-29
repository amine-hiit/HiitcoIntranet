<?php

namespace AppBundle\Form;

use AppBundle\Entity\Vacation;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type',
                ChoiceType::class,
                array(
                    'label' => 'Type',
                    'required' => true,
                    'choices' => array(
                        'Vous demandez' => '',
                        'vacation' => 'vacation',
                        'absence' => 'absence',
                    ),
                    'data' => ''
                )
            )
            ->add(
                'reason',
                TextType::class,
                array(
                    'required' => false
                )
            )
            ->add('refuseReason', TextareaType::class)
            ->add('startDate', DateType::class,
                array(
                    'format' => 'MM/dd/yyyy',
                    'widget' => 'single_text',
                    'attr' => array(
                        'id' => 'vacation_start_date'
                    )

                )
            )
            ->add('endDate', DateType::class,
                array(
                    'format' => 'MM/dd/yyyy',
                    'required' => false,
                    'widget' => 'single_text',
                    'attr' => array(
                        'id' => 'vacation_end_date'
                    )
                )
            )
            ->add(
                'dayPeriod',
                ChoiceType::class,
                array(
                    'expanded' => true,
                    'choices' => array(
                        ' Matiné' => Vacation::MORNING,
                        ' Après-midi' => Vacation::AFTERNOON,
                        ' Toute la journée' => Vacation::ALL_DAY,
                    ),
                )
            );

    }




    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Vacation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_vacation';
    }


}
