<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 5/17/18
 * Time: 8:15 AM
 */

namespace AppBundle\Manager;

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
        $concernedEmployees = $this->employeeManager->findByRoles(array(
            "ROLE_HR","ROLE_ADMIN"
        ));
        $subject = 'demande de cooptation';
        $from = 'mo.amine.jabri@gmail.com';
        $to = $this->employeeManager->getEmails($this->employeeManager->findByRoles(array(
            "ROLE_HR","ROLE_ADMIN"
        )));
        $template = Email\Templates::COOPTATION_REQUEST;
        $args = [
            'recommender' => $employee,
            'recommended' => $cooptation,
        ];
        $filesPaths = [$cooptation->getResumee()->getUrl()];

        $this->sendEmail($subject,$from,$to,$template,$args,$filesPaths);


        //FOR NOTIFICATION


        $this->nm->generateNotification(self::COOPTATION_REQUEST,
            array($employee->getFirstName()), 'url', $concernedEmployees);


        $this->em->persist($cooptation);
        $this->em->flush();
    }

    public function findAll()
    {
        return $this->em->getRepository(Cooptation::class)->findAll();
    }

    private function sendEmail($subject,
        $from,
        $to,
        $template,
        array $args,
        array $filesPaths
    )
    {
        $this->emailManager->send($subject, $from, $to, $template, $args, $filesPaths);
    }

}