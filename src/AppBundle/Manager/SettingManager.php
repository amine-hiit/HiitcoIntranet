<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 8/2/18
 * Time: 09:17
 */

namespace AppBundle\Manager;

use Craue\ConfigBundle\Util\Config;

class SettingManager
{

    /**
     * @var Config
     */
    private $craue;

    /**
     * SettingManager constructor.
     * @param Config $craue
     */
    public function __construct(Config $craue)
    {
        $this->craue = $craue;
    }

    public function getAll()
    {
        $parameters = $this->craue->all();
        if (is_array($parameters)) {
            foreach ($parameters as $parameter => $value) {
                try{
                    $parameters[$parameter] = unserialize($value);
                    } catch (\Exception $e) {
                    }
            }
        }
        return $parameters;
    }

    public function unserialize($string)
    {
                try{
                    $array = unserialize($string);
                    } catch (\Exception $e) {
                    $array = $string;
                    }
        return $array;
    }

    public function get($parameter)
    {
        $value = $this->craue->get($parameter);
        try{
                    $array = unserialize($value);
                    } catch (\Exception $e) {
                    $array = $value;
                    }
        return $array;
    }
}

