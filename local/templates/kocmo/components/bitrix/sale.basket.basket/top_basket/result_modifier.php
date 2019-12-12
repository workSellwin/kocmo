<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */

/** @var array $arResult */

use Bitrix\Main;

$count = 0;
$arItems = [];
if ($arResult['GRID']['ROWS']) {
    $count = count($arResult['GRID']['ROWS']);
    $arItems = $arResult['GRID']['ROWS'];
}

$arData = [
    'COUNT' => $count,
    'ITEMS' => $arItems,
    'SUM' => $arResult['allSum'],
];
$arResult= $arData;
