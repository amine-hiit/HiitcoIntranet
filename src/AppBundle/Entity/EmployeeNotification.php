<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmployeeNotification
 *
 * @ORM\Table(name="employee_notification")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeeNotificationRepository")
 */
class EmployeeNotification
{



    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Employee
     *
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="employeeNotification")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;


    /**
     * @var Notification
     *
     * @ORM\ManyToOne(targetEntity="Notification", inversedBy="employeeNotifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $notification;

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
     * EmployeeNotification constructor.
     * @param int $id
     */
    public function __construct()
    {
        $this->seen = false;
        $this->archived = false;
    }



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @param Employee $employee
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;
    }

    /**
     * @return bool
     */
    public function isSeen()
    {
        return $this->seen;
    }

    /**
     * @param bool $seen
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;
    }

    /**
     * @return bool
     */
    public function isArchived()
    {
        return $this->archived;
    }

    /**
     * @param bool $archived
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
    }

    /**
     * @return Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param Notification $notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
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
     * Get archived.
     *
     * @return bool
     */
    public function getArchived()
    {
        return $this->archived;
    }
}
