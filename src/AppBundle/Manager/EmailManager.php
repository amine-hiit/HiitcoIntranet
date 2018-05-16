<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 5/15/18
 * Time: 3:07 PM
 */

namespace AppBundle\Manager;


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
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function send(
        $subject,
        $from,
        $to,
        $template,
        array $args
    )
    {
        $mailer = $this->container->get('mailer');
        $message = $this->create($subject, $from, $to, $template, $args);
        $mailer->send($message);
    }


    public function create($subject,
        $from,
        $to,
        $template,
        array $args
    )
    {
        $templating = $this->container->get('templating');
        try {
            $rendredTemplate = $templating->render($template, $args);
        }catch (\Twig\Error\Error $e){
            $this->container->get('logger')->log($e,'Twig Error, Email non envoyé');
            return;
        }
        return  \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($rendredTemplate,'text/html');
    }



}