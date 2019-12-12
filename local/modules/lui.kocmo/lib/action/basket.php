<?php


namespace Lui\Kocmo\Action;


use Lui\Kocmo\Interfaces\ActionsInterfaces;

class basket implements ActionsInterfaces
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
                $basket = new \Basket();
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

    /**
     * Удаление продукта с карзины
     * @param $arParams
     * @return bool
     */
    public function Del($arParams)
    {
        if ($arParams['DEL_BASKET'] == 'Y') {
            if ($arParams['PRODUCT_ID'] && $arParams['ID']) {
                $basket = new \Basket();
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

    /**
     * есть ли продукты в карзине
     * @param $arParams
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     */
    public function EmptyBasket($arParams)
    {
        if ($arParams['IS_PRODUCT'] == 'Y') {

            $basket = new \Basket();
            $arProd = $basket::getProductBasket();
            $arProd = array_column($arProd, 'PRODUCT_ID', 'PRODUCT_ID');
            if(empty($arProd)){
                return true;
            }else{
                return false;
            }
        }else{
            echo false;
        }
    }


    public function Available()
    {
        return ['Add', 'Del', 'EmptyBasket'];
    }
}