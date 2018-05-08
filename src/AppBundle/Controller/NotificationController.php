<?php

namespace AppBundle\Controller;

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
        echo $message;
        return new Response('');
    }

}
