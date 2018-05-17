<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 5/17/18
 * Time: 8:15 AM
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Cooptation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CooptationManager
{
    /**
     * @var EntityManagerInterface
     */

    private $em;

    /**
     * @var TokenStorageInterface
     */
    private $token;

    /**
     * CooptationManager constructor.
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $token
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $token)
    {
        $this->em = $em;
        $this->token = $token;
    }


    public function uploadCooptation(Cooptation $cooptation)
    {
        $cooptation->setEmployee($this->token->getToken()->getUser());
        $cooptation->getResumee()->upload();
        $this->em->persist($cooptation);
        $this->em->flush();
    }

    public function findAll()
    {
        return $this->em->getRepository(Cooptation::class)->findAll();
    }

}