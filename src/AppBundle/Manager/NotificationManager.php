<?php
/**
 * Created by PhpStorm.
 * User: younes
 * Date: 5/8/18
 * Time: 5:12 PM
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Employee;
use AppBundle\Entity\EmployeeNotification;
use AppBundle\Entity\Notification;
use AppBundle\Entity\NotificationType;
use AppBundle\Repository\NotificationTypeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class NotificationManager
{
    const MESSAGES_FILE_DIR = '/../src/AppBundle/Resources/notification/message.yml';
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

    public function findLastTeenByEmployee(Employee $employee)
    {
        return $this->em->getRepository('AppBundle:EmployeeNotification')
            ->findLastTeenByEmployee($employee);
    }

    public function countUnseenByEmployee(Employee $employee)
    {
        return $this->em->getRepository('AppBundle:EmployeeNotification')
            ->countUnseenByEmployee($employee);
    }

    /** make notification as seen */
    public function setSeenNotification($notificationId, Employee $employee)
    {

        $notification = $this->findOneNotification($notificationId);
        $notificationEmployee = $this->findOneEmployeeNotification($notification, $employee);
        $notificationEmployee->setSeen(true);
        $this->persist($notification);
        $this->flush();
    }

    /** create message from message structure */
    public function createMessage($notificationType, $args)
    {
        $messageFile = Yaml::parseFile(
            $this->kernel->getRootDir().self::MESSAGES_FILE_DIR);

        return vsprintf($messageFile[$notificationType], $args);
    }

    /** create message from message structure */
    public function generateNotification($notificationTypeLabel, array $args = null, $url, $employees)
    {
        $notification = new Notification();
        $notificationType = $this->findOneByLabel($notificationTypeLabel);
        $notification->setMessage($this->createMessage($notificationTypeLabel, $args));

        $notification->setUrl($url);
        $notification->setNotificationType($notificationType);
        $notification->setEmployeeNotifications($employees);
        $this->em->persist($notification);
        $this->em->flush();
    }

    /** return last with offset  */
    public function findLastByEmployeeWithOffset(Employee $employee, $offset)
    {
        return
            $this->em->getRepository('AppBundle:EmployeeNotification')
            ->findLastByEmployeeWithOffset($employee, $offset)
            ;
    }



    public function findOneEmployeeNotification(Notification $notification, Employee $employee)
    {
        return $this->em
            ->getRepository('AppBundle:EmployeeNotification')
            ->findOneEmployeeNotification( $notification, $employee);
    }

    public function findOneNotification($notificationId)
    {
        return $this->em
            ->getRepository('AppBundle:Notification')
            ->find($notificationId);
    }


    public function persist($notification)
    {
        $this->em->persist($notification);
    }

    public function flush()
    {
        $this->em->flush();
    }

}