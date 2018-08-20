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

use AppBundle\Form\EmployeeApiType;
use AppBundle\Form\EmployeeRegistrationType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;

use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;



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


    /**
     * @param Request $request
     *
     * @Rest\Post(
     *    path = "/intranet/api/employee-registration",
     *    name = "app_employee_register"
     * )
     *
     * @SWG\Parameter(
     *     name="register-employee",
     *     in="body",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=Employee::class, groups={"register"}))
     *      )
     *  )
     * @SWG\Response(
     *     response=400,
     *     description="Request failed",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="employee registred",
     *      )
     * )
     *
     * @return  View
     */
    public function createAction(Request $request)
    {
        $employee  = new Employee();
        $form = $this->createForm(EmployeeRegistrationType::class, $employee, ['csrf_protection' => false]);
        $form->submit($request->request->all());
        if (count($form->getErrors()) > 0) {
            return $this->view($form->getErrors(), 400);
        }
        $employee->setEnabled(true);
        $this->getDoctrine()->getManager()->persist($employee);
        $this->getDoctrine()->getManager()->flush();
        return $this->view("employee registred",201);
    }


    /**
     * @param Request $request
     *
     * @Rest\Post(
     *    path = "/intranet/api/employee-form",
     *    name = "app_employee_form"
     * )
     *
     * @SWG\Parameter(
     *     name="employee-form",
     *     in="body",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=Employee::class, groups={"employeeForm"}))
     *      )
     *  )
     * @SWG\Response(
     *     response=400,
     *     description="Request failed",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="employee validated",
     *      )
     * )
     *
     * @return  View
     */
    public function formAction(Request $request)
    {
        $employeeManager = $this->get('app.employee.manager');
        $employee = $this->getUser();
        $form = $this->createForm(EmployeeApiType::class, $employee, ['csrf_protection' => false]);
        $form->submit($request->request->all());
        if (count($form->getErrors()) > 0) {
            return $this->view($form->getErrors(), 400);
        }
        $employeeManager->completeEmployeeForm($employee);
        $this->getDoctrine()->getManager()->persist($employee);
        $this->getDoctrine()->getManager()->flush();
        return $this->view("employee validated",201);
    }





}

