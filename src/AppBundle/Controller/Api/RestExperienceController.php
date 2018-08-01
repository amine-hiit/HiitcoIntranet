<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 7/26/18
 * Time: 14:48
 */

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Experience;
use AppBundle\Form\ExperienceType;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestExperienceController extends FOSRestController
{
    /**
     * @Rest\Post(
     *    path = "/intranet/api/experiences",
     *    name = "app_experiences_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("experience", converter="fos_rest.request_body")
     */
    public function createAction(Experience $experience)
    {
        $form = $this->createForm(ExperienceType::class,$experience,['csrf_protection' => false]);
        $form->submit($experience);
        $experience->setEmployee($this->getUser());
        if (count($form->getErrors() != 0))
            return $form->getErrors();

        $this->getDoctrine()->getManager()->persist($experience);
        $this->getDoctrine()->getManager()->flush();

        return $this->view();
    }

    /**
     * @Rest\Put(
     *    path = "/intranet/api/experiences/{experience}",
     *    name = "app_experiences_update"
     * )
     * @Rest\View(StatusCode = 204)
     *
     */
    public function updateExperienceAction(Experience $experience, Request $request)
    {
        dump($experience);die;
        $form = $this->createForm(ExperienceType::class, $experience, ['csrf_protection' => false]);
        $data = json_decode($request->getContent(), true);

        $form->submit($data, false);

        $this->getDoctrine()->getManager()->persist($experience);
        $this->getDoctrine()->getManager()->flush();
        return $this->view();
    }

    /**
     * @Rest\Get(
     *     path = "/intranet/api/experiences/{id}",
     *     name = "app_experiences_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function showAction(Experience $experience)
    {
        return $experience;
    }

    /**
     *
     * @Rest\Get(path = "/intranet/api/employees/{employee}/experiences",name = "app_employee_experiences_list",
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
    public function listEmployeeExperienceAction(ParamFetcherInterface $paramFetcher, Employee $employee)
    {
        $paginator = $this->get('knp_paginator');

        $limit = $paramFetcher->get('limit');
        $page = $paramFetcher->get('page');
        $orderBy = $paramFetcher->get('orderBy');
        $direction = $paramFetcher->get('direction');

        $query = $this->getDoctrine()->getRepository(Experience::class)
            ->findAllByEmployeeQuery($orderBy, $direction, $employee);

        return $paginator->paginate($query, $page, $limit)->getItems();

    }



    /**
     *
     * @Rest\Get(path = "/intranet/api/experiences",name = "app_experiences_list", requirements = {"id"="\d+"})
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

        $query = $this->getDoctrine()->getRepository(Experience::class)
            ->findAllQuery($orderBy, $direction);

        return $paginator->paginate($query, $page, $limit)->getItems();

    }

}