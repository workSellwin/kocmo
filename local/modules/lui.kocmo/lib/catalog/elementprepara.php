<?php

namespace Lui\Kocmo\Catalog;

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
        $price['PRICE'] = 0;
        foreach ($this->arOffers as $offer) {
            if ($price['PRICE'] == 0) {
                $price['PRICE'] = $offer['PRICE'][0]['PRICE'];
                $price['OFFER_ID'] = $offer['ID'];
                $price['BASE_PRICE'] = $offer['PRICE'][0]['BASE_PRICE'];
                $price['DISCOUNT'] = $offer['PRICE'][0]['DISCOUNT'];
                $price['PERCENT'] = $offer['PRICE'][0]['PERCENT'];
            } else {
                if ($price['PRICE'] > $offer['PRICE'][0]['PRICE']) {
                    $price['PRICE'] = $offer['PRICE'][0]['PRICE'];
                    $price['OFFER_ID'] = $offer['ID'];
                    $price['BASE_PRICE'] = $offer['PRICE'][0]['BASE_PRICE'];
                    $price['DISCOUNT'] = $offer['PRICE'][0]['DISCOUNT'];
                    $price['PERCENT'] = $offer['PRICE'][0]['PERCENT'];
                }
            }
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