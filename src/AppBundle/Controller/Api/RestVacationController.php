<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 7/26/18
 * Time: 14:48
 */

namespace AppBundle\Controller\Api;

use AppBundle\Controller\ControllerTrait;
use AppBundle\Entity\Employee;
use AppBundle\Entity\Vacation;
use AppBundle\Form\VacationType;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestVacationController extends FOSRestController
{
    use ControllerTrait;

    /**
     * @param Request $request
     *
     * @Rest\Post(
     *    path = "/intranet/api/vacations",
     *    name = "app_vacation_create"
     * )
     *
     * @SWG\Parameter(
     *     name="validation",
     *     in="body",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=Vacation::class, groups={"request"}))
     *      )
     *  )
     * @SWG\Response(
     *     response=400,
     *     description="Request failed",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Request a new vacation|absence",
     *      )
     * )
     *
     * @return  View $view
     */
    public function createAction(Request $request)
    {
        $vacation  = new Vacation();
        $form = $this->createForm(VacationType::class, $vacation, ['csrf_protection' => false]);
        $vacation->setEmployee($this->getUser());
        $form->submit($request->request->all(), false);
        if (count($form->getErrors()) > 0) {
            return $this->view($form->getErrors(), 400);
        }

        $vm = $this->get('app.vacation.manager');
        $vm->request($vacation);

        return $this->view();
    }

    /**
     * @param Vacation $vacation
     * @param Request  $request
     *
     * @Rest\Put(
     *    path = "/intranet/api/vacations/{vacation}",
     *    name = "app_vacation_validate"
     * )
     * @Rest\View()
     *
     * @SWG\Parameter(
     *     name="validation",
     *     in="body",
     *     @SWG\Schema(
     *         @SWG\Items(type="object",
     *             @SWG\Property(property="validation", type="string", enum={"accepter","refuser"}),
     *             @SWG\Property(property="refuseReason", type="string")
     *         )
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Validate vacation"
     * )
     *
     * @return View $view
     */
    public function validateAction(Vacation $vacation, Request $request)
    {

        $vm = $this->get('app.vacation.manager');
        $vacation = $vm->findOneById($vacation->getId());
        $validation = $request->request->get('validation');
        $refuseReason = $request->request->get('refuseReason');

        if (null !== $vacation) {
            return $this->view("validation field is required", 400);
        }

        if ("refuser" !== $validation && "accepter" !== $validation) {
            return $this->view("", 400);
        }

        if ($this->get('security.authorization_checker')->isGranted('ROLE_DIRECTOR')) {
               try {
                   $vm->adminValidation($vacation, $validation, $refuseReason);
               }catch (\Exception $e) {
                   $this->log('Error', $e->getMessage());
               }
        }

        elseif ($this->get('security.authorization_checker')->isGranted('ROLE_HR')) {
            $vm->hrmValidation($vacation, $validation, $refuseReason);
        }

        return $this->view();
    }

    /**
     * @param Vacation $vacation
     *
     * @Rest\Get(
     *     path = "/intranet/api/vacations/{vacation}",
     *     name = "app_vacations_show",
     *     requirements = {"vacation"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default","list"})
     * )
     * @SWG\Response(
     *     response=201,
     *     description="list vacation by id",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=Vacation::class, groups={"default","list"}))
     *      )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="not found",
     * )
     */
    public function showAction(Vacation $vacation)
    {
        if (null === $vacation) {
            return $this->view("", 404);
        }

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
     * @Rest\QueryParam(name="type", requirements="^(vacation|absence)$",description="Type (vacation or absence)")
     * @Rest\View(
     *     serializerGroups = {"default","list"})
     * )
     * @SWG\Response(
     *     response=201,
     *     description="list and paginate vacations by user id",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=Vacation::class, groups={"default","list"}))
     *      )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="not found",
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
     * @param ParamFetcherInterface $paramFetcher
     *
     * @Rest\Get(path = "/intranet/api/vacations",name = "app_vacations_list")
     * @Rest\QueryParam(name="direction", requirements="asc|desc", default="asc", description="Sort order (asc or desc)")
     * @Rest\QueryParam(name="orderBy", default="startDate")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="10", description="Max number of movies per page.")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="The pagination offset")
     * @Rest\QueryParam(name="type", requirements="^(vacation|absence)$",description="accept only vacation or absence")
     * @Rest\View(
     *     serializerGroups = {"default","list"})
     * )
     * @SWG\Response(
     *     response=201,
     *     description="list and paginate vacations",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=Vacation::class, groups={"default","list"}))
     *      )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="not found",
     * )
     *
     * @return View $view
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
