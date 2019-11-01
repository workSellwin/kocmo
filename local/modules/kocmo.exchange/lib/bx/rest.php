<?php


namespace Kocmo\Exchange\Bx;


class Rest extends Helper
{

    protected $stores = [];
    protected $products = [];
    protected $storeXmlId = false;

    function __construct($catalogId, $storeXmlId)
    {
        \Bitrix\Main\Loader::includeModule('catalog');
        $this->stores = $this->getStores();

        if( !empty($storeXmlId) && $this->checkRef($storeXmlId) && in_array($storeXmlId, $this->stores) ){
            $this->storeXmlId = $storeXmlId;
        }

        $treeBuilder = new \Kocmo\Exchange\Tree\Rest($storeXmlId);//'5ea18761-a792-11e9-a246-00505601048d'
        parent::__construct($treeBuilder, $catalogId);
    }

    public function update()
    {
        $arReq = $this->treeBuilder->getRequestArr();//product xml_id => store xml_id => count
        $arUid = array_keys($arReq);
        $this->products = $this->getProductId($arUid);
        $rests = $this->getRest();
//pr($this->products);
//pr($this->stores);
//pr($arReq);
//die();
        foreach ($this->products as $id => $xml_id) {

            if( isset($arReq[$xml_id]) ){

                $arTotalAmount = [];

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

                        if($result->isSuccess()) {

                            if (isset($arTotalAmount[$id])) {
                                $arTotalAmount[$id] += $amount;
                            } else {
                                $arTotalAmount[$id] = $amount;
                            }
                        }
                    } catch(\Bitrix\Main\DB\SqlQueryException $e){
                        //уже есть
                    } catch(\Exception $e){
                        //
                    }

                }

                foreach($arTotalAmount as $productId => $totalAmount){
                    $this->updateAvailable($productId, $totalAmount);
                }
            }
        }
        return ;
    }

    private function getStores($xml_id = false){

        $stores = [];

        try {
            $param = [];

            if( !empty($xml_id) ){
                $param["filter"] = ["XML_ID" => $xml_id];
                $param["limit"] = 1;
            }
            $stores = \Bitrix\Catalog\StoreTable::getlist($param)->fetchAll();
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
                //$storeXmlId = $this->stores[$row['STORE_ID']];
                //$storeProducts[$productXmlId][$storeXmlId] = $row['ID'];
                $storeProducts[$productXmlId][$this->storeXmlId] = $row['ID'];
            }
        } catch (\Exception $e){
            //
        }
        return $storeProducts;
    }

    private function updateAvailable($id, $quantity){

        $obProduct = new \CCatalogProduct();
        return $obProduct->Update($id, ['QUANTITY' => $quantity]);
    }
}