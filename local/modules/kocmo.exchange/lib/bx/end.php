<?php


namespace Kocmo\Exchange\Bx;

use Kocmo\Exchange,
    \Bitrix\Main\Loader,
    \Bitrix\Main\LoaderException,
    \Bitrix\Catalog\Model;

class End
{
    /* @var $utils Exchange\Utils */
    protected $utils = null;
    protected $errors = [];
    protected $productsStatus = [];

    function __construct()
    {
        $this->utils = new Exchange\Utils();
    }

    public function update(): bool
    {

        //$connection = \Bitrix\Main\Application::getConnection();
        //$connection->truncateTable('kocmo_exchange_data');
        //$connection->truncateTable('kocmo_exchange_product_image');

        //$this->utils->setModuleData('PRODUCT_LAST_UID', '');
        //$this->utils->setModuleData('OFFER_LAST_UID', '');

        return true;
    }

    public function updateElementStatus(){

        try {

            Loader::includeModule('iblock');
            Loader::includeModule('catalog');

            $el = new \CIBlockElement();

            $elementsStatus = $this->utils->getElementsStatus(["IBLOCK_ID" => [2], '!SORT' => 988]);//все элементы с их статусами
            $productPrices = $this->utils->getElementPrices();//все элементы имеющие цены
            $productQuantity = $this->utils->getProductsQuantity();//все товары с количеством

            $el = new \CIBlockElement();

            foreach ($elementsStatus as $id => $status) {

                if($status == 'Y'){
                    if( !isset($productPrices[$id]) || !isset($productQuantity[$id]) ){
                        $el->Update($id, ['ACTIVE' => 'N']);
                    }
                }
                else{
                    if( isset($productPrices[$id]) && isset($productQuantity[$id]) ) {
                        $el->Update($id, ['ACTIVE' => 'Y']);
                    }
                }
            }
        } catch (LoaderException $e) {

        }
    }

    public function updateBrands()
    {

        try {
            Loader::includeModule('iblock');

            $res = \CIBlockElement::GetList(
                [],
                ["IBLOCK_ID" => 2, "!PROPERTY_MARKA" => false, 'ACTIVE' => 'Y'],
                false,
                false,
                ["NAME", "ID", "XML_ID", 'PROPERTY_MARKA']
            );

            $markaIds = [];

            while ($fields = $res->fetch()) {
                $markaIds[$fields['PROPERTY_MARKA_ENUM_ID']] = $fields['PROPERTY_MARKA_VALUE'];
            }
            unset($res);

            $property_enums = \CIBlockPropertyEnum::GetList(
                [],
                ["IBLOCK_ID" => 2, "CODE" => "MARKA", 'ID' => array_keys($markaIds)]
            );

            $brandsEnum = [];

            while ($enum_fields = $property_enums->GetNext()) {
                $brandsEnum[$enum_fields['XML_ID']] = $enum_fields['VALUE'];
            }

            $brandsElem = [];

            $res = \CIBlockElement::GetList(
                [],
                ["IBLOCK_ID" => 7],
                false,
                false,
                ["NAME", "ID", "XML_ID", 'PROPERTY_BRAND_BIND']
            );

            while ($fields = $res->fetch()) {

                $brandsElem[$fields['PROPERTY_BRAND_BIND_VALUE']] = $fields['ID'];
            }

            $el = new \CIBlockElement;
//pr($brandsElem, 14);
            foreach ($brandsElem as $enumXmlId => $brandId) {

                if (isset($brandsEnum[$enumXmlId])) {
                    $el->Update($brandId, ['ACTIVE' => 'Y']);
                } else {
                    $el->Update($brandId, ['ACTIVE' => 'N']);
                }
            }
        } catch (LoaderException $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    public function updateAvailable()
    {
        $bx = new Rest();
        $bx->updateAvailable();
    }
//
//    public function activateElement()
//    {
//        $bx = new Rest();
//        $bx->activateElement();
//    }

//    public function deactivateEmptyPriceElem()
//    {
//
//        try {
//            Loader::includeModule('iblock');
//            Loader::includeModule('catalog');
//
//            $res = \CIBlockElement::GetList(
//                [],
//                ["IBLOCK_ID" => [2, 3]],
//                false,
//                false,
//                ['ID', 'ACTIVE']
//            );
//
//            $ids = [];
//
//            while ($fields = $res->fetch()) {
//                $ids[$fields['ID']] = $fields['ACTIVE'];
//            }
//
//            $iterator = Model\Price::getlist([]);
//            $productPrices = [];
//
//            while ($row = $iterator->fetch()) {
//
//                if ($row['PRICE'] > 0) {
//                    $productPrices[$row['PRODUCT_ID']] = true;
//                }
//            }
//            $el = new \CIBlockElement();
//
//            foreach ($ids as $id => $status) {
//
//                if (!isset($productPrices[$id])) {
//                    if ($status != 'N') {
//                        $el->Update($id, ['ACTIVE' => 'N']);
//                    }
//                }
//            }
//        } catch (LoaderException $e) {
//
//        }
//    }

//    public function updateAvailable()
//    {
//
//        $productAmount = $this->getProductAmount();
//        $productQuantity = $this->utils->getProductsQuantity();
//
//        $obProduct = new \CCatalogProduct();
//
//        foreach ($productAmount as $id => $quantity) {
//
//            if ($quantity < 2) {
//                $quantity = 0;
//            }
//
//            if($quantity != $productQuantity[$id]){
//                $productQuantity[$id] = $quantity;
//                $obProduct->Update($id, ['QUANTITY' => $quantity]);
//            }
//        }
//    }
}