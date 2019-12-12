<?php


namespace Lui\Kocmo;


class Product
{
    protected $arData;

    public function __construct(array $arData)
    {
        $this->arData = $arData;
    }

    public function GetDataBasketMain()
    {
        $arData = $this->arData;
        $arData = [
            'ID' => $arData['ID'],
            'AVAILABLE_QUANTITY' => $arData['AVAILABLE_QUANTITY'],
            'PROPERTY_ARTIKUL_VALUE' => $arData['PROPERTY_ARTIKUL_VALUE'],
            'PRODUCT_ID' => $arData['PRODUCT_ID'],
            'QUANTITY' => $arData['QUANTITY'],
            'BASE_PRICE' => $arData['BASE_PRICE'],
            'NAME' => $arData['NAME'],
            'DETAIL_PAGE_URL' => $arData['DETAIL_PAGE_URL'],
            'PRODUCT_XML_ID' => $arData['PRODUCT_XML_ID'],
            'PREVIEW_PICTURE_SRC' => $arData['PREVIEW_PICTURE_SRC'] ? $arData['PREVIEW_PICTURE_SRC'] : $arData['DETAIL_PICTURE_SRC'],
        ];
        return $arData;
    }
}
