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

class DocController extends Controller
{

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
                $this->addFlash('success', 'Demande effectuée');
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
     * @Route("/intranet/validate-doc-request", name="validate-doc-request")
     */
    public function validateAction(Request $request)
    {
        $dm = $this->get('app.docs.manager');
        $docRequest = $dm->findOneById($request->get('request_id'));
        $docRequest->setStatus(Document::DOC_READY);
        $this->generatePdfFromUrl($docRequest);
        $dm->update($docRequest);
        return $this->redirect('/intranet/hrm/docs');
    }

    private function generatePdfFromUrl(Document &$document )
    {
        $employee = $document->getEmployee();
        $array = [
            'employee' => $employee
        ];

        $docPdf = new DocPdf();
        $docPdf->setName($document->getType().'_'.$employee->getUsername().'_'.time().'.pdf');
        $docPdf->setUrl($docPdf->getUploadDir().'/'.$docPdf->getName());
        $output =$docPdf->getUrl();

        $document->setPdf($docPdf);

        $this->getDoctrine()->getManager()->persist($document);
        $this->getDoctrine()->getManager()->flush();

        $pdf = $this->get('knp_snappy.pdf');
        try{
            $pdf->generateFromHtml($this->renderView(Document::ATTESTATION_OF_EMPLOYMENT_VIEW, $array),$output);
        }catch (\Exception $exception)
        {
            $this->get('logger')->error($exception->getMessage());
        }


    }

}
