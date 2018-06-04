<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table(name="pdf")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PdfRepository")
 */
class Pdf extends File
{

    public function getUploadRootDir()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    public function getUploadDir()
    {
        return 'uploads/file/document';
    }


}
