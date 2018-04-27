<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LanguageRepository")
 */
class Language
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
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;


    /**
     * @var EmployeeLanguage
     * @ORM\OneToMany(targetEntity="EmployeeLanguage", mappedBy="language", cascade={"persist","remove"})
     */
    private $employeeLanguages;




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
     * Set label.
     *
     * @param string $label
     *
     * @return Language
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employees = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add employee.
     *
     * @param \AppBundle\Entity\Employee $employee
     *
     * @return Language
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
     * Add employeeLanguage.
     *
     * @param \AppBundle\Entity\EmployeeLanguage $employeeLanguage
     *
     * @return Language
     */
    public function addEmployeeLanguage(\AppBundle\Entity\EmployeeLanguage $employeeLanguage)
    {
        $this->employeeLanguages[] = $employeeLanguage;

        return $this;
    }

    /**
     * Remove employeeLanguage.
     *
     * @param \AppBundle\Entity\EmployeeLanguage $employeeLanguage
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEmployeeLanguage(\AppBundle\Entity\EmployeeLanguage $employeeLanguage)
    {
        return $this->employeeLanguages->removeElement($employeeLanguage);
    }

    /**
     * Get employeeLanguages.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmployeeLanguages()
    {
        return $this->employeeLanguages;
    }
    public function getFullName(){
        return $this->label;
    }
}
