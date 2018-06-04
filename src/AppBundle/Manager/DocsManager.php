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
use Knp\Snappy\Pdf;

class DocsManager
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Pdf
     */
    private $pdf;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * DocsManager constructor.
     * @param EntityManagerInterface $em
     * @param Pdf $pdf
     * @param RouterInterface $router
     */
    public function __construct(EntityManagerInterface $em, Pdf $pdf, RouterInterface $router)
    {
        $this->em = $em;
        $this->pdf = $pdf;
        $this->router = $router;
    }


    public function findAll()
    {
        return $this->em->getRepository(Document::class)->findAll();
    }

    public function findOneById($id)
    {
        return $this->em->getRepository(Document::class)->findOneBy(['id'=>$id]);
    }

    public function findAllByUser(User $user)
    {
        return $this->em->getRepository(Document::class)->findAllByUser($user);
    }

    public function create()
    {
        $doc = new Document();
        return new Document();
    }

    public function update(Document $docRequest)
    {
        $this->em->persist($docRequest);
        $this->em->flush();
    }

/*
    public function generatePdfFromUrl($route,$output)
    {
        dump($PHPSESSID_KEY );dump( $PHPSESSID);die;

        $pdf = $this->pdf;
        $pdf->generate($this->router->generate($route, [], 0),
            $output,
            array(
                'cookie' => array(
                    $PHPSESSID_KEY => $PHPSESSID
                ))
        );
    }
*/

}