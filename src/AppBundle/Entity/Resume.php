<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resume
 *
 * @ORM\Table(name="resume")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResumeRepository")
 */
class Resume extends File
{

    public function getUploadRootDir()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    public function getUploadDir()
    {
        return 'uploads/file/resume';
    }
}
