<?php


namespace Kocmo\Exchange;
use Kocmo\Exchange\Bx,
    Kocmo\Exchange\Tree;
use mysql_xdevapi\Exception;


class Entityupdate
{

    protected $arProduct = false;
    protected $productId = 0;

    function __construct($productId, $params = [])
    {
        try{
            \Bitrix\Main\Loader::includeModule('iblock');
            \Bitrix\Main\Loader::includeModule('catalog');
        } catch (\Exception $e){
            throw new \Exception("module 'iblock' or 'catalog' not found!");
        }

        try {

            if( $this->getProductJson($productId) ) {
                $this->updateProduct();
                //print_r($this->arProduct);
            }

        } catch (\Exception $e){
            //
        }
    }

    public function getProductId(){
        return $this->productId;
    }

    public function isSuccess(){
        if(intval($this->productId) > 0){
            return true;
        }
        return false;
    }

    protected function getProductJson( $productId ){

        $arFields = \Bitrix\Iblock\ElementTable::getList([
            'filter' => ['ID' => $productId],
            'select' => ['ID', 'XML_ID']
        ])->fetch();
        //echo '<pre>', print_r($arFields, true), '</pre>';
        if( !empty($arFields['XML_ID'])) {

            $treeBuilder = new Tree\Product([
                'PRODUCT_LIMIT' => 1,
                'UID' => $arFields['XML_ID']
            ]);

            $arProducts = $treeBuilder->getProductsFromReq();
            //echo '<pre>', print_r($arProducts, true), '</pre>';
            $arProduct = current($arProducts);

            $this->arProduct = $arProduct;
            return true;
        }
        else{
            return false;
        }
    }

    protected function updateProduct(){

        if(!$this->arProduct || empty($this->arProduct)){
            throw new \Exception("arProduct empty");
        }

        $o = new Bx\Product();
        $this->productId = $o->updateOne($this->arProduct);
    }
}