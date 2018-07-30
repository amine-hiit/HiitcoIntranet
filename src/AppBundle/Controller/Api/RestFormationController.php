<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 7/26/18
 * Time: 14:49
 */

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Formation;
use AppBundle\Form\FormationType;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;


class RestFormationController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/intranet/api/formations/{id}",
     *     name = "app_formations_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function showFormationAction(Formation $formation)
    {
        return $formation;
    }

    /**
     * @Rest\Get(
     *     path = "/intranet/api/formations",
     *     name = "app_formations_list",
     *     requirements = {"id"="\d+"}
     * )
     *
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
    public function listFormationAction(ParamFetcherInterface $paramFetcher)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $paginator  = $this->get('knp_paginator');

        return $paginator->paginate(
            $em->getRepository('AppBundle:Formation')
                ->findBy([],['startDate' => $paramFetcher->get('order')]),
            $paramFetcher->get('page'),
            $paramFetcher->get('limit')
        )->getItems();
    }

    /**
     * @Rest\Get(
     *     path = "/intranet/api/employees/{id}/formations",
     *     name = "app_employee_formations_list",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function listFormationByEmployeeAction(Employee $employee)
    {
        return $this->getDoctrine()->getRepository(Formation::class)->findAllByUser($employee);
    }

    /**
     * @Rest\Post(
     *    path = "/intranet/api/employees{employee}/formations",
     *    name = "app_formations_create"
     * )
     * @Rest\View(StatusCode = 201)
     */
    public function createFormationAction( Employee $employee, Request $request)
    {

        $formation = new Formation();
        $serializer = new Serializer(array(new DateTimeNormalizer()));
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(FormationType::class, $formation, array('csrf_protection' => false));

        //$formation->setStartDate($data['start_date']);
        $form->submit($data);

        if ($form->isValid()){
        } else {
            return $this->render('@App/errors.html.twig', [
                'form' => $form->createView()]);
        }
        return new Response('done');

    }

    /**
     *
     * @Rest\Get(path = "/intranet/api/employees/{employee}/formations",name = "app_employee_formations_list",
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
    public function listEmployeeFormationAction(ParamFetcherInterface $paramFetcher, Employee $employee)
    {
        $paginator = $this->get('knp_paginator');

        $limit = $paramFetcher->get('limit');
        $page = $paramFetcher->get('page');
        $orderBy = $paramFetcher->get('orderBy');
        $direction = $paramFetcher->get('direction');

        $query = $this->getDoctrine()->getRepository(Formation::class)
            ->findAllByEmployeeQuery($orderBy, $direction, $employee);

        return $paginator->paginate($query, $page, $limit)->getItems();

    }


    /**
     *
     * @Rest\Get(path = "/intranet/api/formations",name = "app_formations_list", requirements = {"id"="\d+"})
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

        $query = $this->getDoctrine()->getRepository(Formation::class)
            ->findAllQuery($orderBy, $direction);

        return $paginator->paginate($query, $page, $limit)->getItems();

    }

}