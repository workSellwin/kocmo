<?php

namespace Lui\Kocmo\Action;

class Factory
{
    public static function GetOb($action)
    {
        $ob = null;
        $name = "Lui\Kocmo\Action\\" . $action;
        if (class_exists($name)) {
            $ob = new $name;
        }
        return $ob;
    }
}
