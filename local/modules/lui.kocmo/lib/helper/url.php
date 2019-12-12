<?php


namespace Lui\Kocmo\Helper;

/**
 * Class Url
 * @package Lui\Kocmo\Helper
 */
class Url
{

    /**
     * @param $url
     * @return bool
     */
    public static function Not(string $url): bool
    {
        return !self::Is($url);
    }

    /**
     * @param $url
     * @return bool
     */
    public static function Is(string $url): bool
    {
        global $APPLICATION;
        $pageUrl = $APPLICATION->GetCurPage(false);
        return $pageUrl == $url;
    }


}
