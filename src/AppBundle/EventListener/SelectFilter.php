<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 7/18/18
 * Time: 18:32
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Employee;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
class SelectFilter
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * SelectFilter constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authChecker;
    }


    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($this->tokenStorage->getToken() == null)
            return;

        if ($entity instanceof Employee && (
            $entity !== $this->tokenStorage->getToken()->getUser()
            || !$this->authChecker->isGranted('ROLE_HR'))) {

                $entity->setStatus(null);
                $entity->setBirthday(null);
                $entity->setMaritalStatus(null);
                $entity->setDependentChild(null);
                $entity->setCnssNumber(null);
                $entity->setNotifications(null);
                $entity->setAddress(null);

        }
    }
}