<?php


namespace AppBundle\Manager;


use AppBundle\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\EmployeeFormation;
use AppBundle\Entity\Experience;
use AppBundle\Entity\Employee;
use AppBundle\Resources\Email;
use FOS\UserBundle\model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class EmployeeManager
{

    /**
     * @var TokenGeneratorInterface
     */
    private $tg;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var NotificationManager
     */
    private $nm;

    /**
     * @var UserManagerInterface
     */
    private $um;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EmailManager
     */
    private $emailManager;


    /**
     * EmployeeManager constructor.
     * @param EntityManagerInterface $em
     * @param NotificationManager $nm
     * @param UserManagerInterface $um
     * @param EmailManager $emailManager
     * @param RouterInterface $router
     * @param TokenGeneratorInterface $tg
     */
    public function __construct(EntityManagerInterface $em,
    NotificationManager $nm,
    UserManagerInterface $um,
    EmailManager $emailManager,
    RouterInterface $router,
    TokenGeneratorInterface $tg

    )
    {
        $this->emailManager = $emailManager;
        $this->router = $router;
        $this->em = $em;
        $this->nm = $nm;
        $this->um = $um;
        $this->tg = $tg;
    }

    public function findByRole($role)
    {
        return $this->em->getRepository(Employee::class)->findByRole($role);
    }

    public function findEmployeeLastFormation(Employee $employee)
    {
        return $this->em->getRepository(EmployeeFormation::class)->findEmployeeLastFormation($employee);
    }

    public function findEmployeeLastExperience(Employee $employee)
    {
        return $this->em->getRepository(Experience::class)->findEmployeeLastFormation($employee);
    }

    public function findAll()
    {
        return $this->em->getRepository(Employee::class)->findAll();
    }

    public function findEmployeeByToken($token)
    {
        return $this->em->getRepository(Employee::class)->findEmployeeByToken($token);
    }

    public function findEmployeeAllExperiences(Employee $employee)
    {
        return $this->em->getRepository(Experience::class)->findEmployeeAllExperiences($employee);
    }

    public function createEmployee()
    {
         return $this->um->createUser();
    }

    public function registerNewEmployee(Employee $employee)
    {
        $employee->setEnabled(true);
        $employee->setConfirmationToken($this->tg->generateToken());
        $this->updateEmployee($employee);
        $this->sendNewEmployeeEmails($employee);
    }


    public function findEmployeeAllFormations(Employee $employee)
    {
        return $this->em->getRepository(EmployeeFormation::class)->findEmployeeAllFormations($employee);
    }

    public function createEmployeeFormation()
    {
        return new EmployeeFormation();
    }

    public function setUserToAttribute($attribute,Employee $user)
    {
        $attribute->setEmployee($user);
    }

    public function persistAttribute($attribute)
    {
        $this->em->persist($attribute);
    }

    public function flush()
    {
        $this->em->flush();
    }


    public function completeEmployeeForm(Employee $employee)
    {
        $employee->getAvatar()->upload();
        $employee->getAvatar()->setAlt($employee->getUserName().'_Avatar');

        $employee->setValid(true);
        $this->updateEmployee($employee);
    }

    public function updateEmployee($employee)
    {
        $this->um->updateUser($employee);
    }



    /******* Emails Section *************/

    public function sendNewEmployeeEmails(Employee $employee)
    {
        $token = $employee->getConfirmationToken();
        $from = 'mo.amine.jabri@gmail.com';
        $employeeEmail = $employee->getEmail();
        $setNewPasswordUrl = 'http://localhost:8002/new-password/'.$token;
        $employeeFormUrl = 'http://localhost:8002'
            .$this->router->generate('employee-form');



        $this->emailManager->send(
            Email\Subject::SET_NEW_PASSWORD,
            $from,
            $employeeEmail,
            Email\Templates::SET_NEW_PASSWORD,
            [
                'url' => $setNewPasswordUrl,
                'userName' => $employee->getUsername(),
                'action' => 'Clique ici'

            ]
        );



        $this->emailManager->send(
            Email\Subject::FILL_EMPLOYEE_FORM,
            $from,
            $employeeEmail,
            Email\Templates::FILL_EMPLOYEE_FORM,
            [
                'url' => $employeeFormUrl,
                'action' => 'Clique ici'
            ]
        );
    }
}