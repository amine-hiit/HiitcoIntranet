<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Avatar;
use AppBundle\Entity\Employee;
use AppBundle\Entity\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Vacation;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class FileController extends Controller
{


    /**
     * @Route("/intranet/file/{type}/{id}", name = "download_file", requirements={
     *     "id" = "\d+",
     *     "type" = "avatar|resume|pdf|document"
     * })
     *
     */

    public function downloadAction(Request $request,$type,$id)
    {

        $response = new Response();

        $type = ucfirst($type);
        /**
         * @var  File $file
         */
        $file = $this->getDoctrine()->getRepository('AppBundle\\Entity\\'.$type)->find($id);
        $response->headers->set('Content-Type', $file->getMimeType());

        if (null !== $file) {
            $fileContent = file_get_contents($this->get('kernel')->getRootDir() .'/../web/'.$file->getUrl());
            $response->setContent($fileContent);
            return $response;
        }
        else{
            if ($type === 'Avatar'){
                $fileContent = file_get_contents(
                    $this->get('kernel')->getRootDir() .'/../web/img/avatar/default-avatar.png'
                );
                $response->setContent($fileContent);
                return $response;
            }
            return new Response("file does not exist",404);
        }
    }


    /**
     * @Route("/intranet/hrm/export/vacation-requests", name = "premier_test")
     */
    public function xlsExportAction()
    {



        $lm = $this->get('app.vacation.manager');
        $listVacation = $lm->findAll();
        $newSpreadsheet = $this->get('phpoffice.spreadsheet')->createSpreadsheet();
        $newSpreadsheet->setActiveSheetIndex(0);
        $newSpreadsheet->getActiveSheet()->setCellValue('A1','Type');
        $newSpreadsheet->getActiveSheet()->setCellValue('B1','Employé');
        $newSpreadsheet->getActiveSheet()->setCellValue('C1','Date_Début');
        $newSpreadsheet->getActiveSheet()->setCellValue('D1','Date_fin');
        $newSpreadsheet->getActiveSheet()->setCellValue('E1','Raison');
        $i=2;
        foreach ($listVacation as $vacation)
        {
            $newSpreadsheet->getActiveSheet()->setCellValue('A'.strval($i),strval($vacation->getType()));
            $newSpreadsheet->getActiveSheet()->setCellValue('B'.strval($i),$vacation->getEmployee()->getUserName());
            $newSpreadsheet->getActiveSheet()->setCellValue('C'.strval($i),$vacation->getStartDate()->format('d-m-Y'));
            $newSpreadsheet->getActiveSheet()->setCellValue('D'.strval($i),$vacation->getEndDate()->format('d-m-Y'));
            $newSpreadsheet->getActiveSheet()->setCellValue('E'.strval($i),$vacation->getReason());
            $i++;
        }
        
        $SW = $this->get('phpoffice.spreadsheet')->createWriter($newSpreadsheet,'Xlsx');
        $response=$this->get('phpoffice.spreadsheet')->createStreamedResponse(
            $newSpreadsheet,
            'Xlsx',
            200
        );
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'vacation_requests_'.strval(rand()).'.xlsx'
        );
        $response->headers->set('Content-Disposition', $dispositionHeader);
        return $response;
    }
}
