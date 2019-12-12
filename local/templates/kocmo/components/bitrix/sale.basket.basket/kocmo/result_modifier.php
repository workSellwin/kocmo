<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */

/** @var array $arResult */

use Bitrix\Sale;
use Lui\Kocmo\Data\IblockElement;
use Lui\Kocmo\Data\Product;
use Lui\Kocmo\Request\Basket;

$count = 0;
$arItems = [];
if ($arResult['GRID']['ROWS']) {
    $count = count($arResult['GRID']['ROWS']);
    $arItems = $arResult['GRID']['ROWS'];
}

$ob = new Basket();
$ar1c = $ob->Run();
$arItemsNew = [];

foreach ($arItems as $arItem) {
    $obProd = new \Lui\Kocmo\Product($arItem);
    $arItem = $obProd->GetDataBasketMain();
    $arItemsNew[$arItem['PRODUCT_XML_ID']] = $arItem;
}

if ($ar1c['goods']) {
    $ar1c['goods'] = array_column($ar1c['goods'], null, 'UID');
}

foreach ($arItemsNew as $key => &$arItem) {
    $ar1cItem = $ar1c['goods'][$key];
    $arItem['DISCOUNT'] = $ar1cItem['DISCOUNT'];
    if ($arItem['DISCOUNT']) {
        $count = $ar1cItem['COUNT'];
        $price = $ar1cItem['PRICE'];
        $sum = $ar1cItem['SUMM'];
        $allDiscount = 0;
        foreach ($arItem['DISCOUNT'] as &$arDiscount) {
            $allDiscount += $arDiscount['VALUE'];
            $arDiscount['PERCENT'] = round($arDiscount['VALUE'] / $sum, 2) * 100;
        }
        $priceDiscountOne = round($allDiscount / $count, 2);
        $arPriceOld = $price;
        $arPriceNew = $price - $priceDiscountOne;
        $arItem['PRICE_OLD'] = $arPriceOld;
        $arItem['PRICE_NEW'] = $arPriceNew;
    } elseif ($ar1cItem) {
        $arItem['PRICE_NEW'] = $ar1cItem['PRICE'];
    }
}

// Обновим цены в корзине из 1с
$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
foreach ($basket as $basketItem) {
    $basketPropertyCollection = $basketItem->getPropertyCollection();
    $arProp = $basketPropertyCollection->getPropertyValues();
    $xmlId = $arProp['PRODUCT.XML_ID']['VALUE'];
    if ($dt = $arItemsNew[$xmlId]) {
        $price = $dt['PRICE_NEW'];
        $basketItem->setFields([
            'PRICE' => $price,
            'CUSTOM_PRICE' => 'Y',
            'IGNORE_CALLBACK_FUNC' => 'Y',
            'PRODUCT_PROVIDER_CLASS' => '',
        ]);
    }
}
$basket->save();


$arGift = [];
if ($ar1c['GIFT']) {
    $sIdGift = $ar1c['GIFT'][0]['UID'];
    $obGift = new  IblockElement();
    $arData = $obGift->GetXmlID($sIdGift);
    if ($arData) {
        $arGift = reset($arData);
    }
}

$arData = [
    'COUNT' => $count,
    'ITEMS' => $arItemsNew,
    'GIFT' => $arGift,
    'SUM' => $arResult['allSum'],
];

$arResult = $arData;
