<?php


namespace Lui\Kocmo\Action;

use Basket;
use Lui\Kocmo\Interfaces\ActionsInterfaces;

class UpdateBasket implements ActionsInterfaces
{
    public function Update($arParams)
    {
        if ($arParams['UPDATE_BASKET'] == 'Y') {
            if ($arParams['PRODUCT_ID']) {
                $basket = new Basket();
                $PRODUCT_ID = $arParams['PRODUCT_ID']; // ID товара, обязательно
                $FIELDS = $arParams['FIELDS'];
                if($basket::updateProductBasket($PRODUCT_ID, $FIELDS))return true;
                    return false;
            } else {
                echo false;
            }
        }else{
            echo false;
        }
    }

    public function Available()
    {
        return ['Update'];
    }
}