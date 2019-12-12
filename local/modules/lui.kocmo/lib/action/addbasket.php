<?php


namespace Lui\Kocmo\Action;


use Basket;
use Lui\Kocmo\Interfaces\ActionsInterfaces;

class AddBasket implements ActionsInterfaces
{

    /**
     * добавление продукта в карзину
     * @param $arParams
     * @return mixed
     */
    public function Add($arParams)
    {
        if ($arParams['ADD_BASKET'] == 'Y') {
            if ($arParams['PRODUCT_ID']) {
                $basket = new Basket();
                $PRODUCT_ID = $arParams['PRODUCT_ID']; // ID товара, обязательно
                $QUANTITY = $arParams['QUANTITY'] ? $arParams['QUANTITY'] : 1; // количество, обязательно
                if ($res = $basket::addBasket($PRODUCT_ID, $QUANTITY)) {
                    echo $res;
                } else {
                    echo false;
                }
            } else {
                echo false;
            }
        }else{
            echo false;
        }
    }

    public function Available()
    {
        return ['Add'];
    }
}
