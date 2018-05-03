<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use AppBundle\Entity\Vacation;
use AppBundle\Form\VacationType;
use AppBundle\Manager\VacationManager;

class VacationController extends Controller
{

    /**
     * @Route("/intranet/request-a-vacation", name="request_a_vacation")
     */
    public function requestAVacationAction(Request $request)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $vacation = new Vacation();
        $form = $this->get('form.factory')->create(VacationType::class, $vacation);
        $lm = $this->get('app.vacation.manager');

        if ($request->isMethod('POST')) {
            $errors = [];
            if ($form->handleRequest($request)->isValid()) {
                $errors = $lm->isDemandeValid($vacation);
                if (count($errors)==0) {
                    //echo count($lm->isDemandeValid($vacation));
                    $vacation->setEmployee($user);
                    $this->getDoctrine()->getManager()->persist($vacation);
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirect('/intranet/my-vacation-requests');
                }
                else{
                    foreach ($errors as $key => $value){
                        $form->get($key)->addError(new FormError($value));
                    }
                }
            }else{dump($form->getErrors());die;}
        }

        return $this->render('@App/vacation/request.html.twig', array(
            'form' => $form->createView(),
        ));

    }



    /**
     * @Route("/intranet/hrm/vacation-requests", name="approve")
     */
    public function approveListAction(Request $request)
    {


        $user = $this->get('security.token_storage')->getToken()->getUser();

        $lm = $this->get('app.vacation.manager');
        $listVacation = $lm->findAll();
        return $this->render('@App/vacation/requests.html.twig', array(
            'listVacation' => $listVacation,
        ));
    }


    /**
     * @Route("/intranet/my-vacation-requests", name="my_asked_vacations")
     */
    public function myListAction(Request $request)
    {


        $user = $this->get('security.token_storage')->getToken()->getUser();
        $lm = $this->get('app.vacation.manager');
        $listVacation = $lm->findAllByUserId($user->getId());
        return $this->render('@App/vacation/myrequests.html.twig', array(
            'listVacation' => $listVacation,
        ));

    }

    /**
     * @Route("/intranet/vacation-validation", name="validate")
     */
    public function validationAction(Request $request)
    {
        $vacationId = $request->get('id_');
        $isValid = $request->get('action');
        $refuseReason = $request->get('refuse_reason');
        $lm = $this->get('app.vacation.manager');
        $vacation = $lm->findOneById($vacationId);
        $lm->validation($vacation, $isValid, $refuseReason);
        $lm->flush();
        return $this->redirect('/intranet/hrm/vacation-requests');
    }
}
