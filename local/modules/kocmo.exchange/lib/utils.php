<?php


namespace Kocmo\Exchange;

use Bitrix\Main\Config\Option,
    Bitrix\Main\Loader,
    \Bitrix\Catalog\Model,
    \Bitrix\Catalog;

class Utils
{
    private $module = 'kocmo.exchange';

    public function setModuleData(string $name, string $data){
        $data = substr($data, -36);
        Option::set($this->module, $name, $data);
    }

    public function getModuleData (string $name){
        return Option::get($this->module, $name);
    }

    public function checkRef($val = false)
    {
        if( empty($val) ){
            return false;
        }

        if(is_string($val) && strlen($val) === 38 && strpos($val, 'p_') === 0){
            return true;
        }

        if (is_string($val) && strlen($val) === 36 && $val != '00000000-0000-0000-0000-000000000000') {
            $arr = explode('-', $val);

            if (strlen($arr[0]) === 8 && strlen($arr[1]) === 4 && strlen($arr[2]) === 4
                && strlen($arr[3]) === 4 && strlen($arr[4]) === 12) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    public function getGuid( $str ){
        return str_replace(["g_", "_"], ["", "-"], $str);
    }

    public function getStrFromGuid( $guid ){
        return "g_" . str_replace("-", "_", $guid);
    }

    public function getCode($outCode)
    {

        $newStr = "";

        for ($i = 0; $i < mb_strlen($outCode); $i++) {
            $char = mb_substr($outCode, $i, 1);

            if (strpos('АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ', $char) !== false && $i) {
                $newStr .= '_' . $char;
            } else {
                $newStr .= $char;
            }
        }

        return \CUtil::translit($newStr, 'ru', ['change_case' => 'U']);
    }

    function clearTable($table){

        if(!is_string($table)){
            return false;
        }
        $connection = \Bitrix\Main\Application::getConnection();
        return $connection->truncateTable($table);
    }

    public function getElementsStatus (array $filter) {

        Loader::includeModule('iblock');

        $res = \CIBlockElement::GetList(
            [],
            $filter,
            false,
            false,
            ['ID', 'ACTIVE']
        );

        $ids = [];

        while ($fields = $res->fetch()) {
            $ids[$fields['ID']] = $fields['ACTIVE'];
        }
        return $ids;
    }

    public function getStoreProductsQuantity($limit = 1){

        Loader::includeModule('catalog');

        $iterator = \Bitrix\Catalog\StoreProductTable::getlist([
            "filter" => [">AMOUNT" => $limit]
        ]);

        $productQuantity = [];

        while($row = $iterator->fetch()){
                $productQuantity[$row['PRODUCT_ID']] = true;
        }

        return $productQuantity;
    }

    public function getCatalogQuantity($limit = 1){

        Loader::includeModule('catalog');

        $productQuantity = [];

        $iterator = \Bitrix\Catalog\ProductTable::getList([
            "filter" => [">QUANTITY" => $limit]
        ]);

        while($row = $iterator->fetch()){
            $productQuantity[$row['ID']] = $row['QUANTITY'];
        }
        return $productQuantity;
    }

    public function getElementPrices () {

        $iterator = Model\Price::getlist([]);
        $productPrices = [];//все элементы имеющие цены

        while ($row = $iterator->fetch()) {

            if ($row['PRICE'] > 0) {
                $productPrices[$row['PRODUCT_ID']] = true;
            }
        }

        return $productPrices;
    }

    public function getOffers(){

        Loader::includeModule('catalog');

        $iterator = Catalog\ProductTable::getList([
            "filter" => [
                "TYPE" => 4
            ]
        ]);

        $offersId = [];

        while( $fields = $iterator->fetch() ){
            $offersId[] = $fields['ID'];
        }

        return $offersId;
    }

    public function getBindProductsFromOffers($offersId){

        $res = \CIblockElement::GetList(
            [],
            ['IBLOCK_ID' => 3, 'ID' => $offersId, 'ACTIVE' => 'Y'],
            false,
            false,
            ['ID' , 'PROPERTY_CML2_LINK']
        );

        $productIds = [];

        while( $fields = $res->fetch() ){
            $productIds[] = $fields['PROPERTY_CML2_LINK_VALUE'];
        }

        return $productIds;
    }


    public function getProductsId(array $xmlId){
        //["IBLOCK_ID" => [2, 3], "XML_ID" => $xmlId, '!SORT' => 988]
        $res = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => [2], "XML_ID" => $xmlId],
            false,
            false,
            ['ID', "XML_ID"]
        );
        $products = [];

        while($fields = $res->fetch() ){
            $products[$fields['ID']] = $fields['XML_ID'];
        }
        return $products;
    }


    public function getProductsQuantity($limit = 1){

        Loader::includeModule('catalog');

        $iterator = \Bitrix\Catalog\StoreProductTable::getlist([
            "filter" => [">AMOUNT" => $limit]
        ]);

        $productQuantity = [];

        while($row = $iterator->fetch()){
            $productQuantity[$row['PRODUCT_ID']] = true;
        }

        return $productQuantity;
    }
}