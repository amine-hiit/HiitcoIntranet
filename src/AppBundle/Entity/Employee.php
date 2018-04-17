<?php
// src/AppBundle/Entity/Employee.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="employee")
 */
class Employee extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="first_name" , type="string")
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name" , type="string")
     */
    private $lastName;

    /**
     * @var \DateTime
     * @ORM\Column(name="birthday" , type="date")
     */
    private $birthday;

    /**
     * @var string
     * @ORM\Column(name="marital_status" , type="string")
     */
    private $maritalStatus;

    /**
     * var int
     * @ORM\Column(name="dependent_child" , type="integer")
     */
    private $dependentChild;

    /**
     * @var int
     * @ORM\Column(name="$photo_id" , type="integer")
     */
    private $photoId;

    /**
     * @var int
     * @ORM\Column(name="cnss" , type="integer")
     */
    private $cnssNumber;

    /**
     * @var int
     * @ORM\Column(name="phone_number" , type="integer")
     */
    private $phoneNumber;

    /**
     * @var string
     * @ORM\Column(name="address" , type="string")
     */
    private $address;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var string
     * @ORM\Column(name="current_position" , type="string")
     */
    private $currentPosition;

    /**
     * @var int
     * @ORM\Column(name="status" , type="integer")
     */
    private $status;


    /**
     * @ORM\ManyToMany(targetEntity="Formation")
     * @ORM\JoinTable(name="employees_formations",
     *      joinColumns={@ORM\JoinColumn(name="employee_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="formation_id", referencedColumnName="id")}
     *      )
     */
    private $formations;


    /**
     * @ORM\ManyToMany(targetEntity="Project")
     * @ORM\JoinTable(name="employees_project",
     *      joinColumns={@ORM\JoinColumn(name="employee_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")}
     *      )
     */
    private $projects;



    /**
     *
     * @ORM\ManyToMany(targetEntity="Language", mappedBy="employees")
     */
    private $languages;



    public function __construct()
    {
        parent::__construct();
        // your own logic
    }




}
