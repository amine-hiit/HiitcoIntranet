<?php


namespace AppBundle\Manager;


use AppBundle\Entity\EmailType;
use AppBundle\Entity\Notification;
use AppBundle\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Formation;
use AppBundle\Entity\Experience;
use AppBundle\Entity\Employee;
use AppBundle\Resources\Email;
use FOS\UserBundle\model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\RouterInterface;

class EmployeeManager
{


    /**
     *
     */
    private $translator;

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
     * @var TokenStorageInterface
     */
    private $tokenStorage;


    /**
     * EmployeeManager constructor.
     * @param EntityManagerInterface $em
     * @param NotificationManager $nm
     * @param UserManagerInterface $um
     * @param EmailManager $emailManager
     * @param TokenStorageInterface $tokenStorage
     * @param RouterInterface $router
     * @param TokenGeneratorInterface $tg
     */
    public function __construct(EntityManagerInterface $em,
                                NotificationManager $nm,
                                UserManagerInterface $um,
                                EmailManager $emailManager,
                                RouterInterface $router,
                                TokenGeneratorInterface $tg,
                                TokenStorageInterface $tokenStorage

    )
    {
        $this->emailManager = $emailManager;
        $this->router = $router;
        $this->em = $em;
        $this->nm = $nm;
        $this->um = $um;
        $this->tg = $tg;
        $this->tokenStorage = $tokenStorage;
    }

    public function findByRole($role)
    {
        return $this->em->getRepository(Employee::class)->findByRole($role);
    }

    public function findEmployeeLastFormation(Employee $employee)
    {
        return $this->em->getRepository(Formation::class)->findEmployeeLastFormation($employee);
    }

    public function findEmployeesByRoles(array $roles = null){
        if (null === $roles)
            return [];
        $employees = [];
        foreach ($roles as $role){
            $results = $this->em->getRepository(Employee::class)->findByRole($role);
            foreach ($results as $result) {
                $employees[]=$result;
            }
        }
        return $employees;
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

    public function findEmployeeAllProjects(Employee $employee)
    {
        return $this->em->getRepository(Project::class)->findEmployeeAllProjects($employee);
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
        return $this->em->getRepository(Formation::class)->findEmployeeAllFormations($employee);
    }

    public function createEmployeeFormation()
    {
        return new Formation();
    }

    public function setUserToAttribute($attribute,Employee $user)
    {
        $attribute->setEmployee($user);
    }

    public function update($attribute)
    {
        $this->persist($attribute);
        $this->flush();
    }


    public function persist($attribute)
    {
        $this->em->persist($attribute);
    }

    public function persistAttribute($attribute)
    {
        $this->em->persist($attribute);
    }

    public function flush()
    {
        $this->em->flush();
    }

    public function getEmails(array $employees)
    {
        $emails = [];
        foreach ($employees as $employee)
        {
            array_push($emails,$employee->getEmail());
        }
        return $emails;
    }

    public function findByRoles(array $roles)
    {
        return $this->em->getRepository(Employee::class)->findByRoles($roles);
    }

    public function completeEmployeeForm(Employee $employee)
    {
        if (null !== $employee->getAvatar()) {
            $employee->getAvatar()->upload();
            $employee->getAvatar()->setAlt($employee->getUserName().'_Avatar');
        }

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
        $employeeFormEmail = $this->em->getRepository(EmailType::class)
            ->findBy(['label' => Email\Subject::FILL_EMPLOYEE_FORM])[0];
        $setPWEmail = $this->em->getRepository(EmailType::class)
            ->findBy(['label' => Email\Subject::SET_NEW_PASSWORD])[0];

//        dump($this->router->generate('new-emplyee-password',['token' => $token],UrlGeneratorInterface::ABSOLUTE_URL));die;
        $setNewPasswordUrl = $this->router->generate('new-emplyee-password',['token' => $token],UrlGeneratorInterface::ABSOLUTE_URL);// http://localhost:8002/new-password/'.$token;
        $employeeFormUrl = 'http://localhost:8002'
            .$this->router->generate('employee-form');

        $this->emailManager->send(
            $setPWEmail,
            $employee->getEmail(),
            [
                'url' => $setNewPasswordUrl,
                'userName' => $employee->getUsername(),
                'action' => 'Clique ici'
            ]
        );

        $this->emailManager->send(
            $employeeFormEmail,
            $employee->getEmail(),            [
                'url' => $employeeFormUrl,
                'action' => 'Clique ici'
            ]
        );
    }
}