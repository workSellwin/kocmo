<?php


namespace Lui\Kocmo\Helper;


use Lui\Kocmo\Data\MinData;

class Data
{
    use MinData;

    public static function Min(array $arData)
    {
        $ob = new self();
        return $ob->RemoveKeyT($arData);
    }
}
