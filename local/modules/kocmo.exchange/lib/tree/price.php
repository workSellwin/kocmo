<?php


namespace Kocmo\Exchange\Tree;


class Price extends Builder
{
    function __construct()
    {
        parent::__construct();
        $this->entry = $this->arParams['PRICE_ENTRY'];
        $this->fillInOutputArr();

        $arTemp = [];

        if( count($this->outputArr) ){
            foreach($this->outputArr as $price){
                $uid = $price['UID'];
                unset($price['UID']);

                if( isset($arTemp[$uid]) ){
                    $arTemp[$uid][$price['ТипЦены']] = $price['Цена'];
                }
                else{
                    $arTemp[$uid] = [$price['ТипЦены'] => $price['Цена']];
                }
            }
            $this->outputArr = $arTemp;
        }
    }
}