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

class CooptationController extends Controller
{

    /**
     * @Route("/intranet/cooptation-request", name="cooptation-request")
     */
    public function addAction(Request $request)
    {

        $cooptation = new Cooptation();
        $form = $this->createForm(CooptationType::class, $cooptation);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $this->getCooptationManager()->uploadCooptation($cooptation);
            }
            return $this->redirect($this->generateUrl('homepage'));
        }
        return $this->render('@App/cooptation/cooptation_request.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/intranet/hrm/cooptations", name="cooptations")
     */
    public function listAction(Request $request)
    {

        $cooptations = $this->getCooptationManager()->findAll();
        return $this->render('@App/cooptation/cooptations_list.html.twig', array(
            'cooptations' => $cooptations,
        ));

    }

    private function getCooptationManager()
    {
     return   $this->get('app.cooptation.manager');
    }


}