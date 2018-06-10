<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 5/28/18
 * Time: 11:43 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Document;
use AppBundle\Entity\Employee;
use AppBundle\Form\DocumentType;
use AppBundle\Entity\Pdf as DocPdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knp\Snappy\Pdf;

class DocsController extends Controller
{
    use ControllerTrait;
    /**
     * @Route("/intranet/request-doc", name="doc-request")
     */
    public function requestAction(Request $request)
    {
        $dm = $this->get('app.docs.manager');
        $docRequest = $dm->create();
        $form = $this->createForm(DocumentType::class, $docRequest);
        if ( $request->isMethod('POST'))
        {
            if ($form->handleRequest($request) && $form->isValid())
            {

                $docRequest->setEmployee($this->getUser());
                $dm->update($docRequest);
                $this->addFlash('success', $this->trans('request.success'));
/*                $this->get('app.notification.manager')->generateNotification(
                    'document_request',
                    [
                        $docRequest->getEmployee()->getUsername(),
                        $this->trans($docRequest->getType())
                    ],
                    '/intranet/hrm/docs',

                );*/
                return $this->redirectToRoute('my-docs');
            }
        }
        return $this->render('@App/docs/request.html.twig',[
            'form' => $form->createView()]);
    }

    /**
     * @Route("/intranet/my-docs", name="my-docs")
     */
    public function myDocsAction()
    {
        $requests = $this->get('app.docs.manager')->findAllByUser($this->getUser());
        return $this->render("@App/docs/my-requests.html.twig", array(
            'requests' => $requests
        ));
    }

    /**
     * @Route("/intranet/hrm/docs", name="doc-requests-list")
     */
    public function listAction()
    {
        $requests = $this->get('app.docs.manager')->findAll();
        return $this->render("@App/docs/requests.html.twig", array(
            'requests' => $requests
        ));
    }

    /**
     * @Route("/intranet/set-doc-ready", name="set-doc-ready")
     */
    public function setDocReadyAction(Request $request)
    {
        $this->get('app.docs.manager')->setDocReadyAction($request->get('request_id'));
        return $this->redirect('/intranet/hrm/docs');
    }

    /**
     * @Route("/intranet/validate-doc-request", name="validate-doc-request")
     */
    public function validateAction(Request $request)
    {
        $this->get('app.docs.manager')->validateRequest($request->get('request_id'));
        return $this->redirect('/intranet/hrm/docs');
    }

}
