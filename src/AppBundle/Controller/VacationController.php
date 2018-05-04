<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use AppBundle\Entity\Vacation;
use AppBundle\Form\VacationType;
use AppBundle\Manager\VacationManager;
use Symfony\Component\HttpFoundation\Response;

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
        $vm = $this->get('app.vacation.manager');

        if ($request->isMethod('POST')) {
            $errors = [];
            if ($form->handleRequest($request)->isValid()) {
                $errors = $vm->isDemandeValid($vacation);
                if (count($errors)==0) {
                    //echo count($lm->isDemandeValid($vacation));
                    $vacation->setEmployee($user);
                    $vm->persist($vacation);
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
     * @Route("/intranet/sold", name="sold")
     */
    public function showSoldAction(Request $request)
    {
        $employee = $this->get('security.token_storage')->getToken()->getUser();
        $vm = $this->get('app.vacation.manager');
        $sold = $vm->calculateSold($employee, true);
        return new Response($sold);
    }



    /**
     * @Route("/intranet/hrm/vacation-requests", name="approve")
     */
    public function approveListAction(Request $request)
    {


        $user = $this->get('security.token_storage')->getToken()->getUser();

        $vm = $this->get('app.vacation.manager');
        $listVacation = $vm->findAll();
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
        $vm = $this->get('app.vacation.manager');
        $listVacation = $vm->findAllByUserId($user->getId());
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
        $vm = $this->get('app.vacation.manager');
        $vacation = $vm->findOneById($vacationId);
        $vm->validation($vacation, $isValid, $refuseReason);
        $vm->flush();
        return $this->redirect('/intranet/hrm/vacation-requests');
    }
}
