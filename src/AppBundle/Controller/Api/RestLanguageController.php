<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 7/26/18
 * Time: 14:55
 */

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Employee;
use AppBundle\Entity\EmployeeLanguage;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;

use JMS\Serializer\SerializationContext;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;


class RestLanguageController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/intranet/api/languages/{id}",
     *     name = "app_languages_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function showLanguageAction(EmployeeLanguage $language)
    {
        return $language;
    }

    /**
     * @Rest\Get(
     *     path = "/intranet/api/languages",
     *     name = "app_languages_list",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function listLanguageAction()
    {
        return $this->getDoctrine()->getRepository(EmployeeLanguage::class)->findAll();
    }

    /**
     * @Rest\Get(
     *     path = "/intranet/api/employees/{id}/languages",
     *     name = "app_employee_languages_list",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function listLanguageByEmployeeAction(Employee $employee)
    {
        return $this->getDoctrine()->getRepository(EmployeeLanguage::class)
            ->findAllByUser($employee);
    }

    /**
     *
     * @Rest\Get(path = "/intranet/api/employees/{employee}/employee-languages",name = "app_employee_languages_list",
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
    public function listEmployeeeLanguageAction(ParamFetcherInterface $paramFetcher, Employee $employee)
    {
        $paginator = $this->get('knp_paginator');

        $limit = $paramFetcher->get('limit');
        $page = $paramFetcher->get('page');
        $orderBy = $paramFetcher->get('orderBy');
        $direction = $paramFetcher->get('direction');

        $query = $this->getDoctrine()->getRepository(EmployeeLanguage::class)
            ->findAllByEmployeeQuery($orderBy, $direction, $employee);

        return $paginator->paginate($query, $page, $limit)->getItems();

    }


    /**
     *
     * @Rest\Get(path = "/intranet/api/employee-languages",name = "app_languages_list", requirements = {"id"="\d+"})
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

        $query = $this->getDoctrine()->getRepository(EmployeeLanguage::class)
            ->findAllQuery($orderBy, $direction);

        return $paginator->paginate($query, $page, $limit)->getItems();

    }

}