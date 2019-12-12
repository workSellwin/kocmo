<?php


namespace Kocmo\Exchange\Bx;
use \Bitrix\Catalog;


class Store extends Helper
{
    function __construct()
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Store();
        parent::__construct($treeBuilder);
    }

    public function update() : bool{

        $stores = $this->getStore();
        $arReq = $this->treeBuilder->getRequestArr();

        foreach ($arReq as $store) {

            if (!isset($stores[$store [$this->arParams['ID']]])) {

                try {
                    $w = Catalog\StoreTable::add([
                        "TITLE" => $store[$this->arParams['NAME']],
                        "CODE" => $this->utils->getCode($store [$this->arParams['NAME']]),
                        "XML_ID" => $store[$this->arParams['ID']],
                        "ADDRESS" => $store[$this->arParams['ADDRESS']],
                    ]);
                    //pr($w->getErrors());
                } catch (\Exception $e) {
                    pr($e->getMessage());
                }
            }
        }
        $this->status = 'end';
        return true;
    }

    private function getStore(){

        $stores = Catalog\StoreTable::getlist([])->fetchAll();
        $stores = array_column($stores, NULL, "XML_ID");
        return $stores;
    }
}