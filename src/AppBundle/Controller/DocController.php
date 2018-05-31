<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 5/28/18
 * Time: 11:43 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DocController extends Controller
{
    /**
     * @Route("/intranet/docs", name="docs")
     */
    public function docsAction()
    {
        return $this->render("@App/docs/docs.html.twig");
    }

    /**
     * @Route("/intranet/request-doc", name="doc-request")
     */
    public function requestAction(Request $request)
    {
        $dm = $this->get('app.docs.manager');
        $docRequest = $dm->create();

        $type = $request->get('type');
        $docRequest->setType($type);
        $docRequest->setEmployee($this->getUser());
        $dm->update($docRequest);
        return $this->redirect('/intranet/my-doc-requests-list');
    }
    /**
     * @Route("/intranet/doc-requests-list", name="doc-requests-list")
     */
    public function listAction()
    {
        $requests = $this->get('app.docs.manager')->findAll();
        return $this->render("@App/docs/requests.html.twig", array(
            'requests' => $requests
        ));
    }

    /**
     * @Route("/intranet/my-doc-requests-list", name="my-doc-requests-list")
     */
    public function myRequestsListAction()
    {
        $requests = $this->get('app.docs.manager')->findAllByUser($this->getUser());
        return $this->render("@App/docs/my-requests.html.twig", array(
            'requests' => $requests
        ));
    }


    /**
     * @Route("/intranet/validate-doc-request", name="validate-doc-request")
     */
    public function validateAction(Request $request)
    {
        $dm = $this->get('app.docs.manager');
        $docRequest = $dm->findOneById($request->get('request_id'));
        $docRequest->setStatus(Document::DOC_READY);
        $dm->update($docRequest);
        return $this->redirect('/intranet/doc-requests-list');
    }

    /**
     * @Route("/intranet/validate-doc-request", name="validate-doc-request")
     */
    public function generatePdf(Request $request)
    {
        $pm = $this->get('app.pdf.manager');

    }
}