<?php

namespace AppBundle\Entity;

use AppBundle\AppBundle;
use Doctrine\ORM\Mapping as ORM;

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
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Employee|null
     *
     * @ORM\Column(name="sender_id", type="integer", nullable=true)
     */
    private $sender;

    /**
     * @var bool
     *
     * @ORM\Column(name="seen", type="boolean")
     */
    private $seen = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="archived", type="boolean")
     */
    private $archived = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var NotificationType
     * @ORM\ManyToOne(targetEntity="NotificationType")
     * @ORM\JoinColumn(name="notification_type_id", referencedColumnName="id")
     */
    private $notificationType;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string")
     */
    private $url;

    /**
     * Many Notifications concern Many Employees.
     * @ORM\ManyToMany(targetEntity="Employee")
     * @ORM\JoinTable(name="notifications_employees",
     *      joinColumns={@ORM\JoinColumn(name="notification_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="employee_id", referencedColumnName="id")}
     *      )
     */
    private $employees;



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
     * Set seen.
     *
     * @param bool $seen
     *
     * @return Notification
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen.
     *
     * @return bool
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set archived.
     *
     * @param bool $archived
     *
     * @return Notification
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Get archived.
     *
     * @return bool
     */
    public function getArchived()
    {
        return $this->archived;
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
     * Add employee.
     *
     * @param \AppBundle\Entity\Employee $employee
     *
     * @return Notification
     */
    public function addEmployee(\AppBundle\Entity\Employee $employee)
    {
        $this->employees[] = $employee;

        return $this;
    }

    /**
     * Remove employee.
     *
     * @param \AppBundle\Entity\Employee $employee
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEmployee(\AppBundle\Entity\Employee $employee)
    {
        return $this->employees->removeElement($employee);
    }

    /**
     * Get employees.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmployees()
    {
        return $this->employees;
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
}
