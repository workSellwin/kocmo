<?php
use \Asdrubael\Utils;

require 'exchange_1C/load.php';
define('CATALOG_ID', 5);

if( $_GET['mode'] == "get_sections" ) {
    $tree = new Utils\treeHandler();
    echo '<pre>' . print_r($tree->getTree(), true) . '</pre>';
}
elseif( $_GET['mode'] == "get_all_xmlid" ){
    $tree = new Utils\treeHandler();
    echo '<pre>' . print_r($tree->getAllXmlId(), true) . '</pre>';
}
elseif( $_GET['mode'] == "create_structure" ){
    $tree = new Utils\treeHandler();
    $bx = new Utils\BxSection( $tree, CATALOG_ID );
    $bx->createStruct();
    //echo '<pre>' . print_r($bx->getError(), true) . '</pre>';
}
elseif( $_GET['mode'] == "add_products" ){
    echo 'start: ', date("h:i:s"), "<br>";
    //echo '<pre>' . print_r($_SESSION['offset'], true) . '</pre>';die();
    $tree = new Utils\treeHandler();

    $bx = new Utils\BxProduct( $tree, CATALOG_ID );
    $bx->addProducts();
    echo 'finish: ', date("h:i:s"), "<br>";
    echo $_SESSION['offset'];
}
elseif( $_GET['mode'] == "save_image" ){

    $tree = new Utils\treeImage();
    //$bxImage = new Utils\BxImage($tree, CATALOG_ID);
    //$bxImage->upload();
}


