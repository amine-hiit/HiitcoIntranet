<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 4/29/18
 * Time: 9:45 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Formation;
use AppBundle\Form\FormationType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class FormationController extends Controller
{




    /**
     * @Route("/intranet/form/new-formation", options={"expose"=true}, name="new-formation")
     */
    public function createFormationAction(Request $request)
    {

        $formation = new Formation();
        $formationForm = $this->get('form.factory')
            ->create(FormationType::class, $formation);

        $formationForm->handleRequest($request);
        if ($request->getMethod()=='POST') {

            if ($formationForm->isValid()) {
                $this->getDoctrine()->getManager()->persist($formation);
                $this->getDoctrine()->getManager()->flush();

                $formationJson = array(
                    'id' => $formation->getId(),
                    'value' => $formation->getFullName(),
                );
                return new Response((json_encode($formationJson, JSON_FORCE_OBJECT)));
            }
        }



        return $this->render(
            '@App/form/formation.html.twig'
            ,array('formationForm' => $formationForm->createView())
        );
    }

}