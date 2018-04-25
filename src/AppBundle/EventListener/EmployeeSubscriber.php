<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 4/25/18
 * Time: 9:31 AM
 */

namespace AppBundle\EventListener;

use PHPMailer\PHPMailer\Exception;
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

    const INVALID_EMPLOYEE_ALLOWED_ROUTES = array(
    'employee-form',
    'fos_user_security_login',
    'homepage',
    'intranet-homepage',
    'intranet'
    );
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

        //$user = $this->tokenStorage->getToken()->getUser();
        //dump($this->authorizationChecker);die;

        //$this->tokenStorage->getToken()->getUser();
        //$response = new RedirectResponse('/');


        //$event->setResponse($response);
        //getRequest()->get('_route');

        /*$hostName = $event->getRequest()->headers->get('host');
        $url = $event->getRequest()->headers->get('referer');
        $split = explode($hostName,$url);
        $route = $split[1];
        //get('security.token_storage')->getToken()->getUser();

        $this->tokenStorage->getToken()->getUser()
        */

        $currentRoute = $event->getRequest()->get('_route');


        if(null===$token=$this->tokenStorage->getToken())
            $event->setResponse(new RedirectResponse($this->router->generate('employee-form')));


        $currentRoute = $event->getRequest()->get('_route');


        if(null===$token=$this->tokenStorage->getToken())
            $event->setResponse(new RedirectResponse($this->router->generate('employee-form')));

        $user = $token->getUser();



        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            if (!$user->isValid())
            {
                if ($currentRoute && !in_array($currentRoute, self::INVALID_EMPLOYEE_ALLOWED_ROUTES))
                {
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