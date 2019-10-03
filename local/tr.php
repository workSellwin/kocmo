<?php
use \Asdrubael\Utils;

require 'vendor/autoload.php';
require 'exchange_1C/buildTree.php';
require 'exchange_1C/BxStructureCreator.php';
//"exchange_1C/buildTree.php";
'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetFolder/GoodsItems?group=eb7b39f0-aaaa-11e8-a216-00505601048d';
$points = [
    "sections" => 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetFolder/GoodsOnlyGroup',
    "goods" => 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetFolder/GoodsItems',
];

$allowedGetParams = [
    'group'
];

$getParamsStr = "";

foreach( $_GET as $key => $param){
    if( in_array($key, $allowedGetParams) ){
        $getParamsStr .= $key . '=' . $param . '&';
    }
}

if( $_GET['mode'] == "get_sections" || !isset($_GET['mode']) ) {
    $uri = $points['sections'];
}
elseif( $_GET['mode'] == "get_goods" ){
    $uri = $points['goods'];
}
elseif( $_GET['mode'] == "create_structure" ){
    $uri = $points['sections'];
}

if( empty($uri) ){
    echo "URL not defined";
    die();
}
$client = new \GuzzleHttp\Client();
$response = $client->request('GET', $uri . '?' . $getParamsStr);

if($response->getStatusCode() == 200){
    $outArr = json_decode($response->getBody(), true);
}
else{
    echo "error: status: " . $response->getStatusCode();
    die();
}

if( $_GET['mode'] == "get_sections" || !isset($_GET['mode']) ) {
    $buildTree = new Utils\BuildTree($outArr);
    $buildTree->createTree();
    echo '<pre>' . print_r($buildTree->getTree(), true) . '</pre>';
}
elseif( $_GET['mode'] == "get_goods" )
{
    echo $response->getBody();
}
elseif( $_GET['mode'] == "create_structure" ){
    $buildTree = new Utils\BuildTree($outArr);
    $buildTree->createTree();
    $bx = new Utils\BxStructureCreater( $buildTree->getTree(), 5 );
    //echo '<pre>' . print_r($bx->getTree(), true) . '</pre>';
    $bx->createStruct();
}



