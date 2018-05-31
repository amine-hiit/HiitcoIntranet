<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 5/29/18
 * Time: 10:49 AM
 */

namespace AppBundle\File\Manager;

use Knp\Snappy\Pdf;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PDFManager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * PDFManager constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function makePDF(array $strings, $template)
    {

        $pdf = $this->container->get('app.pdf.manager');
        $pdf->generateFromHtml($template);
    }

}