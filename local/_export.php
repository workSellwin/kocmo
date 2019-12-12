<?php
//https://documenter.getpostman.com/view/155604/SVtZwmvs?version=latest
/*
 * Схема свойств
 * http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetScheme/22e8d9ce-ed52-47ca-a524-e32b586aab0a
 *
 * Группы / Разделы
 * http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetSections
 *
 *  Каталог Товаров
 * http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetCatalog
 *
 * Предложения
 *http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetOffers
 *
 * Справочники
 * http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetReference/42d10805-9ccb-11e8-a215-00505601048d
 *
 */
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('kocmo.exchange');
define('CATALOG_ID', 2);

use Kocmo\Exchange;
use Kocmo\Exchange\Bx;

//require $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';
$uri = $_SERVER['SCRIPT_URI'];

if( empty($_GET['step']) ){
    $step = 0;
}
else{
    $step = $_GET['step'];
}
/**
 * $_SESSION['step']
 * 0 - create/update structure
 * 10 - add/update properties
 * 20 - update table kocmo_exchange_data
 * 30 - add/update products from table kocmo_exchange_data and fill in table kocmo_exchange_product_image
 * 40 - add detail image for products from table kocmo_exchange_product_image
 */

if($step == 0){
    $bx = new Exchange\Bx\Section(CATALOG_ID);
    if( $bx->createStruct() ){
        //sleep(2);
        header('Location: ' . $uri . '?step=10');
        exit;
//        $client = new \GuzzleHttp\Client();
//        $response = $client->request('GET', $uri . '?step=10');
    }
}
elseif($step == 10){
    $bx = new Bx\Property(CATALOG_ID);

    if( $bx->updateProperty() ){
        //sleep(2);
        header('Location: ' . $uri . '?step=20');
        exit;
//        $client = new \GuzzleHttp\Client();
//        $response = $client->request('GET', $uri . '?step=20');
    }
}
elseif($step == 20){
    $bx = new Bx\Product(CATALOG_ID);

    if( $bx->addProductsInDb() ){
        header('Location: ' . $uri . '?step=30');
        exit;
//        $client = new \GuzzleHttp\Client();
//        $response = $client->request('GET', $uri . '?step=30');
    }
}
elseif($step == 30){
    $bx = new Bx\Product(CATALOG_ID);

    if( $bx->addProductsFromDb() ){
        header('Location: ' . $uri . '?step=40');
        exit;
    }
}
elseif($step == 40){
    $bx = new Bx\Image(CATALOG_ID);

    if( $bx->updateDetailPictures() ){
//        $client = new \GuzzleHttp\Client();
//        $response = $client->request('GET', $uri . '?step=50');
    }
}
else{
    die('die');
}
