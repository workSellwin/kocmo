<?php


namespace Lui\Kocmo\Action;

use Basket;
use Lui\Kocmo\Interfaces\ActionsInterfaces;

class DelBasket implements ActionsInterfaces
{
    public function Del($arParams)
    {
        if ($arParams['DEL_BASKET'] == 'Y') {
            if ($arParams['PRODUCT_ID'] && $arParams['ID']) {
                $basket = new Basket();
                $PRODUCT_ID = $arParams['PRODUCT_ID']; // ID товара, обязательно
                $ID = $arParams['ID'];
                if($r = $basket->delBasket($ID) > 0){
                    return $r;
                }
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
        return ['Del'];
    }
}