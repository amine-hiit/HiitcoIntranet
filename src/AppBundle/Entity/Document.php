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

    const DOC_NOT_READY = 'doc.not.ready';
    const DOC_VALIDATED = 'doc.validated';
    const DOC_READY = 'doc.ready';
    const CERTIFICATION_OF_SALARY = 'attestation de salaire';
    const ATTESTATION_OF_EMPLOYMENT   = 'attestation de travail';
    const CERTIFICATION_OF_SALARY_VIEW = 'some view';
    const ATTESTATION_OF_EMPLOYMENT_VIEW   = '@App/docs/templates/certification-of-salary.html.twig';


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
     * @ORM\Column(name="status", type="string", length=256)
     */
    private $status;

    /**
     * @var Employee
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(name ="employee_id",referencedColumnName="id", nullable=false)
     */
    private $employee;

    /**
     * @var Pdf
     * @ORM\ManyToOne(targetEntity="Pdf", cascade={"persist"})
     * @ORM\JoinColumn(name ="pdf_id",referencedColumnName="id", nullable=true)
     */
    private $pdf;


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

    /**
     * Set pdf.
     *
     * @param \AppBundle\Entity\Pdf|null $pdf
     *
     * @return Document
     */
    public function setPdf(\AppBundle\Entity\Pdf $pdf = null)
    {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * Get pdf.
     *
     * @return \AppBundle\Entity\Pdf|null
     */
    public function getPdf()
    {
        return $this->pdf;
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
}
