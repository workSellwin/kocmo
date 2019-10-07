<?php
use \Asdrubael\Utils;

require 'exchange_1C/load.php';

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
    $bx = new Utils\BxSection( $buildTree, 4 );
    $bx->createStruct();
    //echo '<pre>' . print_r($bx->getError(), true) . '</pre>';
}
elseif( $_GET['mode'] == "add_products" ){
    $buildTree = new Utils\treeHandler();
    //echo '<pre>' . print_r($buildTree, true) . '</pre>';
    //$bx = new Utils\BxProduct( $buildTree, 4 );
    //$bx->addProducts();
}



