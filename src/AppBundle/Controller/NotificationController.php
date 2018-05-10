<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
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
     * @Route("/intranet/yaml", name="yaml_test")
     * @return Response
     */
    public function testYamlAction()
    {
        $yaml = Yaml::parseFile($this->get('kernel')->getRootDir().'/../src/AppBundle/Resources/notification/message.yml');
        $message = vsprintf($yaml['vacation_request'],array('1','2'));
        echo $message;die;
        return new Response();
    }

    /**
     * @Route("/intranet/get-notif", name="last-teen-notifications")
     * @return Response
     */
    public function jsonNotificationsAction()
    {
        $directeur = $this->getDoctrine()->getRepository('AppBundle:Employee')->find(6);
        $unseenNotifiNumber = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:EmployeeNotification')
            ->countUnseenByEmployee($directeur);

        $lastTeenNotifications = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:EmployeeNotification')->findLastTeenByEmployee($directeur);

        $data = array(
            'unseen_notification_number' => $unseenNotifiNumber,
            'all_notifications' => $lastTeenNotifications,
        );
        $serializer = SerializerBuilder::create()->build();
        return new JsonResponse($serializer->serialize($data, 'json', SerializationContext::create()->setGroups(array('notification'))));
    }

    /**
     * @Route("intranet/unseen-notifications", name="unseen-notifications")
     * @return Response
     */
    public function unseenNotificationsAction()
    {
        $directeur = $this->getDoctrine()->getRepository('AppBundle:Employee')->find(6);
        $response = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:EmployeeNotification')
            ->countUnseenByEmployee($directeur);
        $data = array(
            'unseen_notification' => $response,
        );

        return new Response(json_encode($data));
    }

}


