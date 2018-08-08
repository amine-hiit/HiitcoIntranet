<?php
namespace AppBundle\Form;
use AppBundle\Entity\Experience;
use AppBundle\Manager\SettingManager;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeRegistrationType extends AbstractType
{
    /**
     * @var SettingManager
     */
    private $setting;

    /**
     * EmployeeRegistrationType constructor.
     * @param SettingManager $setting
     */
    public function __construct(SettingManager $setting)
    {
        $this->setting = $setting;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $array = $this->setting->get('status');
        $status =['Choisir' => ''];

        foreach ($array as $value) {
            $status = array_merge($status, [$value => $value]);
        }



        $builder->add('maritalStatus',ChoiceType::class, array(
            'choices'  => $status,
        ))
            ->add('cnssNumber',TextType::class,array(
                'required' => true,
            ))

            ->add('startDate',DateType::class, array(
                'format' => 'MM/dd/yyyy',
                'widget' => 'single_text',
                'required' => true))

            ->add('currentPosition',TextType::class, array(
                'required' => true))
            ->add('status',ChoiceType::class, array(
                'choices'  => array(
                    $status
                ),
            ))
            ->add('civility',ChoiceType::class, array(
                'choices'  => array(
                    'select' => '' ,
                    'mme' => 'mme',
                    'ms' => 'ms',
                    'mr' => 'mr',
                ),
            ));

    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

}

