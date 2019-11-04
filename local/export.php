<?php
//https://documenter.getpostman.com/view/155604/SVtZwmvs?version=latest
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('kocmo.exchange');
define('CATALOG_ID', 6);
define('PRODUCT_PART', 500);

use Bitrix\Main\Context;
use Kocmo\Exchange;
use Kocmo\Exchange\Bx;

$request = Bitrix\Main\Context::getCurrent()->getRequest();
$uri = $request->getRequestedPage();

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
    if( $bx->update() ){
        header('Location: ' . $uri . '?step=10');
        exit;
    }
}
elseif($step == 10){
    $bx = new Bx\Property(CATALOG_ID);

    if( $bx->update() ){
        header('Location: ' . $uri . '?step=20');
        exit;
    }
}
elseif($step == 20){
    $bx = new Bx\Product(CATALOG_ID);
    $result = $bx->addProductsInDb();

//    if(count($bx->getErrors())){
//        pr($bx->getErrors());
//        die();
//    }

    if( $result === true || true){
        header('Location: ' . $uri . '?step=30');
        exit;
    }
    elseif(is_string($result)){
        sleep(1);
        header('Location: ' . $uri . '?step=20&item=' . $result);
        exit;
    }
    else{
        die("On $step - error");
    }
}
elseif($step == 30) {//offers

    $bx = new Bx\Offer(CATALOG_ID);
    $result = $bx->addProductsInDb();

    if( $result === true || true){
        header('Location: ' . $uri . '?step=40');
        exit;
    }
    elseif(is_string($result)){
        sleep(1);
        header('Location: ' . $uri . '?step=30&item=' . $result);
        exit;
    }
    else{
        die("On $step - error");
    }
}
elseif($step == 40){

    $bx = new Bx\Product(CATALOG_ID);
    $result = $bx->addProductsFromDb();

    if( $result === true || true){
        header('Location: ' . $uri . '?step=50');
        exit;
    }
    elseif(is_string($result)){
        header('Location: ' . $uri . '?step=40&item=' . $result . "&count=500");
        exit;
    }
    else{
        die("On $step - error");
    }
}
elseif($step == 50) {//store
    $bx = new Bx\Store(CATALOG_ID);
    if( $bx->update() ) {
        header('Location: ' . $uri . '?step=60');
        exit;
    }
}
elseif($step == 60) {//rest
    $bx = new Bx\Rest(CATALOG_ID);
    if( $bx->update() ) {
        header('Location: ' . $uri . '?step=60');
        exit;
    }
    else{
        header('Location: ' . $uri . '?step=70');
        exit;
    }
}
elseif($step == 70) {//price type
    $bx = new Bx\Typeprice(CATALOG_ID);
    if( $bx->update() ) {
        header('Location: ' . $uri . '?step=80');
        exit;
    }
}
elseif($step == 80) {//price
    $bx = new Bx\Price(CATALOG_ID);
    if( $bx->update() ) {
        header('Location: ' . $uri . '?step=90');
        exit;
    }
}
//elseif($step == 90){
//    $bx = new Bx\Image(CATALOG_ID);
//
//    if( $bx->updateDetailPictures() ){
//    }
//}
else{
    die('die');
}