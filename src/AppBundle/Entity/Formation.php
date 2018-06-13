<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formation
 *
 * @ORM\Table(name="formation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeeFormationRepository")
 */
    class Formation
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
/*
    /**
     * @var Formation
     *
     * @ORM\ManyToOne(targetEntity="Formation~", inversedBy="employeeFormation")
     * @ORM\JoinColumn(nullable=false)

    private $formation;*/

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
     * @var string
     *
     * @ORM\Column(name="diploma", type="string", length=255)
     */
    private $diploma;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=255)
     */
    private $level;


    /**
     * @var string
     *
     * @ORM\Column(name="speciality", type="string", length=255)
     */
    private $speciality;


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
     * @return Formation
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
     * @return Formation
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
     * @return Formation
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
     * @return Formation
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
     * @return Formation
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
     * @return Formation
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

    /**
     * Set diploma.
     *
     * @param string $diploma
     *
     * @return Formation
     */
    public function setDiploma($diploma)
    {
        $this->diploma = $diploma;

        return $this;
    }

    /**
     * Get diploma.
     *
     * @return string
     */
    public function getDiploma()
    {
        return $this->diploma;
    }

    /**
     * Set level.
     *
     * @param string $level
     *
     * @return Formation
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set speciality.
     *
     * @param string $speciality
     *
     * @return Formation
     */
    public function setSpeciality($speciality)
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * Get speciality.
     *
     * @return string
     */
    public function getSpeciality()
    {
        return $this->speciality;
    }
}
