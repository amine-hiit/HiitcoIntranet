<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Leave
 *
 * @ORM\Table(name="leaves")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LeaveRepository")
 */
class Leave
{
    const LEAVE = 'leave';
    const ABSENCE = 'absence';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     * @ORM\Column(name="type", type="string")
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(name="reason", type="text" , nullable=true)
     */
    private $reason;

    /**
     * @var string
     * @ORM\Column(name="refuse_reason", type="text" , nullable=true)
     */
    private $refuseReason;


    /**
     * @var Employee
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee", cascade={"persist"})
     * @ORM\JoinColumn(name ="employee_id",referencedColumnName="id", nullable=false)
     */
    private $employee;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @var int
     * @ORM\Column(name ="leave_days_left", nullable=true)
     */
    private $leaveDaysLeft;
   
    /**
     * @var string
     * @ORM\Column(name ="validation_status", type="text", nullable=true)
     */
    private $validationStatus;


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
     * Set type.
     *
     * @param string $type
     *
     * @return Leave
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set reason.
     *
     * @param string|null $reason
     *
     * @return Leave
     */
    public function setReason($reason = null)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason.
     *
     * @return string|null
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set refuseReason.
     *
     * @param string|null $refuseReason
     *
     * @return Leave
     */
    public function setRefuseReason($refuseReason = null)
    {
        $this->refuseReason = $refuseReason;

        return $this;
    }


    /**
     * Get refuseReason.
     *
     * @return string|null
     */
    public function getRefuseReason()
    {
        return $this->refuseReason;
    }

    /**
     * Set startDate.
     *
     * @param \DateTime|null $startDate
     *
     * @return Leave
     */
    public function setStartDate($startDate = null)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate.
     *
     * @return \DateTime|null
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate.
     *
     * @param \DateTime|null $endDate
     *
     * @return Leave
     */
    public function setEndDate($endDate = null)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate.
     *
     * @return \DateTime|null
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set validationStatus.
     *
     * @param string|null $validationStatus
     *
     * @return Leave
     */
    public function setValidationStatus($validationStatus = null)
    {
        $this->validationStatus = $validationStatus;

        return $this;
    }

    /**
     * Get validationStatus.
     *
     * @return string|null
     */
    public function getValidationStatus()
    {
        return $this->validationStatus;
    }

    /**
     * Set leaveDaysLeft.
     *
     * @param string|null $leaveDaysLeft
     *
     * @return Leave
     */
    public function setLeaveDaysLeft($leaveDaysLeft = null)
    {
        $this->leaveDaysLeft = $leaveDaysLeft;

        return $this;
    }

    /**
     * Get leaveDaysLeft.
     *
     * @return string|null
     */
    public function getLeaveDaysLeft()
    {
        return $this->leaveDaysLeft;
    }

    /**
     * Set employee.
     *
     * @param string|null $employee
     *
     * @return Leave
     */
    public function setEmployee($employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee.
     *
     * @return string|null
     */
    public function getEmployee()
    {
        return $this->employee;
    }
}
