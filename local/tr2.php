<?php
use \Asdrubael\Utils;

require 'exchange_1C/load.php';
define('CATALOG_ID', 6);

if( $_GET['mode'] == "get_sections" ) {
    $tree = new Utils\treeSection();
    echo '<pre>' . print_r($tree->getTree(), true) . '</pre>';
}
elseif( $_GET['mode'] == "get_all_xmlid" ){
    $tree = new Utils\treeSection();
    echo '<pre>' . print_r($tree->getAllXmlId(), true) . '</pre>';
}
elseif( $_GET['mode'] == "create_structure" ){
    $tree = new Utils\treeSection();
    $bx = new Utils\BxSection( $tree, CATALOG_ID );
    $bx->createStruct();
    //echo '<pre>' . print_r($bx->getError(), true) . '</pre>';
}
elseif( $_GET['mode'] == "add_products" ){
    echo 'start: ', date("h:i:s"), "<br>";

    $tree = new Utils\treeProduct();
    $bx = new Utils\BxProduct( $tree, CATALOG_ID );
    $bx->addProducts();
    echo 'finish: ', date("h:i:s"), "<br>";
}
elseif( $_GET['mode'] == "update_props" ){
    $tree = new Utils\treeProperty();
    $bx = new Utils\BxProperty( $tree, CATALOG_ID );
    //echo '<pre>' . print_r($bx, true) . '</pre>';
    $bx->updateProperty();
}
//elseif( $_GET['mode'] == "save_image" ){
//    echo 'start: ', date("h:i:s"), "<br>";
//    $tree = new Utils\treeImage();
//
//    $bxImage = new Utils\BxImage( $tree, CATALOG_ID );
//    $bxImage->upload();
//    echo 'finish: ', date("h:i:s"), "<br>";
//}

//
//use Kocmo\Exchange;
//
//$_GET['group'] = 'c7406c56-8768-11e9-a245-00505601048d';
//
//\Bitrix\Main\Loader::includeModule('kocmo.exchange');
//
//$bx = new Exchange\Bx\Product(7);
//$bx->addProductsFromDb();

///////////
/// \Bitrix\Main\Loader::includeModule('iblock');
//
//$entity = \Bitrix\Iblock\Model\Section::compileEntityByIblock(7);
//$iterator = $entity::getList(["filter" => ["IBLOCK_ID" => 7], "select" => ["XML_ID", "ID"]]);
//$sections = [];
//while($row = $iterator->fetch() ){
//	$sections[$row['XML_ID']] = $row['ID'];
//}
//pr(count($sections));



