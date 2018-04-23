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
use AppBundle\Entity\Employee;
use AppBundle\Entity\EmployeeFormation;
use AppBundle\Entity\Experience;
use AppBundle\Form\EmployeeType;
use AppBundle\Form\EmployeeFormationType;
use AppBundle\Form\ExperienceType;

class EmployeeController extends Controller
{
    /**
     * @Route("/intranet/fiche", name="intranet-fiche")
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
     * @Route("/intranet/fichetest", name="intranet-fiche-test")
     */
    public function ficheTestAction(Request $request)
    {

        $employee = new Employee();
        $form = $this->get('form.factory')->create(EmployeeType::class, $employee);

        if ($request->isMethod('POST'))
        {
            if ($form->handleRequest($request)->isValid())
            {
                //$em = $this->get('entity.manager')->
            }
        }
        return $this->render('@App/test/collection.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/intranet/employee/{employee}", name="intranet-employee")
     */
    public function emplyeeProfileAction(Request $request, Employee $employee)
    {


        $lastFormations = $this->getDoctrine()->getManager()->getRepository(EmployeeFormation::class)->findLastFormationByEmployeeId($employee);
        $formations = $this->getDoctrine()->getManager()->getRepository(EmployeeFormation::class)->findAllByEmployeeId($employee->getId());

        $employeeFormation = new EmployeeFormation();
        $experience = new Experience();

        $formationForm = $this->get('form.factory')->create(EmployeeFormationType::class, $employeeFormation);
        $experienceForm = $this->get('form.factory')->create(ExperienceType::class, $experience);

        $user = $this->get('security.token_storage')->getToken()->getUser();


        //$fm = $this->get('app.form.manager');
        //$fm->handForm();

        /* to be deleted from controller and used in manager */
        if ($request->isMethod('POST'))
        {
            if ($formationForm->handleRequest($request)->isValid())
            {
                dump($formationForm);
                dump($experienceForm);die;

                return new Response('done');
            }
            else if ($experienceForm->handleRequest($request)->isValid())
            {
                dump($formationForm);
                dump($experienceForm);die;

                return new Response('done');
            }
        }




        return  $this->render('@App/profil/employee.html.twig', array(
            'formationForm' => $formationForm->createView(),
            'experienceForm' => $experienceForm->createView(),
            'employee' => $employee,
            'lastFormations' => $lastFormations,
            'formations' => $formations
        ));
    }

}