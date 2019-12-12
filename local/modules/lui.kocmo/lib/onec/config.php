<?php

namespace Lui\Kocmo\OneC;

class Config
{
    protected $url = [];

    public function __construct($arConf = [])
    {
    }

    /**
     * @param $type
     * @param $url
     * @return bool
     */
    public function SetUrl($type, $url)
    {
        $result = false;
        if (!isset($this->url[$type])) {
            $result = true;
            $this->url[$type] = $url;
        }
        return $result;
    }

    /**
     * @return array
     */
    public function GetAllUrl()
    {
        return $this->url;
    }

    /**
     * @param $type
     * @return mixed|strings
     */
    public function GetUrl($type)
    {
        return isset($this->url[$type]) ? $this->url[$type] : '';
    }

    /**
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }
}
