<?php

namespace AppBundle\Entity;




use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;



/**
 * File class
 * @ORM\MappedSuperclass
 */
abstract class File
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    protected $url;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mime_type", type="string", length=255, nullable=true)
     */
    protected $mimeType;



    protected $file;






    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return File
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url.
     *
     * @param string|null url
     *
     * @return File
     */
    public function setUrl($url = null)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }


    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file=null)
    {
        $this->file = $file;
    }

    public function upload()
    {
        if (null === $this->file) {
            return;
        }
        $this->name = time().rand().'.'. ExtensionGuesser::getInstance()
                ->guess($this->file->getClientMimeType());
        $this->mimeType = $this->file->getClientMimeType();
        $this->url = $this->getUploadDir().'/'.$this->name;
        $this->file->move($this->getUploadRootDir(), $this->name);

    }


    abstract protected function getUploadRootDir();

    abstract public function getUploadDir();


    /**
     * Set mimeType.
     *
     * @param string|null $mimeType
     *
     * @return File
     */
    public function setMimeType($mimeType = null)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType.
     *
     * @return string|null
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }
}
