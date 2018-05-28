<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User;

/**
 * Document
 *
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentRepository")
 */
class Document
{

    const DOC_NOT_READY = 'document non prêt';
    const DOC_READY = 'document  prêt';
    const CERTIFICATION_OF_SALARY = 'attestation de salaire';
    const ATTESTATION_OF_EMPLOYMENT   = 'attestation de travail';


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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="request_date", type="date")
     */
    private $requestDate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var Employee
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(name ="employee_id",referencedColumnName="id", nullable=false)
     */
    private $employee;


    /**
     * Document constructor.
     * @param \DateTime $requestDate
     * @param string $status
     */
    public function __construct()
    {
        $this->requestDate = new \DateTime();
        $this->status = self::DOC_NOT_READY;
    }


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
     * @return Document
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
     * Set requestDate.
     *
     * @param \DateTime $requestDate
     *
     * @return Document
     */
    public function setRequestDate($requestDate)
    {
        $this->requestDate = $requestDate;

        return $this;
    }

    /**
     * Get requestDate.
     *
     * @return \DateTime
     */
    public function getRequestDate()
    {
        return $this->requestDate;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return Document
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * Set employee.
     *
     * @param \AppBundle\Entity\Employee $employee
     *
     * @return Document
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
