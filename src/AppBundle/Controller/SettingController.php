<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 5/17/18
 * Time: 7:33 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Cooptation;
use AppBundle\Entity\Employee;
use AppBundle\Form\CooptationType;
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
        return $this->render('@App/setting/email.html.twig');
    }
}