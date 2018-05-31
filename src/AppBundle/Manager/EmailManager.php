<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 5/15/18
 * Time: 3:07 PM
 */

namespace AppBundle\Manager;


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
        $subject,
        $from,
        $to,
        $template,
        array $args,
        array $filesPaths = null

    )
    {
        $mailer = $this->container->get('mailer');
        $message = $this->create($subject, $from, $to, $template, $args, $filesPaths );
        $mailer->send($message);
    }


    public function create($subject,
        $from,
        $to,
        $template,
        array $args,
        array $filesPaths = null
    )
    {
        $templating = $this->container->get('templating');
        try {
            $rendredTemplate = $templating->render($template, $args);
        }catch (\Twig\Error\Error $e){
            dump($e);die;
        }

        $rootDir = $this->container->get('kernel')->getRootDir();

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($rendredTemplate,'text/html');


        if($filesPaths !== null && count($filesPaths) != 0){
            foreach ($filesPaths as $filePath) {
                $message->attach(\Swift_Attachment::fromPath($rootDir.'/../web/'.$filePath));
            }
        }

        return $message;
    }



}