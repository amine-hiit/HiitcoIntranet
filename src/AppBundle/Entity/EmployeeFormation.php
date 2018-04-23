<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmployeeFormation
 *
 * @ORM\Table(name="employee_formation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeeFormationRepository")
 */
class EmployeeFormation
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
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="employeeFormation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;

    /**
     * @var Formation
     *
     * @ORM\ManyToOne(targetEntity="Formation", inversedBy="employeeFormation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formation;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="startDate", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="endDate", type="date", nullable=true)
     */
    private $endDate;


    /**
     * @var string
     *
     * @ORM\Column(name="organization", type="string", length=255)
     */
    private $organization;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;


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
     * Set employee.
     *
     * @param string $employee
     *
     * @return EmployeeFormation
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee.
     *
     * @return string
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set startDate.
     *
     * @param \DateTime|null $startDate
     *
     * @return EmployeeFormation
     */
    public function setStartDate($startDate = null)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return Formation
     */
    public function getFormation()
    {
        return $this->formation;
    }

    /**
     * @param Formation $formation
     */
    public function setFormation($formation)
    {
        $this->formation = $formation;
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
     * @return EmployeeFormation
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
     * Set organization.
     *
     * @param string $organization
     *
     * @return EmployeeFormation
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization.
     *
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set counrty.
     *
     * @param string $counrty
     *
     * @return EmployeeFormation
     */
    public function setCounrty($counrty)
    {
        $this->counrty = $counrty;

        return $this;
    }

    /**
     * Get counrty.
     *
     * @return string
     */
    public function getCounrty()
    {
        return $this->counrty;
    }

    /**
     * Set country.
     *
     * @param string $country
     *
     * @return EmployeeFormation
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
}