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
use AppBundle\Entity\EmployeeLanguage;
use AppBundle\Entity\Experience;
use AppBundle\Entity\Project;
use AppBundle\Entity\Formation;
use AppBundle\Form\ExperienceType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        return $this->getDoctrine()->getRepository(Cooptation::class)->findAllByUser($employee);

    }








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
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function listFormationAction()
    {
        return $this->getDoctrine()->getRepository(Formation::class)->findAll();
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
     *    path = "/intranet/api/employees{employee}/experiences",
     *    name = "app_experiences_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("experience", converter="fos_rest.request_body")
     */
    public function createExperienceAction(Experience $experience, Employee $employee)
    {
        /************* form validator here *************/
        $experience->setEmployee($employee);
        $this->getDoctrine()->getManager()->persist($experience);
        $this->getDoctrine()->getManager()->flush();

        return new Response('done');

    }

    /**
     * @Rest\Patch(
     *    path = "/intranet/api/experiences/{experience}",
     *    name = "app_experiences_update"
     * )
     * @Rest\View(StatusCode = 204)
     *
     */
    public function updateExperienceAction(Experience $experience, Request $request)
    {

        $form = $this->createForm(ExperienceType::class, $experience);
        $data = json_decode($request->getContent(), true);

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);

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
    public function showExperienceAction(Experience $experience)
    {
        return $experience;
    }

    /**
     * @Rest\Get(
     *     path = "/intranet/api/experiences",
     *     name = "app_experiences_list",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function listExperienceAction()
    {
        return $this->getDoctrine()->getRepository(Experience::class)->findAll();
    }

    /**
     * @Rest\Get(
     *     path = "/intranet/api/employees/{id}/experiences",
     *     name = "app_employee_experiences_list",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function listExperienceByEmployeeAction(Employee $employee)
    {
        return $this->getDoctrine()->getRepository(Experience::class)
            ->findEmployeeAllExperiences($employee);
    }










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
     * @Rest\View(
     *     serializerGroups = {"default"})
     * )
     */
    public function listProjectAction()
    {
        return $this->getDoctrine()->getRepository(Project::class)->findAll();
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