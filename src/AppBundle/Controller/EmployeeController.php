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
use AppBundle\Entity\Employee;
use AppBundle\Form\EmployeeType;

class EmployeeController extends Controller
{
    /**
     * @Route("/intranet/fiche", name="intranet-fiche")
     */
    public function ficheAction(Request $request)
    {

            $employee = new Employee();
            $form = $this->get('form.factory')->create(EmployeeType::class, $employee);

            if ($request->isMethod('POST'))
            {
                if ($form->handleRequest($request)->isValid())
                {
                        //return $this->redirect('/intranet/my-leave-requests');
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
}