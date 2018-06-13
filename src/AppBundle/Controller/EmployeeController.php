<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 4/17/18
 * Time: 11:13 AM
 */

namespace AppBundle\Controller;


use AppBundle\Form\EmployeeRegistrationType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use FOS\UserBundle\Form\Type\ChangePasswordFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Form\Type\RegistrationFormType;
use FOS\UserBundle\Form\Type\ResettingFormType;

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
        $employeeManager = $this->get('app.employee.manager');

        $employee = $employeeManager->createEmployee();
        $form = $this->createForm(EmployeeRegistrationType::class, $employee);
        $form->handleRequest($request);

        if( $form->isSubmitted() ){

            if($form->isValid()){
                $employee->addRole("ROLE_EMPLOYEE");
                $employeeManager->registerNewEmployee($employee);
                $this->addFlash('success',$this->trans('flash.employee.registred'));
            }

            return $this->redirect($this->generateUrl('employees-list'));
        }
        return $this->render('@App/profil/register_new_employee.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/intranet/hrm/employees", name="employees-list")
     */
    public function listAction(Request $request)
    {

        $employeeManager = $this->get('app.employee.manager');
        $employees = $employeeManager->findAll();


        return $this->render('@App/profil/employees_list.html.twig', array(
            'employees' => $employees,
        ));
    }

    /**
     * @Route("/intranet/new-password", name="new-password")
         * @Route("/new-password/{token}", name="new-emplyee-password")
     */
    public function newPasswordAction(Request $request, $token)
    {
        $employeeManager = $this->get('app.employee.manager');
        $employee = $this->getUser();
        if( null === $employee)
            $employee = $employeeManager->findEmployeeByToken($token);

        $newPasswordForm = $this->createForm(ResettingFormType::class, $employee);

        if($request->isMethod('POST'))
        {
            $newPasswordForm->handleRequest($request);
            $employee->setConfirmationToken(null);
            $employeeManager->updateEmployee($employee);
            return $this->redirectToRoute('homepage');
        }

        return $this->render('@App/profil/set_password.html.twig',
            [
                'form' => $newPasswordForm->createView(),
            ]);
    }

    /**
     * @Route("/intranet/change-password", name="change-password")
     */
    public function changePasswordAction(Request $request)
    {

        $employeeManager = $this->get('app.employee.manager');
        $employee = $this->getUser();


        $newPasswordForm = $this->createForm(ChangePasswordFormType::class, $employee);

        if($request->isMethod('POST'))
        {
            $newPasswordForm->handleRequest($request);
            $employeeManager->updateEmployee($employee);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('@App/profil/change_password.html.twig',
            [
                'form' => $newPasswordForm->createView(),
            ]);
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



        $employeeFormation =  $employeemanager->createEmployeeFormation();
        $experience = new Experience();

        $employeeFormationForm = $this->get('form.factory')->create(EmployeeFormationType::class, $employeeFormation);
        $experienceForm = $this->get('form.factory')->create(ExperienceType::class, $experience);

        /* to be deleted from controller and used in manager */
        if ($request->isMethod('POST'))
        {
            if ($employeeFormationForm->handleRequest($request)->isValid())
            {
                $employeemanager->setUserToAttribute($employeeFormation, $employee );
                $employeemanager->persistAttribute($employeeFormation);
                $employeemanager->updateEmployee($employee);

                return $this->redirect('/intranet/employee/'.$employee->getId());
            }
            else if ($experienceForm->handleRequest($request)->isValid())
            {
                $employeemanager->setUserToAttribute($experience, $employee );
                $employeemanager->persistAttribute($experience);
                $employeemanager->updateEmployee($employee);

                return $this->redirect('/intranet/employee/'.$employee->getId());
            }
        }

        return  $this->render('@App/profil/employee.html.twig', array(
            'formationForm' =>$employeeFormationForm->createView(),
            'experienceForm' => $experienceForm->createView(),
            'employee' => $employee,
            'profileOwner' => ($employee->getId()===$user->getId()),
            'lastFormations' => $lastFormations,
            'formations' => $formations,
            'experiences' => $experiences
        ));
    }

    private function trans($id){
        return $this->get('translator')->trans($id);
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

}

