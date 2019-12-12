<?php


namespace Lui\Kocmo\Data;

/**
 * Trait MinData
 * @package Lui\Kocmo\Data
 */
trait MinData
{
    public function RemoveKeyT(array $arData): array
    {
        $arData = array_filter($arData, function ($k) {
            return strrpos($k, '~') === false ? true : false;
        }, ARRAY_FILTER_USE_KEY);
        return array_diff($arData, ['']);
    }
}
