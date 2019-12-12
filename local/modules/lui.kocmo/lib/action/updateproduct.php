<?php


namespace Lui\Kocmo\Action;


use Bitrix\Main\LoaderException,
    Lui\Kocmo\Interfaces\ActionsInterfaces,
    Kocmo\Exchange;

class UpdateProduct implements ActionsInterfaces
{

    public function update($param){

        $returnVal = ["SUCCESS" => 0, "VALUE" => 0, "ERRORS" => []];
        $param = json_decode($param, true);

        try {
            \Bitrix\Main\Loader::includeModule('kocmo.exchange');
        } catch( \Bitrix\Main\LoaderException $e){
            $returnVal["ERRORS"][] =  $e->getMessage();
        }

        try {
            $o = new Exchange\Entityupdate($param['ID']);
        } catch(\Exception $e){
            $returnVal["ERRORS"][] =  $e->getMessage();
        }

        if($o->isSuccess()){
            $returnVal["VALUE"] = $o->getProductId();
            $returnVal["SUCCESS"] = 1;
        }
        return $returnVal;
    }

    public function Available(){
        return ['update'];
    }
}