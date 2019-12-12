<?php


class ElementPrice
{

    /**
     * @param $PRODUCT_ID
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     */
    static function getElementPrice($PRODUCT_ID){

        $PRICE = new Price();
        $arPriseType = $PRICE::getListTypePrice();
        $ROZNICHNAYA = $PRICE::getPriceProduct($PRODUCT_ID, $arPriseType['ROZNICHNAYA']['XML_ID']);
        $AKTSIONNAYA = $PRICE::getPriceProduct($PRODUCT_ID, $arPriseType['AKTSIONNAYA']['XML_ID']);

        $PRICE_NEW = 0;
        $PRICE_OLD = 0;
        $DISCOUNT = 0;
        $PERCENT = 0;

        if($ROZNICHNAYA['PRICE']){
            if($AKTSIONNAYA['PRICE']){
                $PRICE_NEW = $AKTSIONNAYA['PRICE'];
                $PRICE_OLD = $ROZNICHNAYA['PRICE'];
                $DISCOUNT = $ROZNICHNAYA['PRICE'] - $AKTSIONNAYA['PRICE'];
                $PERCENT = round($DISCOUNT / $ROZNICHNAYA['PRICE'], 2)*100;
            }else{
                $PRICE_NEW = $ROZNICHNAYA['PRICE'];
            }
        }else{
            if($AKTSIONNAYA['PRICE']){
                $PRICE_NEW = $AKTSIONNAYA['PRICE'];
            }
        }

        return $elemPrice = [
            'PRODUCT_ID'    => $PRODUCT_ID,
            'PRICE_NEW'     => $PRICE_NEW,
            'PRICE_OLD'     => $PRICE_OLD,
            'DISCOUNT'      => $DISCOUNT,
            'PERCENT'       => $PERCENT,
            'ROZNICHNAYA'   => $ROZNICHNAYA,
            'AKTSIONNAYA'   => $AKTSIONNAYA,
        ];
    }
}
