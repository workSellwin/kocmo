<?php


namespace Lui\Kocmo\Handler;


class BaseHandler
{
    public $module = '';

    protected function GetModule()
    {
        return $this->module;
    }
}
