<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 4/17/18
 * Time: 11:13 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\EmployeeLanguage;
use AppBundle\Entity\Language;
use AppBundle\Entity\Project;
use AppBundle\Entity\Setting;
use AppBundle\Form\EmployeeLanguageType;
use AppBundle\Form\EmployeeRegistrationType;
use AppBundle\Form\ProjectType;
use AppBundle\Form\SettingType;
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

use AppBundle\Form\EmployeeType;
use AppBundle\Form\FormationType;
use AppBundle\Form\ExperienceType;
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
            dump($form);die;
            $employee->addRole("ROLE_EMPLOYEE");
            $employeeManager->registerNewEmployee($employee);
            $this->addFlash('success',$this->trans('flash.employee.registred'));

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

        $employeeManager = $this->get('app.employee.manager');


        if(!$employee->isValid())
            return  $this->render('@App/profil/invalid_employee.html.twig', array(
                'employee' => $employee,
            ));

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $languages = $this->getDoctrine()->getRepository(EmployeeLanguage::class)->findAll();
        $lastFormations = $employeeManager->findEmployeeLastFormation($employee);
        $formations = $employeeManager->findEmployeeAllFormations($employee);
        $experiences = $employeeManager->findEmployeeAllExperiences($employee);
        $projects = $employeeManager->findEmployeeAllProjects($employee);



        $employeeFormation =  $employeeManager->createEmployeeFormation();
        $experience = new Experience();
        $language = new EmployeeLanguage();
        $project = new Project();

        $employeeFormationForm = $this->get('form.factory')->create(FormationType::class, $employeeFormation);
        $experienceForm = $this->get('form.factory')->create(ExperienceType::class, $experience);
        $languageForm = $this->get('form.factory')->create(EmployeeLanguageType::class, $language);
        $projectForm = $this->get('form.factory')->create(ProjectType::class, $project);

        /* to be deleted from controller and used in manager */
        if ($request->isMethod('POST'))
        {
            if ($employeeFormationForm->handleRequest($request)->isValid())
            {
                dump('ici');die;
                $employeeManager->setUserToAttribute($employeeFormation, $employee );
                $employeeManager->persistAttribute($employeeFormation);
                $employeeManager->updateEmployee($employee);

                return $this->redirect('/intranet/employee/'.$employee->getId());
            }

            else if ($languageForm->handleRequest($request)->isValid())
            {
                $employeeManager->setUserToAttribute($language, $employee );
                $employeeManager->persistAttribute($language);
                $employeeManager->updateEmployee($employee);

                return $this->redirect('/intranet/employee/'.$employee->getId());
            }

            else if ($experienceForm->handleRequest($request)->isValid())
            {
                $employeeManager->setUserToAttribute($experience, $employee );
                $employeeManager->persistAttribute($experience);
                $employeeManager->updateEmployee($employee);

                return $this->redirect('/intranet/employee/'.$employee->getId());
            }

            else if ($projectForm->handleRequest($request)->isValid())
            {
                $employeeManager->setUserToAttribute($project, $employee );
                $employeeManager->persistAttribute($project);
                $employeeManager->updateEmployee($employee);

                return $this->redirect('/intranet/employee/'.$employee->getId());
            }
        }

        return  $this->render('@App/profil/employee.html.twig', array(
            'formationForm' =>$employeeFormationForm->createView(),
            'projectForm' =>$projectForm->createView(),
            'languageForm' =>$languageForm->createView(),
            'experienceForm' => $experienceForm->createView(),
            'employee' => $employee,
            'profileOwner' => ($employee->getId()===$user->getId()),
            'lastFormations' => $lastFormations,
            'formations' => $formations,
            'projects' => $projects,
            'languages' => $languages,
            'experiences' => $experiences
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
     * @Route("/intranet/update/{item}/{id}", name="update-profile-item", requirements={
     *     "item"="project|formation|experience|language|employee|employeeLanguage"
     * })
     */
    public function updateItem($item, $id = 0, Request $request)
    {

        $class = '\\AppBundle\\Entity\\'.ucfirst($item);
        $type = '\\AppBundle\\Form\\'.ucfirst($item).'Type';

        $data = $this->getDoctrine()->getRepository($class)->find($id);
        $form = $this->createForm($type, $data);
        if($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()){
                $this->get('app.employee.manager')->update($data);
            }
            return $this->redirectToRoute('employee-profil',['employee' => $this->getUser()->getId()]);
        }
        return $this->render('@App/profil/form/'.$item.'.html.twig',['form' => $form->createView()]);
    }





    private function trans($id){
        return $this->get('translator')->trans($id);
    }

}


