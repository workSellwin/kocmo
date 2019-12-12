<?php


class Cookies
{

    /**
     * @param $name
     * @return mixed
     */
    static function getCookies($name)
    {
        return \Bitrix\Main\Context::getCurrent()->getRequest()->getCookie($name);

    }

    /**
     * @param $name
     * @param $value
     * @param bool $time
     * @param bool $path
     */
    static function setCookies($name, $value, $time = false, $path = false)
    {
        if (!$time) $time = time() + (3600 * 24 * 14);
        if (!$path) $path = '/';
        \Bitrix\Main\Context::getCurrent()->getResponse()->addCookie(
            new \Bitrix\Main\Web\Cookie($name, $value, $time)
        );
    }

    /**
     * @param $name
     * @param $value
     */
    static function delCookies($name)
    {
        \Bitrix\Main\Context::getCurrent()->getResponse()->addCookie(
            new \Bitrix\Main\Web\Cookie($name, 1, time() - 3600)
        );
    }
}