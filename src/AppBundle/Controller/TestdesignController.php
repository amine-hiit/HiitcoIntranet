<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Leave;
use AppBundle\Form\LeaveType;

class TestdesignController extends Controller
{
    
    

    /**
     * @Route("/navbar", name="navbar")
     */
    public function navAction(Request $request)
    {
        return $this->render('@App/general/navbar2.html.twig');    

    }

    /**
     * @Route("/intranet/stepform", name="step-form")
     */
    public function stepFormAction(Request $request)
    {
        return $this->render('@App/profil/employee_form.html.twig');

    }


    /**
     * @Route("/layout", name="layout")
     */
    public function layoutAction(Request $request)
    {
        return $this->render('@App/leave/request.html.twig');    

    }


    /**
     * @Route("/sidebar", name="sidebar")
     */
    public function sideAction(Request $request)
    {
        return $this->render('@App/general/sidebar2.html.twig');    

    }

    /**
     * @Route("/tmplt", name="tmplt")
     */
    public function tmpltAction(Request $request)
    {
        return $this->render('@App/index.html.twig');    

    }

    /**
     * @Route("/blank", name="blank")
     */
    public function blankAction(Request $request)
    {
        return $this->render('@App/blank.html.twig');    

    }

    /**
     * @Route("/conge", name="conge")
     */
    public function congeAction(Request $request)
    {
        return $this->render('@App/conge.html.twig');    

    }

    /**
     * @Route("/intranet/form", name="form")
     */
    public function formAction(Request $request)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $leave = new Leave();
        $form = $this->get('form.factory')->create(LeaveType::class, $leave);
        $lm = $this->get('app.leave.manager');

         if ($request->isMethod('POST')) {
                $errors = $lm->isDemandeValid($leave);
             if ($form->handleRequest($request)->isValid()) {
                $errors = $lm->isDemandeValid($leave);
                //dump($errors);exit;
                if (count($errors)==0) {
                    echo count($lm->isDemandeValid($leave));
                     $leave->setEmployee($user);
                     $this->getDoctrine()->getManager()->persist($leave);
                     $this->getDoctrine()->getManager()->flush();
                     return $this->redirect('/intranet/my-asked-leave-list');
                }
                else{
                    foreach ($errors as $key => $value){
                        $form->get($key)->addError(new FormError($value));
                    }
                }
             }
           } 
         
        return $this->render('AppBundle:test:form.html.twig', array(
            'form' => $form->createView(),
        ));

    }


}
