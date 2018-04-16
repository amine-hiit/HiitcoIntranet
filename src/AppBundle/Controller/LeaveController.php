<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use AppBundle\Entity\Leave;
use AppBundle\Form\LeaveType;
use AppBundle\Manager\LeaveManager;

class LeaveController extends Controller
{

    /**
     * @Route("/intranet/request-a-leave", name="request_a_leave")
     */
    public function requestALeaveAction(Request $request)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $leave = new Leave();
        $form = $this->get('form.factory')->create(LeaveType::class, $leave);
        $lm = $this->get('app.leave.manager');

         if ($request->isMethod('POST')) {
                $errors = [];
             if ($form->handleRequest($request)->isValid()) {
                $errors = $lm->isDemandeValid($leave);
                if (count($errors)==0) {
                    //echo count($lm->isDemandeValid($leave));
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
             }else{dump($form->getErrors());die;}
           } 
         
        return $this->render('@App/leave/request.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/intranet/ask-for-leave", name="ask_for_leave")
     */
    public function askForLeaveAction(Request $request)
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
         
        return $this->render('AppBundle:leave:askforleave.html.twig', array(
            'form' => $form->createView(),
        ));

    }


    /**
     * @Route("/intranet/asked-leave-list", name="asked_leaves")
     */
    public function askedListAction(Request $request)
    {


        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $lm = $this->get('app.leave.manager');
                $listLeave = $lm->findAll();
                return $this->render('@App/Leave/listofaskedleaves.html.twig', array(
                    'listLeave' => $listLeave,
                ));
    }


    /**
     * @Route("/intranet/hrm/leave-requests", name="approve")
     */
    public function approveListAction(Request $request)
    {


        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $lm = $this->get('app.leave.manager');
                $listLeave = $lm->findAll();
                return $this->render('@App/leave/requests.html.twig', array(
                    'listLeave' => $listLeave,
                ));
    }


    /**
     * @Route("/intranet/my-leave-requests", name="my_asked_leaves")
     */
    public function myListAction(Request $request)
    {


        $user = $this->get('security.token_storage')->getToken()->getUser();
        $lm = $this->get('app.leave.manager');
        $listLeave = $lm->findAllByUserId($user->getId());
        return $this->render('@App/leave/myrequests.html.twig', array(
            'listLeave' => $listLeave,
        ));

    }

    /**
     * @Route("/intranet/leave-validation", name="validate")
     */
        public function validationAction(Request $request)
    {

        $leaveId = $request->get('id_');
        $isValid = $request->get('action');
        $refuseReason = $request->get('refuse_reason');

        
        $lm = $this->get('app.leave.manager');
        
        $leave = $lm->findOneById($leaveId);

        $lm->validation($leave, $isValid, $refuseReason);
        $lm->flush();
        return $this->redirect('/intranet/approve');
    }

     /**
     * @Route("/intranet/home", name="home")
     */
    public function homeAction(Request $request)
    {
        return $this->render('@App/home/home.html.twig');    
    }
     /**
     * @Route("/intranet/jsonListAction", name="json_list")
     */
    public function jsonListAction(Request $request)
    {
        return $this->render('@App/home/home.html.twig');    
    }

    /**
     * @Route("/intranet/home/leave", name="home-leave")
     */
    public function leavePageAction(Request $request)
    {
        return $this->render('@App/home/leavehome.html.twig');    
    }


}
