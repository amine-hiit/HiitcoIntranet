<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 4/25/18
 * Time: 9:31 AM
 */

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberinterface;
use Symfony\Component\HttpKernel\Event\Ge;

class EmployeeSubscriber implements EventSubscriberinterface
{

    public function onKernelRequest($event)
    {
        die('test');
    }
    public static function getSubscribedEvents()
    {

        return array(
            'kernel.request' => 'onKernelRequest'
        );

    }
}