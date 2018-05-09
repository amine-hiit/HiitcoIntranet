<?php
/**
 * Created by PhpStorm.
 * User: younes
 * Date: 5/8/18
 * Time: 5:12 PM
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Notification;
use AppBundle\Entity\NotificationType;
use AppBundle\Repository\NotificationTypeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class NotificationManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /** @var KernelInterface $kernel */
    private $kernel;


    /**
     * NotificationManager constructor.
     * @param EntityManagerInterface $em
     */

    public function __construct(EntityManagerInterface $em, KernelInterface $kernel)
    {
        $this->em = $em;
        $this->kernel = $kernel;
    }


    public function findOneByLabel($notificationType)
    {
        return $this->em->getRepository('AppBundle:NotificationType')
            ->findOneByLabel($notificationType);
    }

    /** create message from message structure */
    public function createMessage($notificationType, $args)
    {
        $messageFile = Yaml::parseFile(
            $this->kernel->getRootDir().
            '/../src/AppBundle/Resources/notification/message.yml'
        );
        $message = vsprintf($messageFile[$notificationType], $args);
        dump($message);
        die;
    }

    /** create message from message structure */
    public function generateNotification($notificationTypeLabel, $args, $url, $employees)
    {

        $notification = new Notification();
        $notificationType = $this->findOneByLabel($notificationTypeLabel);

        $notification->setUrl($url);
        $notification->setNotificationType($notificationType);
        $notification->setEmployeeNotifications($employees);
        $this->em->persist($notification);
        $this->em->flush();
    }

}