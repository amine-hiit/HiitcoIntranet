<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 4/25/18
 * Time: 9:31 AM
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Employee;
use Symfony\Component\EventDispatcher\EventSubscriberinterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class EmployeeSubscriber implements EventSubscriberinterface
{

    const INVALID_EMPLOYEE_ALLOWED_ROUTES = [
        'employee-form',
        'new-formation',
        'fos_user_security_login',
        'homepage',
        '_wdt',
        'intranet-homepage',
        'intranet',
        'fos_js_routing_js',
    ];
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * EmployeeSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $token
     * @param RouterInterface $router
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->router = $router;
    }


    public function onKernelController(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()
            || ('assetic.controller:render' === $event->getRequest()->attributes->get('_controller'))
            || !($this->tokenStorage->getToken() instanceof TokenInterface)
            || !($this->tokenStorage->getToken()->getUser() instanceof Employee)
        ) {
            // don't do anything
            return;
        }
        $currentRoute = $event->getRequest()->get('_route');
        $token = $this->tokenStorage->getToken();

        $user = $token->getUser();


        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            if (!$user->isValid()) {
                if ($currentRoute && !in_array($currentRoute, self::INVALID_EMPLOYEE_ALLOWED_ROUTES)) {
                    $event->setResponse(new RedirectResponse($this->router->generate('homepage')));
                }
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'kernel.request' => 'onKernelController'
        );

    }
}