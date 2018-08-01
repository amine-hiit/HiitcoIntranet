<?php
/**
 * Created by PhpStorm.
 * User: younes
 * Date: 6/10/18
 * Time: 2:47 PM
 */

namespace AppBundle\Controller;


trait ControllerTrait
{
    protected function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->get('translator')->trans($id,  $parameters , $domain , $locale );
    }
    protected function log($level, $message, $context = [])
    {
        return $this->get('logger')->log($level, $message, $context );
    }

}