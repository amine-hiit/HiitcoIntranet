<?php
namespace AppBundle\Controller;

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
     * @Route("/intranet/hrm/export/vacation-requests", name = "premier_test")
     */
    public function xlsExportAxtion()
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
