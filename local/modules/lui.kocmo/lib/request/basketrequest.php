<?php


namespace Lui\Kocmo\Request;


class BasketRequest
{
    protected $arBasket = [];

    public function __construct($arData = [])
    {
        if ($arData) {
            $this->arBasket = $arData;
        } else {
            $this->arBasket = Basket::Get1CRequest();
        }
    }

    public function GetEconomy()
    {
        $result = 0;
        if ($arBasket = $this->arBasket and $arBasket['goods']) {
            foreach ($arBasket['goods'] as $arItem) {
                foreach ($arItem['DISCOUNT'] as $arDiscount) {
                    $result += $arDiscount['VALUE'];
                }
            }
        }
        return $result;
    }

    public function GetTotal()
    {
        $result = $this->GetSum();
        if ($Economy = $this->GetEconomy()) {
            $result -= $Economy;
        }
        if ($Certificate = $this->GetCertificate()) {
            $result -= $Certificate;
        }
        return $result > 0 ? $result : 0;
    }

    public function GetSum()
    {
        $result = 0;
        if ($arBasket = $this->arBasket and $arBasket['goods']) {
            foreach ($arBasket['goods'] as $arItem) {
                $result += $arItem['SUMM'];
            }
        }
        return $result;
    }

    public function GetCertificate()
    {
        $result = 0;

        return $result;
    }


    public function GetResult()
    {
        return [
            'Sum' => $this->GetSum(),
            'Economy' => $this->GetEconomy(),
            'Total' => $this->GetTotal(),
            'Certificate' => $this->GetCertificate(),
            'Delivery' => 0,
        ];
    }
}
