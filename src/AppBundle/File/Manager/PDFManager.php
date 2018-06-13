<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 5/31/18
 * Time: 1:27 PM
 */

namespace AppBundle\File\Manager;

use Knp\Snappy\Pdf;
use Symfony\Component\Templating\EngineInterface;

class PDFManager
{
    /**
     * @var Pdf
     */
    private $pm;

    /**
     * @var EngineInterface
     */
    private $tm;

    /**
     * PDFManager constructor.
     * @param Pdf $pm
     * @param EngineInterface $tm
     */
    public function __construct(Pdf $pm, EngineInterface $tm)
    {
        $this->pm = $pm;
        $this->tm = $tm;
    }

    public function generate()
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
        try {
            $this->pm->generateFromHtml(
                $this->tm->render('@App/docs/templates/certification-of-salary.html.twig', $array)
                ,
                'test.pdf'
            );
        } catch (\Exceptione $e){

        }

    }
}