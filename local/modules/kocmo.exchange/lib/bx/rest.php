<?php


namespace Kocmo\Exchange\Bx;

use \Bitrix\Catalog,
    \Bitrix\Main\DB,
    \Bitrix\Main\Loader;

class Rest extends Helper
{

    protected $stores = [];
    protected $curStore = false;
    protected $products = [];
    protected $storeXmlId = false;

    function __construct()
    {
        Loader::includeModule('catalog');

        $treeBuilder = new \Kocmo\Exchange\Tree\Rest();
        parent::__construct($treeBuilder);
    }

    private function setCurStore()
    {

        $curXmlId = false;

        if (isset($this->stores) && count($this->stores)) {

            $last = false;
            $lastStoreId = $this->utils->getModuleData($this->arParams['LAST_STORE_ID']);

            if ( empty($lastStoreId) ) {

                reset($this->stores);
                $curXmlId = current($this->stores);
                $this->curStore = key($this->stores);
            }
            else {
                $this->curStore = $lastStoreId;
                $curXmlId = $this->stores[$lastStoreId];
            }
        }

        return $curXmlId;
    }

    private function nextStore()
    {

        $last = false;
        $install = false;

        foreach ($this->stores as $id => $xml) {

            if ($last) {
                $this->utils->setModuleData($this->arParams['LAST_STORE_ID'], $id);
                $install = true;
                break;
            }

            if ($id == $this->curStore) {
                $last = true;
            }
        }
        if(!$install){
            $this->resetCurStore();
        }
    }

    public function update($full = true, array $exchangeData = []): bool
    {

        $this->stores = $this->getStores();

        if($full) {
            $storeXmlId = $this->setCurStore();

            if (!empty($storeXmlId) && $this->utils->checkRef($storeXmlId) && in_array($storeXmlId, $this->stores)) {

                $this->storeXmlId = $storeXmlId;
                $this->treeBuilder->setStoreRest($storeXmlId);
            } else {
                $this->storeXmlId = false;
                throw new \Exception("store not found!");
            }
        }
        else{
            $this->treeBuilder->setRest($exchangeData);
        }

//        if ($this->storeXmlId === false) {
//            $this->updateAvailable();
//            return false;
//        }

        $arReq = $this->treeBuilder->getRequestArr();//product xml_id => store xml_id => count
        $arUid = array_keys($arReq);
        $this->products = $this->utils->getProductsId($arUid);

        if($full) {
            $restIds = $this->getRestIds();
        }
        else{
            $restIds = $this->getRestIds(["PRODUCT_ID" => array_keys($this->products)]);
        }
        $rowId = [];

        foreach ($this->products as $id => $xml_id) {

            if (isset($arReq[$xml_id])) {

                foreach ($arReq[$xml_id] as $storeXmlId => $amount) {

                    try {

                        if (isset($restIds[$xml_id]) && isset($restIds[$xml_id][$storeXmlId])) {

                            $restId = $restIds[$xml_id][$storeXmlId];

                            $result = Catalog\StoreProductTable::update($restId, [
                                "PRODUCT_ID" => $id,
                                "AMOUNT" => $amount,
                                "STORE_ID" => array_search($storeXmlId, $this->stores)
                            ]);

                        } else {

                            $result = Catalog\StoreProductTable::add([
                                "PRODUCT_ID" => $id,
                                "AMOUNT" => $amount,
                                "STORE_ID" => array_search($storeXmlId, $this->stores)
                            ]);
                        }

                        if ($result->isSuccess()) {

                            $rowId[] = $result->getId();
                        }
                        else{
                            $this->errors[] = $result->getErrors();
                        }
                    } catch (DB\SqlQueryException $e) {
                        //уже есть
                    } catch (\Exception $e) {
                    }
                }
            }
        }

        if ($full) {
            //$this->clearOldRest($rowId);
            $this->nextStore();
            $lastStoreId = $this->utils->getModuleData($this->arParams['LAST_STORE_ID']);

            if (empty($lastStoreId)) {
                $this->status = 'end';
            } else {
                $this->status = 'run';
            }
        }

        return true;
    }

    public function updateFrom1C( array $data ){

        if(!count($data)){
            return false;
        }

        $stores = $this->getStores();


    }

    private function getStores($xml_id = false)
    {

        $stores = [];

        try {
            $param["filter"] = ['ACTIVE' => 'Y'];

            if (!empty($xml_id)) {
                $param["filter"] = ["XML_ID" => $xml_id];
                $param["filter"]["limit"] = 1;
            }
            $stores = Catalog\StoreTable::getlist($param)->fetchAll();
            $stores = array_column($stores, "XML_ID", "ID");
        } catch (\Exception $e) {
            //
        }

        return $stores;
    }

    private function getRestIds($filter = false)
    {

        $storeProducts = [];

        if( !is_array($filter) ){

            if( empty($this->curStore) ){
                return $storeProducts;
            }

            $filter = ['STORE_ID' => $this->curStore];
        }

        try {
            $iterator = Catalog\StoreProductTable::getlist( ['filter' => $filter] );

            while ( $row = $iterator->fetch() ) {

                $productXmlId = $this->products[$row['PRODUCT_ID']];

                if( !empty($productXmlId) ) {
                    $storeXmlId = $this->stores[$row['STORE_ID']];
                    $storeProducts[$productXmlId][$storeXmlId] = $row['ID'];
                }
            }
        } catch (\Exception $e) {
            //
        }

        return $storeProducts;
    }

    public function updateAvailable()
    {

        $productAmount = $this->getProductAmount();
        $productQuantity = $this->utils->getCatalogQuantity();

        $obProduct = new \CCatalogProduct();

        foreach ($productAmount as $id => $quantity) {

            if ($quantity < 2) {
                $quantity = 0;
            }

            if( !isset($productQuantity[$id]) ){
                $productQuantity[$id] = 0;
            }

            if($quantity != $productQuantity[$id]){
                $obProduct->Update($id, ['QUANTITY' => $quantity]);
            }
        }
    }

    public function getProductAmount(){

        $res = Catalog\StoreProductTable::getList([
            'filter' => [
                'STORE_ID' => [17, 35],
            ]
        ]);

        $productAmount = [];

        while ($row = $res->fetch()) {

            if (isset($productAmount[$row['PRODUCT_ID']])) {

                if($row['AMOUNT'] > 1){
                    $productAmount[$row['PRODUCT_ID']] += $row['AMOUNT'];
                }
                else{
                    $productAmount[$row['PRODUCT_ID']] += 0;
                }

            } else {
                if($row['AMOUNT'] > 1) {
                    $productAmount[$row['PRODUCT_ID']] = $row['AMOUNT'];
                }
                else{
                    $productAmount[$row['PRODUCT_ID']] += 0;
                }
            }
        }
        return $productAmount;
    }

    public function resetCurStore()
    {
        $this->utils->setModuleData($this->arParams['LAST_STORE_ID'], "");
    }

    public function activateElement(){

        $res = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => [2, 3], "ACTIVE" => 'N', "CATALOG_AVAILABLE" => 'N'],
            false,
            false,
            ['ID']
        );
        $ids = [];

        while ($fields = $res->fetch()) {
            $ids[$fields['ID']] = 'N';
        }

        if (!count($ids)) {
            return false;
        }
        $res = Catalog\StoreProductTable::getList([
            'filter' => ['>AMOUNT' => 1, "PRODUCT_ID" => array_keys($ids)],
        ]);

        while ($row = $res->fetch()) {
            $ids[ $row["PRODUCT_ID"] ] = 'Y';
        }

        $el = new \CIBlockElement();

        foreach( $ids as $id => $active){
            if( $active == 'Y' ){
                $el->Update($id, ['ACTIVE' => 'Y']);
            }
        }
    }

    private function clearOldRest($rowId)
    {

        if (!count($rowId)) {
            return false;
        }

        $res = Catalog\StoreProductTable::getList([
            'filter' => ['STORE_ID' => $this->curStore, '!ID' => $rowId]
        ]);

        $arOld = [];

        while ($row = $res->fetch()) {
            $arOld[] = $row['ID'];
        }

        if (count($arOld)) {
            foreach ($arOld as $rest) {
                Catalog\StoreProductTable::delete($rest);
            }
        }
    }
}