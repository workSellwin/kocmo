<?php


namespace Lui\Kocmo\Action;


use Bitrix\Main\LoaderException,
    Lui\Kocmo\Interfaces\ActionsInterfaces,
    \Bitrix\Main\Loader,
    Kocmo\Exchange,
    \Bitrix\Main\Config;

class CatalogUpdate implements ActionsInterfaces
{
    private $returnVal = ["SUCCESS" => 0, "VALUE" => 0, "ERRORS" => []];

    public function __construct()
    {
        try {
            Loader::includeModule('kocmo.exchange');
        } catch( LoaderException $e){
            $this->returnVal["ERRORS"][] =  $e->getMessage();
        }
    }

    public function updSections($param){
        return $this->updateEntity(0, $param);
    }

    public function updProperties($param){
        return $this->updateEntity(10, $param);
    }

    public function updPrices($param){
        return $this->updateEntity(80, $param);
    }

    private function updateEntity($stage, $param){

        $param = json_decode($param, true);

        $bx = \Kocmo\Exchange\StaticFactory::factory($stage);

        if( !$bx->update() ){
            array_merge($this->returnVal["ERRORS"], $bx->getErrors());
        }
        else{
            $this->returnVal["SUCCESS"] = 1;
        }

        return $this->returnVal;
    }

    public function updRests($param){

        $module = 'kocmo.exchange';
        Config\Option::set($module, 'LAST_STORE_ID', '');

        $agentId = \CAgent::AddAgent(
            'Kocmo\\Exchange\\Agents::start(60);',
            $module,
            "N",
            60
        );

        if(intval($agentId) > 0){
            $this->returnVal["SUCCESS"] = 1;
        }
        else{
            $this->returnVal["ERRORS"][] =  'Ошибка при создании агента';
        }
        return $this->returnVal;
    }

    public function updRest($param){

        $param = json_decode($param, true);

        if( intval($param['LAST_STORE_ID']) < 1){
            $this->returnVal["ERRORS"][] =  'un correct param';
            return $this->returnVal;
        }

        $module = 'kocmo.exchange';
        Config\Option::set($module, 'LAST_STORE_ID', $param['LAST_STORE_ID']);

        $bx = Exchange\StaticFactory::factory(60);
        $bx->update();
        Config\Option::set($module, 'LAST_STORE_ID', '');
        $this->returnVal["SUCCESS"] = 1;
        return $this->returnVal;
    }

    public function updBrands($param){
        $bx = Exchange\StaticFactory::factory(100);
        $bx->updateBrands();
        $this->returnVal["SUCCESS"] = 1;
        return $this->returnVal;
    }

    public function Available(){
        return ['updSections', 'updProperties', 'updPrices', 'updRests', 'updBrands', 'updRest'];
    }
}