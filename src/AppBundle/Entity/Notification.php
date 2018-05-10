<?php

namespace AppBundle\Entity;

use AppBundle\AppBundle;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationRepository")
 */
class Notification
{
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @var int
     * @Groups({"notification"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"notification"})
     * @var Employee|null
     *
     * @ORM\Column(name="sender_id", type="integer", nullable=true)
     */
    private $sender;

    /**
     * @var \DateTime
     * @Groups({"notification"})
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var NotificationType
     * @Groups({"notification"})
     * @ORM\ManyToOne(targetEntity="NotificationType")
     * @ORM\JoinColumn(name="notification_type_id", referencedColumnName="id")
     */
    private $notificationType;

    /**
     * @var string
     * @Groups({"notification"})
     * @ORM\Column(name="url", type="string")
     */
    private $url;

    /**
     * @var string
     * @Groups({"notification"})
     * @ORM\Column(name="message", type="string")
     */
    private $message;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @Groups({"notification"})
     * @ORM\OneToMany(
     *     targetEntity="EmployeeNotification",
     *     mappedBy="notification",
     *     cascade={"persist","remove"}
     *     )
     */
    private $employeeNotifications;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sender.
     *
     * @param int|null $sender
     *
     * @return Notification
     */
    public function setSender($sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender.
     *
     * @return int|null
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set notificationType.
     *
     * @param \AppBundle\Entity\NotificationType|null $notificationType
     *
     * @return Notification
     */
    public function setNotificationType(\AppBundle\Entity\NotificationType $notificationType = null)
    {
        $this->notificationType = $notificationType;

        return $this;
    }

    /**
     * Get notificationType.
     *
     * @return \AppBundle\Entity\NotificationType|null
     */
    public function getNotificationType()
    {
        return $this->notificationType;
    }


    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Notification
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return Notification
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Add employeeNotification.
     *
     * @param \AppBundle\Entity\EmployeeNotification $employeeNotification
     *
     * @return Notification
     */
    public function addEmployeeNotification(\AppBundle\Entity\EmployeeNotification $employeeNotification)
    {
        $this->employeeNotifications[] = $employeeNotification;

        return $this;
    }

    /**
     * Remove employeeNotification.
     *
     * @param \AppBundle\Entity\EmployeeNotification $employeeNotification
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEmployeeNotification(\AppBundle\Entity\EmployeeNotification $employeeNotification)
    {
        return $this->employeeNotifications->removeElement($employeeNotification);
    }

    /**
     * Get employeeNotifications.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmployeeNotifications()
    {
        return $this->employeeNotifications;
    }


    /**
     * Set employeeNotifications.
     *
     * @return Notification
     */
    public function setEmployeeNotifications($employees)
    {
        foreach ($employees as $employee){
            $employeeNotification = new EmployeeNotification();
            $employeeNotification->setEmployee($employee);
            $employeeNotification->setNotification($this);
            $this->addEmployeeNotification($employeeNotification);
        }
    }



    /**
     * Set message.
     *
     * @param string $message
     *
     * @return Notification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
