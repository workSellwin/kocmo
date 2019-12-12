<?php


namespace Lui\Kocmo;


class PropertyPage
{
    protected $APP;

    public function __construct()
    {
        global $APPLICATION;
        if (is_object($APPLICATION)) {
            $this->APP = $APPLICATION;
        } else {
            throw  new \Exception('No CMain');
        }
    }

    public function isView($str)
    {
        return $this->APP->GetProperty($str) == 'Y' ? true : false;
    }

    public function getProp($str)
    {
        return $this->APP->GetProperty($str);
    }

    public function isViewThis($str)
    {
        return $this->APP->GetPageProperty($str);
    }
}
