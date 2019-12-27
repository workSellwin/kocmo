<?php


namespace Lui\Kocmo\Action;


use Bitrix\Main\LoaderException,
    Lui\Kocmo\Interfaces\ActionsInterfaces,
    \Bitrix\Main\Loader,
    Kocmo\Exchange;

class UpdateProduct implements ActionsInterfaces
{

    public function update($param){

        $returnVal = ["SUCCESS" => 0, "VALUE" => 0, "ERRORS" => []];
        $param = json_decode($param, true);

        try {
            Loader::includeModule('kocmo.exchange');
        } catch( LoaderException $e){
            $returnVal["ERRORS"][] =  $e->getMessage();
        }

        $o = new Exchange\Import\Product();
        $o->update([$param['XML_ID']]);

        if(!$o->isSuccess()){
            array_merge($returnVal["ERRORS"], $o->getErrors());
        }
        else{
            //$returnVal["VALUE"] = $o->getProductId();
            $returnVal["SUCCESS"] = 1;
        }

        return $returnVal;
    }

    public function Available(){
        return ['update'];
    }
}