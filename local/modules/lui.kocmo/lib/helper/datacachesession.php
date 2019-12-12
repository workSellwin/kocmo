<?php

namespace Lui\Kocmo\Helper;

trait  DataCacheSession
{
    /**
     * @return array|mixed
     */
    protected function GetDataCache()
    {
        return [];
        ///return isset($_SESSION[$this->GetSessionCode()]) ? $_SESSION[$this->GetSessionCode()] : [];
    }

    /**
     * @return string
     */
    abstract function GetSessionCode();


    protected function SetDataCache($data)
    {
        if (is_array($data)) {
            $_SESSION[$this->GetSessionCode()] = $data;
        }
    }
}
