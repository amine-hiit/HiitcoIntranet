<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 5/17/18
 * Time: 7:33 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Cooptation;
use AppBundle\Entity\EmailType;
use AppBundle\Entity\Employee;
use AppBundle\Form\CooptationType;
use AppBundle\Form\EmailTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SettingController extends Controller
{

    /**
     * @Route("/intranet/admin/parameters", name="parameters")
     */
    public function indexAction(Request $request)
    {
        return $this->render('@App/setting/parameters.html.twig');
    }

    /**
     * @Route("/intranet/admin/emails", name="emails-configuration")
     */
    public function emailAction(Request $request)
    {
        $id = $request->get('id');
        if (null != $id){
            $email = $this->getDoctrine()->getRepository(EmailType::class)->find($id);
            //dump($email->getEmployees());die;
            $form = $this->createForm(EmailTypeType::class,$email);
            $form->handleRequest($request);
            //$this->getDoctrine()->getManager()->persist($email);

            if($form->isSubmitted()){
                $this->getDoctrine()->getManager()->persist($email);
                $this->getDoctrine()->getManager()->flush();
            }
        }
        $emails = $this->getDoctrine()->getRepository(EmailType::class)->findAll();
        return $this->render('@App/setting/email.html.twig',['emails' => $emails]);
    }

    /**
     * @Route("/intranet/admin/update/{id}", name="emails-edit")
     */
    public function editEmailAction(Request $request, $id)
    {
        $email = $this->getDoctrine()->getRepository(EmailType::class)->find($id);
        $form = $this->createForm(EmailTypeType::class,$email);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $this->getDoctrine()->getManager()->persist($email);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('@App/setting/email_form.html.twig',['form' => $form->createView()]);
    }
}