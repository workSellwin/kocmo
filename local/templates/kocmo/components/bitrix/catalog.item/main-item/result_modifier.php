<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Lui\Kocmo\Catalog;

$elemPrepara = new Catalog\ElementPrepara($arResult);
$arImg = $arResult['ITEM']['PREVIEW_PICTURE'] ? $arResult['ITEM']['PREVIEW_PICTURE'] : $arResult['ITEM']['DETAIL_PICTURE'] ? $arResult['ITEM']['DETAIL_PICTURE'] : [];
$file_img = [];
if ($arImg) {
    $file_img = CFile::ResizeImageGet($arImg, array('width' => 290, 'height' => 226), BX_RESIZE_IMAGE_PROPORTIONAL, true);
}

$minPriceOffer = $elemPrepara->getMinPriceOffers();
$PROP = $elemPrepara->getProp();
$countOffers = $elemPrepara->getCauntOffers();
$prop = array_column($arResult['ITEM']['PROPERTIES'], '~VALUE', 'CODE');
$IS_BASKET = $arResult['ADD_BASKET'][$minPriceOffer['PRODUCT_ID']] || $arResult['ITEM']['OFFERS'] ? 'Y' : 'N';
$QUANTITY = $arResult['ITEM']['PRODUCT']['QUANTITY'];
$IS_OFFERS = $countOffers > 0 ? 'Y' : 'N';
$TEXT = 'В корзину';
if($IS_BASKET == 'Y'){
    $TEXT = 'Перейти в корзину';
}
if($IS_OFFERS == 'Y'){
    $TEXT = 'В карточку';
}

//unset($_SESSION['BASKET_PRODUCT_ADD']);


global $OBJ_ITEMS;
$OBJ_ITEMS['OBJ_ITEM'][$minPriceOffer['PRODUCT_ID']]['PRODUCT_ID'] = $minPriceOffer['PRODUCT_ID'];
$OBJ_ITEMS['OBJ_ITEM'][$minPriceOffer['PRODUCT_ID']]['file_img'] = $file_img;
$OBJ_ITEMS['OBJ_ITEM'][$minPriceOffer['PRODUCT_ID']]['minPriceOffer'] = $minPriceOffer;
$OBJ_ITEMS['OBJ_ITEM'][$minPriceOffer['PRODUCT_ID']]['PROP'] = $PROP;
$OBJ_ITEMS['OBJ_ITEM'][$minPriceOffer['PRODUCT_ID']]['countOffers'] = $countOffers;
$OBJ_ITEMS['OBJ_ITEM'][$minPriceOffer['PRODUCT_ID']]['prop'] = $prop;
$OBJ_ITEMS['OBJ_ITEM'][$minPriceOffer['PRODUCT_ID']]['IS_BASKET'] = $IS_BASKET;
$OBJ_ITEMS['OBJ_ITEM'][$minPriceOffer['PRODUCT_ID']]['IS_OFFERS'] = $IS_OFFERS;
$OBJ_ITEMS['OBJ_ITEM'][$minPriceOffer['PRODUCT_ID']]['QUANTITY'] = $QUANTITY;
$OBJ_ITEMS['OBJ_ITEM'][$minPriceOffer['PRODUCT_ID']]['IS_URL'] = $arResult['ITEM']['OFFERS'] || $arResult['QUANTITY'] <=0 ? $arResult['ITEM']['DETAIL_PAGE_URL'] : '/cart/' ;
$OBJ_ITEMS['OBJ_ITEM'][$minPriceOffer['PRODUCT_ID']]['URL_CART'] = '/cart/' ;
$OBJ_ITEMS['OBJ_ITEM'][$minPriceOffer['PRODUCT_ID']]['URL_DETAIL'] = $arResult['ITEM']['DETAIL_PAGE_URL'] ;
$OBJ_ITEMS['OBJ_ITEM'][$minPriceOffer['PRODUCT_ID']]['BTN_TEXT'] = $TEXT;


$arResult['PRODUCT_ID'] = $minPriceOffer['PRODUCT_ID'];
$arResult['file_img'] = $file_img;
$arResult['minPriceOffer'] = $minPriceOffer;
$arResult['PROP'] = $PROP;
$arResult['countOffers'] = $countOffers;
$arResult['prop'] = $prop;
$arResult['IS_BASKET'] = $IS_BASKET;
$arResult['IS_OFFERS'] = $IS_OFFERS;
$arResult['QUANTITY'] = $QUANTITY;
$arResult['IS_URL'] = $arResult['ITEM']['OFFERS'] || $arResult['QUANTITY'] <=0 ? $arResult['ITEM']['DETAIL_PAGE_URL'] : '/cart/' ;
$arResult['URL_CART'] ='/cart/' ;
$arResult['URL_DETAIL'] = $arResult['ITEM']['DETAIL_PAGE_URL'] ;



if($arResult['PROP']['MARKA']){
    $arSelect = Array("ID", "NAME", "CODE" , "DETAIL_PAGE_URL");
    $arFilter = Array("IBLOCK_ID"=>7, 'NAME' => trim($arResult['PROP']['MARKA']));
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNext())
    {
        $arResult['MARKA_BRAND'] = $ob;
    }
}

if($_REQUEST['BASKET_PRODUCT_ADD']){
    $arResult['ADD_BASKET'] = $_REQUEST['BASKET_PRODUCT_ADD'];
}