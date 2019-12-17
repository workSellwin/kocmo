<?php


namespace Kocmo\Exchange\Tree;


class Price extends Builder
{
    function __construct()
    {
        parent::__construct();
    }

    public function setPrice(){

        $this->entry = $this->arParams['PRICE_ENTRY'];

        if( !count($this->outputArr) ){
            $this->fillInOutputArr();
        }

        $arTemp = [];

        if( count($this->outputArr) ){
            foreach($this->outputArr as $price){
                $uid = $price['UID'];
                unset($price['UID']);

                if( isset($arTemp[$uid]) ){
                    $arTemp[$uid][$price[ $this->arParams['TYPE_PRICE'] ]] = $price[ $this->arParams['PRICE'] ];
                }
                else{
                    $arTemp[$uid] = [$price[ $this->arParams['TYPE_PRICE'] ] => $price[ $this->arParams['PRICE'] ]];
                }
            }
            $this->outputArr = $arTemp;
        }
    }
}