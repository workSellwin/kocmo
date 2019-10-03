<?php
use \Asdrubael\Utils;

require 'vendor/autoload.php';
require 'exchange_1C/treeHandler.php';
require 'exchange_1C/BxStructureCreator.php';

//'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetFolder/GoodsItems?group=eb7b39f0-aaaa-11e8-a216-00505601048d';
//$points = [
//    "sections" => 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetFolder/GoodsOnlyGroup',
//    "goods" => 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetFolder/GoodsItems',
//];
//
//$allowedGetParams = [
//    'group'
//];
//
//$getParamsStr = "";
//
//foreach( $_GET as $key => $param){
//    if( in_array($key, $allowedGetParams) ){
//        $getParamsStr .= $key . '=' . $param . '&';
//    }
//}
//
//if( $_GET['mode'] == "get_sections" || !isset($_GET['mode']) ) {
//    $uri = $points['sections'];
//}
//if( $_GET['mode'] == "get_struct_sections" ) {
//    $uri = $points['sections'];
//}
//elseif( $_GET['mode'] == "get_goods" ){
//    $uri = $points['goods'];
//}
//elseif( $_GET['mode'] == "create_structure" ){
//    $uri = $points['sections'];
//}
//
//if( empty($uri) ){
//    echo "URL not defined";
//    die();
//}
//$client = new \GuzzleHttp\Client();
//$response = $client->request('GET', $uri . '?' . $getParamsStr);
//
//if($response->getStatusCode() == 200){
//    $outArr = json_decode($response->getBody(), true);
//}
//else{
//    echo "error: status: " . $response->getStatusCode();
//    die();
//}

if( $_GET['mode'] == "get_sections" ) {
    $buildTree = new Utils\treeHandler();
    echo '<pre>' . print_r($buildTree->getTree(), true) . '</pre>';
}
elseif( $_GET['mode'] == "get_all_xmlid" ){
    $buildTree = new Utils\treeHandler();
    echo '<pre>' . print_r($buildTree->getAllXmlId(), true) . '</pre>';
}
elseif( $_GET['mode'] == "create_structure" ){
    $buildTree = new Utils\treeHandler();
    $bx = new Utils\BxStructureCreator( $buildTree, 6 );
    //echo '<pre>' . print_r($bx->getTree(), true) . '</pre>';
    $bx->createStruct();
    echo '<pre>' . print_r($bx->getError(), true) . '</pre>';
}
//elseif( $_GET['mode'] == "get_struct_sections" ){
//    $buildTree = new Utils\treeHandler();
//    $buildTree->createTree();
//    //$buildTree->createStruct();
//}
//elseif( $_GET['mode'] == "get_goods" )
//{
//    echo $response->getBody();
//}




