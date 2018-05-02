<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formation
 *
 * @ORM\Table(name="formation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FormationRepository")
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



    /** var EmployeeFormation
     *
     * @ORM\OneToMany(targetEntity="EmployeeFormation", mappedBy="formation")
     */
    private $employeeFormation;





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
     * Set startDate.
     *
     * @param \DateTime $startDate
     *
     * @return Formation
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate.
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate.
     *
     * @param \DateTime $endDate
     *
     * @return Formation
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate.
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employeeFormation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employee_formation = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add employeeFormation.
     *
     * @param \AppBundle\Entity\EmployeeFormation $employeeFormation
     *
     * @return Formation
     */
    public function addEmployeeFormation(\AppBundle\Entity\EmployeeFormation $employeeFormation)
    {
        $this->employeeFormation[] = $employeeFormation;

        return $this;
    }

    /**
     * Remove employeeFormation.
     *
     * @param \AppBundle\Entity\EmployeeFormation $employeeFormation
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEmployeeFormation(\AppBundle\Entity\EmployeeFormation $employeeFormation)
    {
        return $this->employeeFormation->removeElement($employeeFormation);
    }

    /**
     * Get employeeFormation.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmployeeFormation()
    {
        return $this->employeeFormation;
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

    public function getFullName(){
        return $this->level.', '.$this->diploma.', '.$this->speciality;
    }
}
