<?php
use \Asdrubael\Utils;

require 'exchange_1C/load.php';
define('CATALOG_ID', 5);

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
    $bx = new Utils\BxSection( $buildTree, CATALOG_ID );
    $bx->createStruct();
    //echo '<pre>' . print_r($bx->getError(), true) . '</pre>';
}
elseif( $_GET['mode'] == "add_products" ){
    echo 'start: ', date("h:i:s"), "<br>";
    //echo '<pre>' . print_r($_SESSION['offset'], true) . '</pre>';die();
    $buildTree = new Utils\treeHandler();

    $bx = new Utils\BxProduct( $buildTree, CATALOG_ID );
    $bx->addProducts();
    echo 'finish: ', date("h:i:s"), "<br>";
    echo $_SESSION['offset'];
}



