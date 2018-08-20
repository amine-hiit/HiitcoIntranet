<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 7/26/18
 * Time: 14:55
 */

namespace AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Employee;
use AppBundle\Entity\EmployeeLanguage;
use AppBundle\Form\EmployeeLanguageType;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;

use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;



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
     * @param Request  $request
     *
     * @Rest\Post(
     *    path = "/intranet/api/languages",
     *    name = "app_language_create"
     * )
     *
     * @SWG\Parameter(
     *     name="language",
     *     in="body",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=EmployeeLanguage::class, groups={"default"}))
     *      )
     *  )
     * @SWG\Response(
     *     response=400,
     *     description="Request failed",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Request a new language",
     *      )
     * )
     *
     * @return  View
     */

    public function createAction(Request $request)
    {

        $language = new EmployeeLanguage();
        $form = $this->createForm(EmployeeLanguageType::class, $language, array('csrf_protection' => false));
        $language->setEmployee($this->getUser());

        $form->submit($request->request->all(), false);

        if (count($form->getErrors()) > 0) {
            return $this->view($form->getErrors(), 400);
        }

        $this->getDoctrine()->getManager()->persist($language);
        $this->getDoctrine()->getManager()->flush();

        return $this->view();

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