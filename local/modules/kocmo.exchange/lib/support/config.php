<?php


namespace Kocmo\Exchange\Support;
use Kocmo\Exchange\Interfaces,
    Kocmo\Exchange\Utils;

class Config implements Interfaces\Config
{
    private $utils;
    private $space = null;
    private $data = [
        "bx" => [
            'IBLOCK_CATALOG_ID' => 2,
            'IBLOCK_OFFERS_ID' => 3,
            'TIME_LIMIT' => 50,
            'PARENT_ID' => 'Родитель',
            'ID' => "UID",
            'NAME' => "Наименование",
            'ADDRESS' => 'Адрес',
            'CHILDREN' => 'CHILDREN',
            'DEPTH_LEVEL' => 'DEPTH_LEVEL',
            'FULL_NAME' => "НаименованиеПолное",
            'PROPERTIES' => "Свойства",
            'DESCRIPTION' => 'Описание',
            'PIC_FILE' => 'ФайлКартинки',
            'REST' => 'Остаток',
            'TYPE_PRICE' => 'ТипЦены',
            'PRICE' => 'Цена',
            'OFFERS_POINT_OF_ENTRY' => 'http://1c.kocmo.by/kocmo/hs/Kocmo/GetOffers',
            'PRODUCT_LAST_UID' => 'PRODUCT_LAST_UID',
            'OFFER_LAST_UID' => 'OFFER_LAST_UID',
            'LAST_STORE_ID' => 'LAST_STORE_ID',
            'PROP_REF' => 'CML2_LINK',
        ],
        "tree" => [
            'PRODUCT_LIMIT' => 500,
            'WAITING_TIME' => 0,
            'PARENT_ID' => 'Родитель',
            'ID' => "UID",
            'PARENT' => "PARENT",
            'NAME' => "Наименование",
            'ADDRESS' => 'Адрес',
            'CHILDREN' => 'CHILDREN',
            'DEPTH_LEVEL' => 'DEPTH_LEVEL',
            'FULL_NAME' => "НаименованиеПолное",
            'PROPERTIES' => "Свойства",
            'DESCRIPTION' => 'Описание',
            'PIC_FILE' => 'ФайлКартинки',
            'REST' => 'Остаток',
            'TYPE_PRICE' => 'ТипЦены',
            'PRICE' => 'Цена',
            'PROP_POINT_OF_ENTRY' => 'http://1c.kocmo.by/kocmo/hs/Kocmo/GetScheme/22e8d9ce-ed52-47ca-a524-e32b586aab0a/',
            'PROD_POINT_OF_ENTRY' => 'http://1c.kocmo.by/kocmo/hs/Kocmo/GetCatalog',
            'OFFERS_POINT_OF_ENTRY' => 'http://1c.kocmo.by/kocmo/hs/Kocmo/GetOffers',
            'SECT_POINT_OF_ENTRY' => 'http://1c.kocmo.by/kocmo/hs/Kocmo/GetSections/',
            'GET_IMAGE_URI' => 'http://1c.kocmo.by/kocmo/hs/Kocmo/GetImage/',
            'REFERENCE_URL' => 'http://1c.kocmo.by/kocmo/hs/Kocmo/GetReference/',
            'TYPE_PRICE_ENTRY' => 'http://1c.kocmo.by/kocmo/hs/Kocmo/GetReference/42d10808-9ccb-11e8-a215-00505601048d/',
            'PRICE_ENTRY' => 'http://1c.kocmo.by/kocmo/hs/Kocmo/GetPrice/',
            'STORE_ENTRY' => 'http://1c.kocmo.by/Kocmo/hs/Kocmo/GetReference/42d1082d-9ccb-11e8-a215-00505601048d',
            'REST_ENTRY' => 'http://1c.kocmo.by/Kocmo/hs/Kocmo/GetStock',
            'PRODUCT_LAST_UID' => 'PRODUCT_LAST_UID',
            'OFFER_LAST_UID' => 'OFFER_LAST_UID',
            'LAST_STORE_ID' => 'LAST_STORE_ID',
        ],
    ];

    private $moduleOptions = [
        "IBLOCK_CATALOG_ID" => "exchange-catalog_id",
        "IBLOCK_OFFERS_ID" => "exchange-offers_id",
        "SECT_POINT_OF_ENTRY" => "exchange-section-href",
        "PROD_POINT_OF_ENTRY" => "exchange-product-href",
        "PROP_POINT_OF_ENTRY" => "exchange-props-href",
        "GET_IMAGE_URI" => "exchange-image-href",
        "REFERENCE_URL" => "exchange-schema-href",
        "TYPE_PRICE_ENTRY" => "exchange-price-type-href",
        "PRICE_ENTRY" => "exchange-price-href",
        "STORE_ENTRY" => "exchange-store-href",
        "REST_ENTRY" => "exchange-rest-href",
    ];

    function __construct(string $space)
    {
        $this->space = $space;
        $this->utils = new Utils();
    }

    function getConfig(){

        if( isset($this->data[$this->space]) ){
            $this->setConfigFromModuleOptions();
            return $this->data[$this->space];
        }
        else{
            return false;
        }
    }

    private function setConfigFromModuleOptions(){

        foreach( $this->moduleOptions as $key => $option ){
            $temp = $this->utils->getModuleData($option);
            if($temp){
                $this->data[$this->space][$key] = $temp;
            }

        }
    }
}