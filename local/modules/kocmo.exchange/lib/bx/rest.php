<?php


namespace Kocmo\Exchange\Bx;


class Rest extends Helper
{

    protected $stores = [];
    protected $products = [];

    function __construct($catalogId)
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Rest();
        parent::__construct($treeBuilder, $catalogId);
    }

    public function update()
    {
        $arReq = $this->treeBuilder->getRequestArr();//product xml_id => store xml_id => count
        $this->stores = $this->getStore();
        $arUid = array_keys($arReq);
        $this->products = $this->getProductId($arUid);
        $rests = $this->getRest();

        foreach ($this->products as $id => $xml_id) {

            if( isset($arReq[$xml_id]) ){

                foreach($arReq[$xml_id] as $storeXmlId => $amount ){

                    try {
                        if ( isset($rests[$xml_id]) && isset($rests[$xml_id][$storeXmlId])) {

                            $restId = $rests[$xml_id][$storeXmlId];

                            $result = \Bitrix\Catalog\StoreProductTable::update($restId, [
                                "PRODUCT_ID" => $id,
                                "AMOUNT" => $amount,
                                "STORE_ID" => array_search($storeXmlId, $this->stores)
                            ]);
                        }
                        else{
                            $result = \Bitrix\Catalog\StoreProductTable::add([
                                "PRODUCT_ID" => $id,
                                "AMOUNT" => $amount,
                                "STORE_ID" => array_search($storeXmlId, $this->stores)
                            ]);
                        }

                    } catch(\Bitrix\Main\DB\SqlQueryException $e){
                        //уже есть
                    } catch(\Exception $e){
                        //
                    }

                }
            }

        }
//        foreach ($arReq as $store) {
//            if (!isset($stores[$store [$this->arParams['ID']]])) {
//
//                try {
//                    $w = \Bitrix\Catalog\StoreTable::add([
//                        "TITLE" => $store[$this->arParams['NAME']],
//                        "CODE" => $this->getCode($store [$this->arParams['NAME']]),
//                        "XML_ID" => $store[$this->arParams['ID']],
//                        "ADDRESS" => $store[$this->arParams['ADDRESS']],
//                    ]);
//                    //pr($w->getErrors());
//                } catch (\Exception $e) {
//                    pr($e->getMessage());
//                }
//            }
//        }
    }

    private function getStore(){

        $stores = [];

        try {
            $stores = \Bitrix\Catalog\StoreTable::getlist([])->fetchAll();
            $stores = array_column($stores, "XML_ID", "ID");
        } catch (\Exception $e){
            //
        }

        return $stores;
    }

    private function getProductId(array $arUid){

        $res = \CIblockElement::GetList([], ["XML_ID" => $arUid], false, false, ['XML_ID', 'ID']);
        $products = [];

        while( $fields = $res->fetch() ){
            $products[$fields['ID']] = $fields['XML_ID'];
        }
        return $products;
    }

    private function getRest(){

        $storeProducts = [];

        try {
            $iterator = \Bitrix\Catalog\StoreProductTable::getlist([]);

            while($row = $iterator->fetch()){
                $productXmlId = $this->products[ $row['PRODUCT_ID'] ];
                $storeXmlId = $this->stores[$row['STORE_ID']];
                $storeProducts[$productXmlId][$storeXmlId] = $row['ID'];
            }
        } catch (\Exception $e){
            //
        }
        return $storeProducts;
    }
}