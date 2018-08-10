<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ReligiousPaidVacation;
use AppBundle\Form\ReligiousPaidVacationType;
use AppBundle\Repository\ReligiousPaidVacationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function requestVacationAction(Request $request)
    {

        $vacation = new Vacation();
        $form = $this->get('form.factory')->create(VacationType::class, $vacation);
        $vm = $this->get('app.vacation.manager');

        if ($request->isMethod('POST' ) && $form->handleRequest($request)) {
            if ($form->isValid()) {
                $vacation->setEmployee($this->getUser());
                $vm->request($vacation);
                return $this->redirect($this->get('router')
                    ->generate('my_vacations_requests'));
            }
        }



        return $this->render('@App/vacation/request2.html.twig', array(
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

        $years = $vm->calculateMonthlyVacationBalance($employee);

        $soldAfterAppovingRequestes = $vm->calculateVacationBalance($employee, true);
        $soldBeforeAppovingRequestes = $vm->calculateVacationBalance($employee, false);
        return $this->render('@App/vacation/vacation-balance.html.twig',
            [
                'years' => array_reverse($years),
            ] );
    }


    /**
     * @Route("/intranet/hrm/vacation-requests", name="approve")
     */
    public function approveListAction(Request $request)
    {

        $vm = $this->get('app.vacation.manager');
        $listVacation = $vm->findAll();
        return $this->render('@App/vacation/requests.html.twig', array(
            'listVacation' => $listVacation,
        ));
    }


    /**
     * @Route("/intranet/my-vacation-requests", name="my_vacations_requests")
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

        $vm = $this->get('app.vacation.manager');
        $vacationId = $request->get('id_');
        $approval = $request->get('action');
        $refuseReason = $request->get('refuse_reason');
        $vacation = $vm->findOneById($vacationId);

        if ($this->get('security.authorization_checker')->isGranted('ROLE_DIRECTOR')) {
            $vm->adminValidation($vacation, $approval, $refuseReason);
        }

        else if ($this->get('security.authorization_checker')->isGranted('ROLE_HR')) {
            $vm->hrmValidation($vacation, $approval, $refuseReason);
        }

        return $this->redirect('/intranet/hrm/vacation-requests');
    }

    /**
     * @Route("/intranet/hrm/vacation-setting", name="vacation_setting")
     */
    public function settingAction(Request $request)
    {
        $vm = $this->get('app.vacation.manager');
        $religiousVacations = $this->getDoctrine()
            ->getRepository(ReligiousPaidVacation::class)->findAll();
        $religiousVacation = new ReligiousPaidVacation();
        $religiousVacationForm = $this->createForm(ReligiousPaidVacationType::class, $religiousVacation);
        if ($request->isMethod('POST')
            && $religiousVacationForm->handleRequest($request)->isValid()) {
            $vm->addReligiousVacation($religiousVacation);
            return $this->redirectToRoute('vacation_setting');
        }
        return $this->render('@App/vacation/setting.html.twig',[
            'religiousVacationForm' => $religiousVacationForm->createView(),
            'religiousVacations' => $religiousVacations
        ]);
    }

    /**
     * @Route("/intranet/hrm/edit-religious-vacation/{id}", name="edit_religious_vacation")
     */
    public function editReligiousVacationAction(Request $request, $id)
    {
        $jms = $this->get('jms_serializer');
        $vm = $this->get('app.vacation.manager');
        $religiousVacation = $this->getDoctrine()
            ->getRepository(ReligiousPaidVacation::class)->find($id);
        $religiousVacationForm = $this->createForm(ReligiousPaidVacationType::class, $religiousVacation);

        if ($request->isMethod('POST')
            && $religiousVacationForm->handleRequest($request)->isValid()) {
            $vm->addReligiousVacation($religiousVacation);

            $religiousVacation->setReference($this->get('translator')->trans($religiousVacation->getReference()));

            return $this->redirectToRoute('vacation_setting');
        //return new JsonResponse($jms->serialize($religiousVacation,'json'));
        }
        return $this->render('@App/vacation/form/religious_vacation.html.twig',[
            'religiousVacationForm' => $religiousVacationForm->createView(),
        ]);
    }
    /**
     * @Route("/intranet/hrm/delete-religious-vacation/{id}", name="delete_religious_vacation")
     */
    public function deleteReligiousVacationAction(Request $request,ReligiousPaidVacation $religiousPaidVacation)
    {
        if ($request->isMethod('DELETE')) {
            $this->getDoctrine()->getManager()->remove($religiousPaidVacation);
            $this->getDoctrine()->getManager()->flush();
            return new Response('item deleted',202);
        } else {
            return new Response('http method not allowed',405);
        }
    }
}
