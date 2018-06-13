<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 5/17/18
 * Time: 8:15 AM
 */

namespace AppBundle\Manager;

use AppBundle\Entity\EmailType;
use AppBundle\Entity\Employee;
use AppBundle\Resources\Email;
use AppBundle\Entity\Cooptation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CooptationManager
{

    const COOPTATION_REQUEST = 'cooptation_request';
    /**
     * @var EntityManagerInterface
     */

    private $em;

    /**
     * @var TokenStorageInterface
     */
    private $token;

    /**
     * @var EmailManager
     */
    private $emailManager;

    /**
     * @var EmployeeManager
     */
    private $employeeManager;

    /**
     * @var NotificationManager
     */
    private $nm;

    /**
     * CooptationManager constructor.
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $token
     * @param EmailManager $emailManager
     * @param EmployeeManager $employeeManager
     * @param NotificationManager $nm
     */
    public function __construct(
        EntityManagerInterface $em,
        TokenStorageInterface $token,
        EmailManager $emailManager,
        EmployeeManager $employeeManager,
        NotificationManager $nm
    ) {
        $this->em = $em;
        $this->token = $token;
        $this->emailManager = $emailManager;
        $this->employeeManager = $employeeManager;
        $this->nm = $nm;
    }


    public function uploadCooptation(Cooptation $cooptation)
    {
        $employee = $this->token->getToken()->getUser();
        $cooptation->setEmployee($employee);
        $cooptation->getResumee()->upload();

        //FOR SENDIG EMAIL

        $emailType = $this->em->getRepository(EmailType::class)->findBy(['label' =>
        Email\Subject::COOPTATION_REQUEST])[0];
        $args = [
            'recommender' => $employee,
            'recommended' => $cooptation,
        ];
        $filesPaths = [
            $cooptation->getResumee()->getUrl()
        ];

        $this->sendEmail(
            $emailType,
            null,
            $args,
            $filesPaths
        );

        //FOR NOTIFICATION

        $concernedEmployees = $this->employeeManager->findEmployeesByRoles($emailType->getRoles());
        $this->nm->generateNotification(self::COOPTATION_REQUEST,
            array($employee->getFirstName()), '#', $concernedEmployees);


        $this->em->persist($cooptation);
        $this->em->flush();
    }

    public function findAll()
    {
        return $this->em->getRepository(Cooptation::class)->findAll();
    }

    private function sendEmail(
        EmailType $emailType = null,
        $to = null,
        array $args = null,
        array $filesPaths = null

    )
    {
        $this->emailManager->send(
            $emailType,
            $to,
            $args,
            $filesPaths
        );
    }

}