<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 7/26/18
 * Time: 14:54
 */

namespace AppBundle\Controller\Api;


use AppBundle\Entity\Employee;
use AppBundle\Entity\Project;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;

use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;



class RestProjectController extends FOSRestController
{



    /**
     * @Rest\Get(
     *     path = "/intranet/api/projects/{id}",
     *     name = "app_projects_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function showProjectAction(Project $project)
    {
        return $project;
    }

    /**
     * @Rest\Get(
     *     path = "/intranet/api/projects",
     *     name = "app_projects_list",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="desc",
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="Max number of movies per page."
     * )
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="The pagination offset"
     * )
     * @Rest\View()
     *     serializerGroups = {"default"})
     * )
     */
    public function listProjectAction(ParamFetcherInterface $paramFetcher)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $paginator  = $this->get('knp_paginator');

        return $paginator->paginate(
            $em->getRepository('AppBundle:Experience')
                ->findBy([],['startDate' => $paramFetcher->get('order')]),
            $paramFetcher->get('page'),
            $paramFetcher->get('limit')
        )->getItems();
    }


    /**
     * @Rest\Get(
     *     path = "/intranet/api/employees/{id}/projects",
     *     name = "app_employee_projects_list",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default","list"})
     * )
     */
    public function listProjectByEmployeeAction(Employee $employee)
    {
        return $this->getDoctrine()->getRepository(Project::class)
            ->findEmployeeAllProjects($employee);
    }

    /**
     *
     * @Rest\Get(path = "/intranet/api/employees/{employee}/projects",name = "app_employee_projects_list",
     *     requirements = {"employee"="\d+"}
     * )
     * @Rest\QueryParam(name="direction", requirements="asc|desc", default="desc", description="Sort order (asc or desc)")
     * @Rest\QueryParam(name="orderBy",)
     * @Rest\QueryParam(name="limit", requirements="\d+", default="10", description="Max number of movies per page.")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="The pagination offset")
     * @Rest\View(
     *     serializerGroups = {"default","list"})
     * )
     */
    public function listEmployeeProjectAction(ParamFetcherInterface $paramFetcher, Employee $employee)
    {
        $paginator = $this->get('knp_paginator');

        $limit = $paramFetcher->get('limit');
        $page = $paramFetcher->get('page');
        $orderBy = $paramFetcher->get('orderBy');
        $direction = $paramFetcher->get('direction');

        $query = $this->getDoctrine()->getRepository(Project::class)
            ->findAllByEmployeeQuery($orderBy, $direction, $employee);

        return $paginator->paginate($query, $page, $limit)->getItems();

    }


    /**
     *
     * @Rest\Get(path = "/intranet/api/projects",name = "app_projects_list", requirements = {"id"="\d+"})
     * @Rest\QueryParam(name="direction", requirements="asc|desc", default="desc", description="Sort order (asc or desc)")
     * @Rest\QueryParam(name="orderBy",)
     * @Rest\QueryParam(name="limit", requirements="\d+", default="10", description="Max number of movies per page.")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="The pagination offset")
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

        $query = $this->getDoctrine()->getRepository(Project::class)
            ->findAllQuery($orderBy, $direction);

        return $paginator->paginate($query, $page, $limit)->getItems();

    }



}