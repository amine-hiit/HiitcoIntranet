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

class RestCooptationController extends FOSRestController
{

    /**
     * @Rest\Get(
     *     path = "/intranet/api/cooptations/{id}",
     *     name = "app_cooptations_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function showCooptationAction(Cooptation $cooptation)
    {
        return $cooptation;
    }

    /**
     * @Rest\Get(
     *     path = "/intranet/api/cooptations",
     *     name = "app_cooptations_list",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function listCooptationAction()
    {
        return $this->getDoctrine()->getRepository(Cooptation::class)->findAll();
    }

    /**
     * @Rest\Get(
     *     path = "/intranet/api/employees/{id}/cooptations",
     *     name = "app_cooptations_list",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function listCooptationByEmployeeAction(Employee $employee)
    {
        return $this->getDoctrine()->getRepository(Cooptation::class)
            ->findAllByUser($employee);

    }

}