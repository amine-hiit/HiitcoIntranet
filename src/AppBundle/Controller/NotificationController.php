<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
use AppBundle\Manager\NotificationManager;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class NotificationController extends Controller
{

    /**
     * @Route("/intranet/see-notification", name="see-notification")
     * @return Response
     */
    public function seeNotificationAction(Request $request)
    {
        $notificationId = $request->request->get('id');
        $user = $this->getUser();
        //dump($notificationId);dump($user->getId());die;
        $this->getNotificationManager()->setSeenNotification($notificationId, $user);
        return new JsonResponse('done');
    }

    /**
     * @Route("/intranet/last-teen-notifications", name="last-teen-notifications")
     * @return Response
     */
    public function jsonNotificationsAction()
    {
        $nm = $this->getNotificationManager();
        $user = $this->getUser();
        $unseenNotifNumber = $nm->countUnseenByEmployee($user);

        $lastTeenNotifications = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:EmployeeNotification')->findLastTeenByEmployee($user);

        $serializer = SerializerBuilder::create()->build();

        return new JsonResponse($serializer->serialize(
            $lastTeenNotifications, 'json',
            SerializationContext::create()->setGroups(array('notification'))
        ));
    }

    /**
     * @Route("intranet/unseen-notifications", name="unseen-notifications")
     * @return Response
     */
    public function unseenNotificationsAction()
    {
        $nm = $this->getNotificationManager();
        $user = $this->getUser();
        $response = $nm->countUnseenByEmployee($user);
        $data = array(
            'unseen_notification_number' => $response,
        );
        return new Response(json_encode($data));
    }

    /**
     * @return NotificationManager
     */
    private function getNotificationManager()
    {
        return $this->get('app.notification.manager');
    }
}


