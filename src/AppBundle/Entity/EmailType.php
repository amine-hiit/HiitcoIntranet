<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * EmailType
 *
 * @ORM\Table(name="email_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmailTypeRepository")
 */
class EmailType
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
     * @ORM\Column(name="label",unique=true, type="string", length=255)
     */
    private $label;

    /**
     * @var string|null
     *
     * @ORM\Column(name="template", type="string", length=1024, nullable=true)
     */
    private $template;




    /**
     * @ORM\ManyToMany(targetEntity="Employee", cascade={"persist"})
     * @ORM\JoinTable(name="concerned_employee",
     *      joinColumns={@ORM\JoinColumn(name="email_type_id", referencedColumnName="id")},
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
     * Set label.
     *
     * @param string $label
     *
     * @return EmailType
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
     * Set template.
     *
     * @param string|null $template
     *
     * @return EmailType
     */
    public function setTemplate($template = null)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template.
     *
     * @return string|null
     */
    public function getTemplate()
    {
        return $this->template;
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
     * @return EmailType
     */
    public function addEmployee(\AppBundle\Entity\Employee $employee)
    {
        dump($this->employees);
        $this->employees[] = $employee;
        //dump($this->employees);die;
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
        dump('test');die;
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
}
