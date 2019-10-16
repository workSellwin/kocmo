<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('kocmo.exchange');
define('CATALOG_ID', 7);

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
//        $client = new \GuzzleHttp\Client();
//        $response = $client->request('GET', $uri . '?step=40');
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