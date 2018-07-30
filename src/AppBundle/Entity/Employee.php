<?php
// src/AppBundle/Entity/Employee.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;


/**
 * Employee
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeeRepository")
 * @ORM\Table(name="employee")
 */
class Employee extends BaseUser
{

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_HR = 'ROLE_HR';
    const ROLE_EMPLOYEE = 'ROLE_EMPLOYEE';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    protected $username;

    /**
     * @var bool
     * @ORM\Column(name="valid", type="boolean", nullable=false )
     */
    private $valid = false;


    /**
     * @var string
     * @ORM\Column(name="first_name" , type="string", nullable=true)
     */
    private $firstName;


    /**
     * @var string
     * @ORM\Column(name="last_name" , type="string", nullable=true)
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(name="civility" , type="string", nullable=true)
     */
    private $civility;

    /**
     * @var \DateTime
     * @ORM\Column(name="birthday" , type="date", nullable=true)
     */
    private $birthday;

    /**
     * @var string
     * @ORM\Column(name="marital_status" , type="string", nullable=true)
     */
    private $maritalStatus;

    /**
     *
     * @Assert\Range(
     *      min = 0,
     *      max = 10
     * )
     * var int
     * @ORM\Column(name="dependent_child" , type="integer", nullable=true)
     */
    private $dependentChild;

    /**
     * @var int
     * @ORM\Column(name="photo_id" , type="integer", nullable=true)
     */
    private $photoId;

    /**
     *
     * @Assert\Range(
     *      min = 0,
     *      max = 999999999
     * )
     * @var int
     * @ORM\Column(name="cnss" , type="integer", nullable=true)
     */
    private $cnssNumber;

    /**
     * @var string
     * @ORM\Column(name="phone_number" , type="string", nullable=true)
     * @Assert\Regex(
     *     pattern="/0[756][0-9]{8,8}$/",
     *     match=true,
     *     message="Your name cannot contain a number"
     * )
     */
    private $phoneNumber;

    /**
     * @var string
     * @ORM\Column(name="address" , type="string", nullable=true)
     */
    private $address;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="start_date", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var string
     * @ORM\Column(name="current_position" , type="string", nullable=true)
     */
    private $currentPosition;

    /**
     * @var string
     * @ORM\Column(name="status" , type="string", nullable=true)
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="EmployeeNotification", mappedBy="employee",cascade={"remove"})
     *
     */
    private $notifications;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Formation", mappedBy="employee", cascade={"persist","remove"})
     */
    private $formations;

    /**
     * @var EmployeeLanguage
     * @ORM\OneToMany(targetEntity="EmployeeLanguage", mappedBy="employee", cascade={"persist","remove"})
     */
    private $employeeLanguages;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Experience", mappedBy="employee",cascade={"persist","remove"})
     *
     */
    private $experiences;

    /**
     * @var Project
     * @ORM\OneToMany(targetEntity="Project", mappedBy="employee",cascade={"persist","remove"})
     */
    private $projects;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Cooptation", mappedBy="employee",cascade={"persist","remove"})
     *
     */
    private $cooptations;


    /**
     * @var Avatar
     *
     * @ORM\OneToOne(targetEntity="Avatar", cascade={"persist","remove"})
     *
     */
    private $avatar;



    public function __construct()
    {
        parent::__construct();
        $this->notifications = new ArrayCollection();
        $this->employeeLanguages = new ArrayCollection();
    }


    /**
     * Add employeeLanguage.
     *
     * @param \AppBundle\Entity\EmployeeLanguage $employeeLanguage
     *
     * @return Employee
     */
    public function addEmployeeLanguage(\AppBundle\Entity\EmployeeLanguage $employeeLanguage)
    {
        $employeeLanguage->setEmployee($this);
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

    /**
     * Add experience.
     *
     * @param \AppBundle\Entity\Experience $experience
     *
     * @return Employee
     */
    public function addExperience(\AppBundle\Entity\Experience $experience)
    {
        $experience->setEmployee($this);
        $this->experiences[] = $experience;

        return $this;
    }

    /**
     * Remove experience.
     *
     * @param \AppBundle\Entity\Experience $experience
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeExperience(\AppBundle\Entity\Experience $experience)
    {
        return $this->experiences->removeElement($experience);
    }

    /**
     * Get experiences.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExperiences()
    {
        return $this->experiences;
    }

    /**
     * Set valid.
     *
     * @param bool $valid
     *
     * @return Employee
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * Get valid.
     *
     * @return bool
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Set firstName.
     *
     * @param string|null $firstName
     *
     * @return Employee
     */
    public function setFirstName($firstName = null)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string|null $lastName
     *
     * @return Employee
     */
    public function setLastName($lastName = null)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string|null
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set birthday.
     *
     * @param \DateTime|null $birthday
     *
     * @return Employee
     */
    public function setBirthday($birthday = null)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday.
     *
     * @return \DateTime|null
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set maritalStatus.
     *
     * @param string|null $maritalStatus
     *
     * @return Employee
     */
    public function setMaritalStatus($maritalStatus = null)
    {
        $this->maritalStatus = $maritalStatus;

        return $this;
    }

    /**
     * Get maritalStatus.
     *
     * @return string|null
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * Set dependentChild.
     *
     * @param int|null $dependentChild
     *
     * @return Employee
     */
    public function setDependentChild($dependentChild = null)
    {
        $this->dependentChild = $dependentChild;

        return $this;
    }

    /**
     * Get dependentChild.
     *
     * @return int|null
     */
    public function getDependentChild()
    {
        return $this->dependentChild;
    }

    /**
     * Set photoId.
     *
     * @param int|null $photoId
     *
     * @return Employee
     */
    public function setPhotoId($photoId = null)
    {
        $this->photoId = $photoId;

        return $this;
    }

    /**
     * Get photoId.
     *
     * @return int|null
     */
    public function getPhotoId()
    {
        return $this->photoId;
    }

    /**
     * Set cnssNumber.
     *
     * @param int|null $cnssNumber
     *
     * @return Employee
     */
    public function setCnssNumber($cnssNumber = null)
    {
        $this->cnssNumber = $cnssNumber;

        return $this;
    }

    /**
     * Get cnssNumber.
     *
     * @return int|null
     */
    public function getCnssNumber()
    {
        return $this->cnssNumber;
    }

    /**
     * Set phoneNumber.
     *
     * @param string|null $phoneNumber
     *
     * @return Employee
     */
    public function setPhoneNumber($phoneNumber = null)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber.
     *
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set address.
     *
     * @param string|null $address
     *
     * @return Employee
     */
    public function setAddress($address = null)
    {

        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set startDate.
     *
     * @param \DateTime|null $startDate
     *
     * @return Employee
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
     * Set currentPosition.
     *
     * @param string|null $currentPosition
     *
     * @return Employee
     */
    public function setCurrentPosition($currentPosition = null)
    {
        $this->currentPosition = $currentPosition;

        return $this;
    }

    /**
     * Get currentPosition.
     *
     * @return string|null
     */
    public function getCurrentPosition()
    {
        return $this->currentPosition;
    }


    /**
     * Add formation.
     *
     * @param \AppBundle\Entity\Formation $formation
     *
     * @return Employee
     */
    public function addFormation(\AppBundle\Entity\Formation $formation)
    {
        $formation->setEmployee($this);
        $this->formations[] = $formation;

        return $this;
    }

    /**
     * Remove formation.
     *
     * @param \AppBundle\Entity\Formation $fFormation
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeFormation(\AppBundle\Entity\Formation $formation)
    {
        return $this->formations->removeElement($formation);
    }

    /**
     * Get formations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormations()
    {
        return $this->formations;
    }


    /**
     * Set avatar.
     *
     * @param \AppBundle\Entity\Avatar|null $avatar
     *
     * @return Employee
     */
    public function setAvatar(\AppBundle\Entity\Avatar $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return \AppBundle\Entity\Avatar|null
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add project.
     *
     * @param \AppBundle\Entity\Project
     *
     * @return Employee
     */
    public function addProject(\AppBundle\Entity\Project $project)
    {
        $project->setEmployee($this);
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project.
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProject(\AppBundle\Entity\Project $project)
    {
        return $this->projects->removeElement($project);
    }

    /**
     * Get projects.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Set status.
     *
     * @param string|null $status
     *
     * @return Employee
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add notification.
     *
     * @param \AppBundle\Entity\EmployeeNotification $notification
     *
     * @return Employee
     */
    public function addNotification(\AppBundle\Entity\EmployeeNotification $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification.
     *
     * @param \AppBundle\Entity\EmployeeNotification $notification
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeNotification(\AppBundle\Entity\EmployeeNotification $notification)
    {
        return $this->notifications->removeElement($notification);
    }

    /**
     * Add cooptation.
     *
     * @param \AppBundle\Entity\Cooptation $cooptation
     *
     * @return Employee
     */
    public function addCooptation(\AppBundle\Entity\Cooptation $cooptation)
    {
        $cooptation->setEmployee($this);
        $this->cooptations[] = $cooptation;

        return $this;
    }

    /**
     * Remove cooptation.
     *
     * @param \AppBundle\Entity\Cooptation $cooptation
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCooptation(\AppBundle\Entity\Cooptation $cooptation)
    {
        return $this->cooptations->removeElement($cooptation);
    }

    /**
     * Get cooptations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCooptations()
    {
        return $this->cooptations;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $notifications
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * @return string
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * @param string $civility
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;
    }



}
