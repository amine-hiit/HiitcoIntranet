<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 5/15/18
 * Time: 3:07 PM
 */

namespace AppBundle\Manager;


use AppBundle\Entity\EmailType;
use AppBundle\Entity\Resume;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Email;

class EmailManager
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * EmailManager constructor.
     * @param ContainerInterface $container
     */
    public function __construct( ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function send(
        EmailType $emailType = null,
        $to = null,
        array $args = null,
        array $filesPaths = null
    )
    {
        $mailer = $this->container->get('mailer');
        $message = $this->create(
            'mo.amine.jabri@gmail.com',
            $to,
            $emailType,
            $args,
            $filesPaths
            );
        $mailer->send($message);
        //dump($message);die;
    }

    public function create(
        $from,
        $to,
        EmailType $emailType,
        array $args,
        array $filesPaths = null
    )
    {
        if (!is_array($to))
            $to = [$to];

        $subject = $this->container->get('translator')->trans($emailType->getLabel());
        $template = $emailType->getTemplate();

        if(null !== $emailType){
            $employees = $emailType->getEmployees()->getValues();
            $employeesByRoles = $this->container->get('app.employee.manager')
                ->findEmployeesByRoles($emailType->getRoles());
            $employees = array_merge($employees,$employeesByRoles );
        foreach ($employees as $employee)
            $to[] = $employee->getEmail();
        }

        $templating = $this->container->get('templating');

        try {
            $rendredTemplate = $templating->render($template, $args);
        }catch (\Twig\Error\Error $e){
            $this->container->get('logger')->log('error',$e->getMessage());
        }

        $rootDir = $this->container->get('kernel')->getRootDir();

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo(array_filter($to))
            ->setBody($rendredTemplate,'text/html')
        ;

        if($filesPaths !== null && count($filesPaths) != 0){
            foreach ($filesPaths as $filePath) {
                $message->attach(\Swift_Attachment::fromPath($rootDir.'/../web/'.$filePath));
            }
        }
        return $message;
    }
}