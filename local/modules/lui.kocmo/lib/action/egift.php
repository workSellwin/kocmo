<?php


namespace Lui\Kocmo\Action;
use MyOrder;
use Lui\Kocmo\Interfaces\ActionsInterfaces;
use Lui\Kocmo\Data\IblockElement;

class egift implements ActionsInterfaces
{
    public $IBLOCK_EGIFT_ID = 12;

    public function creatNewOrder($arParams)
    {
        if ($arParams['EGIFT_ELEMENT_ID']) {
            $EGIFT_ID = $arParams['EGIFT_ELEMENT_ID'];
            $arDataEgift = new IblockElement;
            $arFilter = Array("IBLOCK_ID"=>$this->IBLOCK_EGIFT_ID, 'ID'=>$EGIFT_ID);
            $arDataEgift = $arDataEgift->GetData($arFilter);
            $order = new MyOrder;
            if($id = $order->process($EGIFT_ID, 1,  $arDataEgift[$EGIFT_ID]['PROPERTY']['SUM_SERTIFIKAT'])){
                return $id;
            }else {
                return false;
            }
        }else{
            echo false;
        }
    }

    public function Available()
    {
        return ['creatNewOrder'];
    }

}