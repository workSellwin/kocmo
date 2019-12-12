<?php

namespace Lui\Kocmo\Request\Config;

class Url
{
    /**
     * @var string
     */
    protected static $baseUrl = 'http://kocmo1c.sellwin.by/Kosmo/hs/Kocmo/';
    /**
     * @var array
     */
    protected static $arRule = [
        'Base' => [
            'GetBasket',
            'GetCertificate',
            'GetActions',
            'SetOrder',
            'SetPayment',
            'GetNumberEGifts',
        ],
        'All' => [
            'GetActionsAll' => 'http://kocmo1c.sellwin.by/Kosmo/hs/Kocmo/All'
        ],
    ];

    /**
     * @param $name
     * @param $arguments
     * @return string
     */
    public static function __callStatic($name, $arguments)
    {
        $url = '';
        if (in_array($name, self::$arRule['Base'])) {
            $url = self::$baseUrl . $name;
        } elseif (in_array($name, array_keys(self::$arRule['All']))) {
            $url = self::$arRule[$name];
        }
        return $url;

    }

}
