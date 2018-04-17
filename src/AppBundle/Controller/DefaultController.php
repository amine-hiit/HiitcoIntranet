<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Leave;
use AppBundle\Form\LeaveType;

class DefaultController extends Controller
{
    
    

    /**
     * @Route("/", name="homepage")
     * @Route("/intranet/home", name="intranet-homepage")
     * @Route("/intranet/", name="intranet")
     */
    public function indexAction(Request $request)
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->render('@App/home.html.twig');

        }
        return $this->redirect('login');
    }



}
