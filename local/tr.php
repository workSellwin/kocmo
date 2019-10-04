<?php
use \Asdrubael\Utils;

require 'vendor/autoload.php';
require 'exchange_1C/treeHandler.php';
require 'exchange_1C/BxStructureCreator.php';


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
elseif( $_GET['mode'] == "add_products" ){
    $buildTree = new Utils\treeHandler();
    $bx = new Utils\BxStructureCreator( $buildTree, 5 );
    $bx->prepareProducts();
}
elseif( $_GET['mode'] == "add_enum" ){
    $buildTree = new Utils\treeHandler();
    $bx = new Utils\BxStructureCreator( $buildTree, 5 );
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




