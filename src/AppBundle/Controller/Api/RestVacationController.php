<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 7/26/18
 * Time: 14:48
 */

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Vacation;
use AppBundle\Form\VacationType;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class RestVacationController extends FOSRestController
{

    /**
     * @Rest\Post(
     *    path = "/intranet/api/employees{employee}/vacations",
     *    name = "app_vacation_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("vacation", converter="fos_rest.request_body")
     */
    public function createeAction(Vacation $vacation, Employee $employee)
    {
        $vm = $this->get('app.vacation.manager');
        $vacation->setEmployee($employee);
        $vm->request($vacation);
        $this->getDoctrine()->getManager()->persist($vacation);
        $this->getDoctrine()->getManager()->flush();

        return $this->view();

    }

    /**
     * @Rest\Put(
     *    path = "/intranet/api/vacations/{vacation}",
     *    name = "app_vacation_validate"
     * )
     * @Rest\View(StatusCode = 201)
     */
    public function validateAction(Vacation $vacation, Request $request)
    {

        $vm = $this->get('app.vacation.manager');
        $vacation = $vm->findOneById($vacation->getId());
        $validation = $request->request->get('validation');
        $refuseReason = $request->request->get('refuseReason');

        if($validation !== "refuser" || $validation !== "accepter")
            return $this->view("",400);

        if ($this->get('security.authorization_checker')->isGranted('ROLE_DIRECTOR'))
                $vm->adminValidation($vacation, $validation, $refuseReason);


        else if ($this->get('security.authorization_checker')->isGranted('ROLE_HR'))
            $vm->hrmValidation($vacation, $validation, $refuseReason);

        return $this->view();

    }


    /**
     * @Rest\Get(
     *     path = "/intranet/api/vacations/{id}",
     *     name = "app_vacations_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default","list"})
     * )
     */
    public function showAction(Vacation $vacation)
    {
        return $vacation;
    }

    /**
     *
     * @Rest\Get(path = "/intranet/api/employees/{employee}/vacations",name = "app_employee_vacations_list",
     *     requirements = {"employee"="\d+"}
     * )
     * @Rest\QueryParam(name="direction", requirements="asc|desc", default="desc", description="Sort order (asc or desc)")
     * @Rest\QueryParam(name="orderBy", default="startDate")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="10", description="Max number of movies per page.")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="The pagination offset")
     * @Rest\QueryParam(name="type", requirements="vacation|absence")
     * @Rest\View(
     *     serializerGroups = {"default","list"})
     * )
     */
    public function listEmployeeVacationAction(ParamFetcherInterface $paramFetcher, Employee $employee)
    {
        $paginator = $this->get('knp_paginator');

        $limit = $paramFetcher->get('limit');
        $page = $paramFetcher->get('page');
        $orderBy = $paramFetcher->get('orderBy');
        $direction = $paramFetcher->get('direction');
        $type = $paramFetcher->get('type');

        $query = $this->getDoctrine()->getRepository(Vacation::class)
            ->findAllByEmployeeQuery($orderBy, $direction, $employee, ['type' => $type]);

        return $paginator->paginate($query, $page, $limit)->getItems();

    }

    /**
     *
     * @Rest\Get(path = "/intranet/api/vacations",name = "app_vacations_list", requirements = {"id"="\d+"})
     * @Rest\QueryParam(name="direction", requirements="asc|desc", default="asc", description="Sort order (asc or desc)")
     * @Rest\QueryParam(name="orderBy", default="startDate")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="10", description="Max number of movies per page.")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="The pagination offset")
     * @Rest\QueryParam(name="type", requirements="vacation|absence")
     * @Rest\View(
     *     serializerGroups = {"default","list"})
     * )
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {

        $paginator = $this->get('knp_paginator');

        $limit = $paramFetcher->get('limit');
        $page = $paramFetcher->get('page');
        $orderBy = $paramFetcher->get('orderBy');
        $direction = $paramFetcher->get('direction');

        $query = $this->getDoctrine()->getRepository(Vacation::class)
            ->findAllQuery($orderBy, $direction);

        return $paginator->paginate($query, $page, $limit)->getItems();

    }
}