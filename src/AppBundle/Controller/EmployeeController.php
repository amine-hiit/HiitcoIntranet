<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 4/17/18
 * Time: 11:13 AM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

use FOS\UserBundle\Form\Type\RegistrationFormType;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Formation;
use AppBundle\Entity\Experience;
use AppBundle\Entity\EmployeeFormation;

use AppBundle\Form\EmployeeType;
use AppBundle\Form\FormationType;
use AppBundle\Form\ExperienceType;
use AppBundle\Form\EmployeeFormationType;

class EmployeeController extends Controller
{

    /**
     * @Route("/intranet/admin/register-new-employee", name="employee-registration")
     */
    public function registerEmployeeAction(Request $request)
    {
        $employee = new Employee();
        $registrationForm = $this->createForm(RegistrationFormType::class, $employee);
        $registrationForm->handleRequest($request);
        if($registrationForm->isSubmitted()){
            if($registrationForm->isValid()){
                $userManager = $this->get('fos_user.user_manager');
                $exists = $userManager->findUserBy(array('email' => $employee->getEmail()));
                if ($exists instanceof Employee) {
                    throw new HttpException(409, 'Email already taken');
                }
                $employee->setEnabled(true);
                $userManager->updateUser($employee);
            }
        }
        return $this->render('@App/profil/register_new_employee.html.twig', array(
            'form' => $registrationForm->createView(),
        ));
    }

    /**
     * @Route("/intranet/form", name="employee-form")
     */
    public function ficheAction(Request $request)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->get('form.factory')->create(EmployeeType::class, $user);
        $employeeManager = $this->get('app.employee.manager');

        if ($request->isMethod('POST'))
        {
            if ($form->handleRequest($request)->isValid())
            {
                $employeeManager->completeEmployeeForm($user);
                return $this->redirect('/intranet/employee/'.$user->getId());

            }
        }
        return $this->render('@App/profil/employee_form_.html.twig', array(
            'form' => $form->createView(),
        ));
    }



    /**
     * @Route("/intranet/employee/{employee}", name="employee-profil")
     */
    public function emplyeeProfileAction(Request $request, Employee $employee)
    {

        $employeemanager = $this->get('app.employee.manager');


        if(!$employee->isValid())
            return  $this->render('@App/profil/invalid_employee.html.twig', array(
                'employee' => $employee,
            ));

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $lastFormations = $employeemanager->findEmployeeLastFormation($employee);
        $formations = $employeemanager->findEmployeeAllFormations($employee);
        $experiences = $employeemanager->findEmployeeAllExperiences($employee);


        $employeeFormation =  $employeemanager->create();
        $experience = new Experience();


        $employeeFormationForm = $this->get('form.factory')->create(EmployeeFormationType::class, $employeeFormation);
        //$formationForm = $this->get('form.factory')->create(FormationType::class, $employeeFormation);
        $experienceForm = $this->get('form.factory')->create(ExperienceType::class, $experience);

        /* to be deleted from controller and used in manager */
        if ($request->isMethod('POST'))
        {
            if ($employeeFormationForm->handleRequest($request)->isValid())
            {
                $employeemanager->setUserToAtribut($employeeFormation, $user );
                $employeemanager->persistAtribut($employeeFormation);
                $employeemanager->flush();

                return $this->redirect('/intranet/employee/'.$user->getId());
            }
            else if ($experienceForm->handleRequest($request)->isValid())
            {
                $employeemanager->setUserToAtribut($experience, $user );
                $employeemanager->persistAtribut($experience);
                $employeemanager->flush();

                return $this->redirect('/intranet/employee/'.$user->getId());
            }
        }




        return  $this->render('@App/profil/employee.html.twig', array(
            'formationForm' =>$employeeFormationForm->createView(),
            'experienceForm' => $experienceForm->createView(),
            'employee' => $employee,
            'profileOwner' => ($employee->getId()==$user->getId()),
            'lastFormations' => $lastFormations,
            'formations' => $formations,
            'experiences' => $experiences
        ));
    }

}