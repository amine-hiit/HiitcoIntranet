<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vacation
 *
 * @ORM\Table(name="vacation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VacationRepository")
 */
class Vacation
{
    const VACATION = 'vacation';
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
     * @ORM\Column(name ="vacation_days_left", nullable=true)
     */
    private $vacationDaysLeft;

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
     * @return Vacation
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
     * @return Vacation
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
     * @return Vacation
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
     * @return Vacation
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
     * @return Vacation
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
     * @return Vacation
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
     * Set vacationDaysLeft.
     *
     * @param string|null $vacationDaysLeft
     *
     * @return Vacation
     */
    public function setVacationDaysLeft($vacationDaysLeft = null)
    {
        $this->vacationDaysLeft = $vacationDaysLeft;

        return $this;
    }

    /**
     * Get vacationDaysLeft.
     *
     * @return string|null
     */
    public function getVacationDaysLeft()
    {
        return $this->vacationDaysLeft;
    }

    /**
     * Set employee.
     *
     * @param \AppBundle\Entity\Employee $employee
     *
     * @return Vacation
     */
    public function setEmployee(\AppBundle\Entity\Employee $employee)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee.
     *
     * @return \AppBundle\Entity\Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }
}