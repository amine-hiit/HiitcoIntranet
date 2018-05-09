<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/intranet/getNotif", name="last-teen-notifications")
     * @return Response
     */
    public function jsonNotificationsAction()
    {
        $directeur = $this->getDoctrine()->getRepository('AppBundle:Employee')->find(15);
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
        dump($serializer->serialize($data, 'json'));die;
        return $this->json($serializer->serialize($data, 'json'));
        return new Response($data);
    }

    /**
     * @Route("intranet/unseen-notifications", name="unseen-notifications")
     * @return Response
     */
    public function unseenNotificationsAction()
    {
        $directeur = $this->getDoctrine()->getRepository('AppBundle:Employee')->find(15);
        $response = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:EmployeeNotification')
            ->countUnseenByEmployee($directeur);
        $data = array(
            'unseen_notification' => $response,
        );

        return new Response(json_encode($data));
    }

}
