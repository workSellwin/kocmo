<?

use Lui\Kocmo\Data\IblockSection;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


foreach ($arResult as &$arItem) {
    if ($arItem['LINK'] == '/about/news/') {
        $ob = new IblockSection();
        $arSub = $ob->GetData(['ACTIVE' => 'Y', 'IBLoCK_ID' => 1]);
        $arItem['IS_PARENT'] = 1;
        $arItem['SUB'] = $arSub;

    }
}

if (empty($arResult))
    return;
