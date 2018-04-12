<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class LeaveType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder        
            ->add('type', ChoiceType::class, array(
                'label'    => 'Type',
                'required' => true,
                'choices' => array(
                        'leave' => 'leave',
                        'absence' => 'absence',
                    ),
            ))
        	->add('reason',TextType::class, array(
                'required' => false
            ))
            ->add('refuseReason',TextareaType::class)
            ->add('startDate',DateType::class, array('format' => 'MM/dd/yyyy',
               'widget' => 'single_text'))
            ->add('endDate',DateType::class, array('format' => 'MM/dd/yyyy',
               'widget' => 'single_text'));



/*add('type', ChoiceType::class, array(
                'choices'  => array(
                'congé' => 'leave',
                'absence' => 'absence',
                ),))
*/
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Leave'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_leave';
    }
}
