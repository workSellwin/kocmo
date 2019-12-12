<?php

namespace Lui\Kocmo\Catalog;

use ElementPrice;

class ElementPrepara
{

    public $arData = [];
    public $arOffers = [];

    /**
     * ElementPrepara constructor.
     * @param $arData
     */
    public function __construct($arData)
    {
        if (!empty($arData['ITEM'])) {
            $this->arData = $arData;
            $this->arOffers = $this->formatOffers($arData['ITEM']['OFFERS']);
        } else {
            echo 'Error: не передан массив arResult';
        }
    }

    /**
     * @param $arOffers
     * @return array
     */
    protected function formatOffers($arOffers)
    {
        $arOf = [];
        foreach ($arOffers as $offer) {
            $arOf[$offer['ID']] = [
                'ID' => $offer['ID'],
                'NAME' => $offer['NAME'],
                'PREVIEW_PICTURE' => $offer['PREVIEW_PICTURE'],
                'SORT' => $offer['SORT'],
                'PROP' => array_column($offer['PROPERTIES'], 'VALUE', 'CODE'),
                'PRICE' => $offer['ITEM_PRICES'],
            ];
        }
        return $arOf;
    }

    /**
     * Минимальная цена offers
     */
    public function getMinPriceOffers()
    {
        $price['PRICE_NEW'] = 0;



        if ($this->arOffers) {
            foreach ($this->arOffers as $offer) {
                $elemPrice = ElementPrice::getElementPrice($offer['ID']);
                if ($price['PRICE_NEW'] <= 0) {
                    $price = $elemPrice;
                } else {
                    if ($price['PRICE_NEW'] > $elemPrice['PRICE_NEW']) {
                        $price = $elemPrice;
                    }
                }
            }
        } else {
            $price = ElementPrice::getElementPrice($this->arData['ITEM']['ID']);
        }

        return $price;
    }

    /**
     * Количество торговых предложений
     * @return int
     */
    public function getCauntOffers()
    {
        return count($this->arOffers);
    }

    /**
     * Свойства элемента
     * @return array
     */
    public function getProp()
    {
        return array_column($this->arData['ITEM']['PROPERTIES'], 'VALUE', 'CODE');
    }
}
