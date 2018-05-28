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

class DocsManager
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * DocsManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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

}