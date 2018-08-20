<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 8/13/18
 * Time: 10:23
 */

namespace AppBundle\DataFixtures;



use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Employee;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    private $encoder;


    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param ContainerAwareInterface $container
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $employee = new Employee();
        $employee->setEnabled(true);
        $employee->setUsername($this->container->getParameter('admin_username'));
        $plainPassowrd = $this->container->getParameter('admin_passowrd');
        $employee->setPassword(
            $this->encoder->encodePassword($employee, $plainPassowrd)
        );
        $employee->setEmail($this->container->getParameter('mailer_user'));
        $employee->setValid(true);
        $employee->addRole("ROLE_ADMIN");
        $manager->persist($employee);
        $manager->flush();

    }

}