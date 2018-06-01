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
use Knp\Snappy\Pdf;

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
     * @Route("/intranet/test", name="test")
     */
    public function test(Request $request)
    {
        try {
            $this->generatePdfFromUrl('validate-doc-request','test'.rand(10,99).'.pdf');
        } catch (\Exceptione $e){

        };die;
    }
    /**
     * @Route("/intranet/validate-doc-request", name="validate-doc-request")
     */
    public function generatePdf(Request $request)
    {
        $array = [
            'employee' => [
                'civility' => 'M.',
                'firstName' => 'fn',
                'lastName' => 'ln',
                'startDate' => new \DateTime('now'),
                'currentPosition' => 'position'
            ]
        ];
        return $this->render('@App/docs/templates/certification-of-salary.html.twig', $array);
    }

    private function generatePdfFromUrl($route,$output)
    {
        $Pdf = $this->get('knp_snappy.pdf');
        $Pdf->generate($this->generateUrl($route),$output);
    }

}