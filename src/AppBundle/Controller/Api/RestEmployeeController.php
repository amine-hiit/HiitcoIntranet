<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 7/15/18
 * Time: 13:22
 */

namespace AppBundle\Controller\Api;


use AppBundle\Entity\Avatar;
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



class RestEmployeeController extends FOSRestController
{

    /**
     * @Rest\Get(
     *     path = "/intranet/api/basic-employees/{id}",
     *     name = "app_employees_basic_info_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"basic_info"})
     * )
     */
    public function showBasicInfoAction(Employee $employee)
    {
        return $employee;
    }

    /**
     * @Rest\Get(
     *     path = "/intranet/api/employees/{id}",
     *     name = "app_employees_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"detail","default","basic_info"})
     * )
     */
    public function showDetailAction(Employee $employee)
    {
        $employee->setAvatar($employee->getAvatar());
        return $employee;

    }

    /**
     * @Rest\Get(
     *     path = "/intranet/api/avatars/{id}",
     *     name = "app_avatars_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function showAvatarAction(Avatar $avatar)
    {
        return $avatar;
    }








    /**
     * @Rest\Get(
     *     path = "/intranet/api/employees",
     *     name = "app_employees_list",
     * )
     * @Rest\View(
     *     serializerGroups = {"list"})
     * )
     */

    public function listAction()
    {
        return $this->getDoctrine()->getRepository(Employee::class)->findAll();
    }




}

