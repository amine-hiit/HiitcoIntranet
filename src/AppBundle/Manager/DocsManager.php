<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 5/28/18
 * Time: 1:28 PM
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\User;
use Symfony\Component\Routing\RouterInterface;
use AppBundle\Entity\Pdf as DocPdf;
use Knp\Snappy\Pdf;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Session;
use Psr\Log\LoggerInterface;


class DocsManager
{

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var Session\Flash\FlashBagInterface
     */
    private $flashBag;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Pdf
     */
    private $pdf;

    /**
     * @var RouterInterface
     */
    private $router;


    /**
     * @var NotificationManager
     */
    private $nm;

    /**
     * DocsManager constructor.
     * @param EngineInterface $templating
     * @param Session\Flash\FlashBagInterface $flashBagInterface
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @param Pdf $pdf
     * @param NotificationManager $nm
     * @param RouterInterface $router
     */
    public function __construct(
        EngineInterface $templating,
        Session\Flash\FlashBagInterface $flashBag,
        LoggerInterface $logger,
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        Pdf $pdf,
        NotificationManager $nm,
        RouterInterface $router)

    {
        $this->templating = $templating;
        $this->flashBag = $flashBag;
        $this->logger = $logger;
        $this->em = $em;
        $this->translator = $translator;
        $this->nm = $nm;
        $this->pdf = $pdf;
        $this->router = $router;
    }


    public function findAll()
    {
        return $this->em->getRepository(Document::class)->findAll();
    }

    public function findOneById($id)
    {
        return $this->em->getRepository(Document::class)->findOneBy(['id' => $id]);
    }

    public function findAllByUser(User $user)
    {
        return $this->em->getRepository(Document::class)->findAllByUser($user);
    }

    public function create()
    {
        return new Document();
    }

    public function update(Document $docRequest)
    {
        $this->em->persist($docRequest);
        $this->em->flush();
    }

    public function setDocReadyAction($docId)
    {
        $docRequest = $this->findOneById($docId);
        $docRequest->setStatus(Document::DOC_READY);
        $this->update($docRequest);
        $this->flashBag->add('success', $this->translator->trans('doc.ready.success'));
        $this->nm->generateNotification('document_ready',null, '/intranet/my-docs', $docRequest->getEmployee());
    }

    public function validateRequest($docId)
    {
        $docRequest = $this->findOneById($docId);
        $this->generatePdfFromUrl($docRequest);
        $docRequest->setStatus(Document::DOC_VALIDATED);
        $this->flashBag->add('success',$this->translator->trans('doc.validated.success'));
        $this->update($docRequest);
    }

    public function generateNotification($notifType,array $args = null, $url, $employees)
    {
        $this->nm->generateNotification($notifType, $args, $url, $employees);
    }

    public function generatePdfFromUrl(Document &$document)
    {
        $employee = $document->getEmployee();
        $array = [
            'employee' => $employee
        ];

        $docPdf = new DocPdf();
        $docPdf->setName($this->translator->trans($document->getType() . '_' . $employee->getUsername()) . '_' . time() . '.pdf');
        $docPdf->setUrl($docPdf->getUploadDir() . '/' . $docPdf->getName());
        $output = $docPdf->getUrl();

        $document->setPdf($docPdf);

        $this->em->persist($document);
        $this->em->flush();

        try {
            $this->pdf->generateFromHtml($this->templating->render(Document::ATTESTATION_OF_EMPLOYMENT_VIEW, $array), $output);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

    }
}