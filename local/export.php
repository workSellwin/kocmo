<?php
//https://documenter.getpostman.com/view/155604/SVtZwmvs?version=latest
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('kocmo.exchange');

use Bitrix\Main\Context,
    Kocmo\Exchange,
    Kocmo\Exchange\Bx;

$request = Context::getCurrent()->getRequest();
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
    $bx = new Exchange\Bx\Section();
    if( $bx->update() ){
        header('Location: ' . $uri . '?step=10');
        exit;
    }
}
elseif($step == 10){
    $bx = new Bx\Property();

    if( $bx->update() ){
        header('Location: ' . $uri . '?step=20');
        exit;
    }
}
elseif($step == 20){
    $bx = new Bx\Dbproduct();
    $result = $bx->update();

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
}
elseif($step == 30) {//offers

//    $bx = new Bx\Offer();
//    $result = $bx->addProductsInDb();
//
//    if( $result === true || true){
        header('Location: ' . $uri . '?step=40');
        exit;
//    }
//    elseif(is_string($result)){
//        sleep(1);
//        header('Location: ' . $uri . '?step=30&item=' . $result);
//        exit;
//    }
}
elseif($step == 40){

    $bx = new Bx\Product();
    $result = $bx->update();

    if( $result === true){
        header('Location: ' . $uri . '?step=50');
        exit;
    }
    else{
        sleep(1);
        header('Location: ' . $uri . '?step=40');
        exit;
    }
}
elseif($step == 50) {//store
    die('end');
    $bx = new Bx\Store();
    if( $bx->update() ) {
        header('Location: ' . $uri . '?step=60');
        exit;
    }
}
elseif($step == 60) {//rest
    $bx = new Bx\Rest();
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
    $bx = new Bx\Typeprice();
    if( $bx->update() ) {
        header('Location: ' . $uri . '?step=80');
        exit;
    }
}
elseif($step == 80) {//price
    $bx = new Bx\Price();
    if( $bx->update() ) {
        header('Location: ' . $uri . '?step=90');
        exit;
    }
}
//elseif($step == 90){
//    $bx = new Bx\Image();
//
//    if( $bx->updateDetailPictures() ){
//    }
//}
else{
    die('die');
}