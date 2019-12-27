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
    private $brandsIBlockId = 7;
    private $catalogIBlockId = 2;

    function __construct()
    {
        $this->utils = new Exchange\Utils();
    }

    public function updateElementStatus(){

        try {

            Loader::includeModule('iblock');
            Loader::includeModule('catalog');

            $el = new \CIBlockElement();

            $elementsStatus = $this->utils->getElementsStatus(["IBLOCK_ID" => [$this->catalogIBlockId]]);
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
                ["IBLOCK_ID" => $this->catalogIBlockId, "!PROPERTY_MARKA" => false, 'ACTIVE' => 'Y'],
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
                ["IBLOCK_ID" => $this->catalogIBlockId, "CODE" => "MARKA", 'ID' => array_keys($markaIds)]
            );

            $brandsEnum = [];

            while ($enum_fields = $property_enums->GetNext()) {
                $brandsEnum[$enum_fields['XML_ID']] = $enum_fields['VALUE'];
            }

            $brandsElem = [];

            $res = \CIBlockElement::GetList(
                [],
                ["IBLOCK_ID" => $this->brandsIBlockId],
                false,
                false,
                ["NAME", "ID", "XML_ID", 'PROPERTY_BRAND_BIND']
            );

            while ($fields = $res->fetch()) {

                $brandsElem[$fields['PROPERTY_BRAND_BIND_VALUE']] = $fields['ID'];
            }

            $el = new \CIBlockElement;

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
}