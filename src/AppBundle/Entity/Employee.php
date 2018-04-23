<?php
// src/AppBundle/Entity/Employee.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Employee
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeeRepository")
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
     * @ORM\Column(name="first_name" , type="string", nullable=true)
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name" , type="string", nullable=true)
     */
    private $lastName;

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
     * @var int
     * @ORM\Column(name="cnss" , type="integer", nullable=true)
     */
    private $cnssNumber;

    /**
     * @var int
     * @ORM\Column(name="phone_number" , type="integer", nullable=true)
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
     * @var int
     * @ORM\Column(name="status" , type="integer", nullable=true)
     */
    private $status;


    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="EmployeeFormation", mappedBy="employee", cascade={"persist"})
     */
    private $employeeFormations;

    /**
     * @var EmployeeLanguage
     * @ORM\OneToMany(targetEntity="EmployeeLanguage", mappedBy="employee", cascade={"persist","remove"})
     */
    private $employeeLanguages;


    /**
     * @var Experience
     * @ORM\OneToMany(targetEntity="Experience", mappedBy="employee",cascade={"persist","remove"})
     *
     */
    private $experiences;

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
        // your own logic
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
     * @param int|null $phoneNumber
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
     * @return int|null
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
     * Set status.
     *
     * @param int|null $status
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
     * @return int|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add employeeFormation.
     *
     * @param \AppBundle\Entity\EmployeeFormation $employeeFormation
     *
     * @return Employee
     */
    public function addEmployeeFormation(\AppBundle\Entity\EmployeeFormation $employeeFormation)
    {
        $employeeFormation->setEmployee($this);
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
     * Add project.
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return Employee
     */
    public function addProject(\AppBundle\Entity\Project $project)
    {
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
     * Add language.
     *
     * @param \AppBundle\Entity\Language $language
     *
     * @return Employee
     */
    public function addLanguage(\AppBundle\Entity\Language $language)
    {
        $this->languages[] = $language;

        return $this;
    }

    /**
     * Remove language.
     *
     * @param \AppBundle\Entity\Language $language
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeLanguage(\AppBundle\Entity\Language $language)
    {
        return $this->languages->removeElement($language);
    }

    /**
     * Get languages.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Get employeeFormations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmployeeFormations()
    {
        return $this->employeeFormations;
    }

    /**
     * Set avatar.
     *
     * @param \AppBundle\Entity\Avatar $avatar
     *
     * @return Employee
     */
    public function setAvatar(\AppBundle\Entity\Avatar $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return \AppBundle\Entity\Avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
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
}
