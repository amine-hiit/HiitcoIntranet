<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 7/26/18
 * Time: 14:59
 */

namespace AppBundle\Controller\Api;


use AppBundle\Entity\Cooptation;
use AppBundle\Entity\Employee;

use AppBundle\Entity\EmployeeNotification;
use AppBundle\Entity\Notification;
use AppBundle\Repository\EmployeeNotificationRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;


class RestNotificationController extends FOSRestController
{

    /**
     * @param Notification $notification
     *
     * @Rest\Get(
     *     path = "/intranet/api/notifications/{notification}",
     *     name = "app_notifications_show",
     *     requirements = {"notification"="\d+"}
     * )
     *
     * @Rest\View(
     *     serializerGroups = {"list"})
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="not found",
     * )
     */
    public function showAction(Notification $notification)
    {
        if (null === $notification) {
            return $this->view("", 404);
        }
        return $notification;
    }

    /**
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @Rest\Get(path = "/intranet/api/notifications",name = "app_employee_notifications_list",
     *     requirements = {"employee"="\d+"}
     * )
     * @Rest\QueryParam(name="direction", requirements="asc|desc", default="desc", description="Sort order (asc or desc)")
     * @Rest\QueryParam(name="orderBy", default="createdAt")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="10", description="Max number of movies per page.")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="The pagination offset")
     * @Rest\View(
     *     serializerGroups = {"list"})
     * )
     * @SWG\Response(
     *     response=201,
     *     description="list and paginate vacations by user id",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=Notification::class, groups={"list"}))
     *      )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="not found",
     * )
     */
    public function listNotificationsAction(ParamFetcherInterface $paramFetcher)
    {
        $employee = $this->getUser();
        $paginator = $this->get('knp_paginator');
        $limit = $paramFetcher->get('limit');
        $page = $paramFetcher->get('page');
        $orderBy = $paramFetcher->get('orderBy');
        $direction = $paramFetcher->get('direction');

        $query = $this->getDoctrine()->getRepository(EmployeeNotification::class)
            ->findAllByEmployeeQuery(
            null,
            'desc',
            $employee
        );

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

        $query = $this->getDoctrine()->getRepository(Notification::class)
            ->findAllQuery($orderBy, $direction);

        return $paginator->paginate($query, $page, $limit)->getItems();

    }

}