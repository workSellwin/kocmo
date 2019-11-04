<?php


namespace Kocmo\Exchange\Bx;


class Store extends Helper
{
    function __construct()
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Store();
        parent::__construct($treeBuilder);
    }

    public function update()
    {

        $stores = $this->getStore();
        $arReq = $this->treeBuilder->getRequestArr();

        foreach ($arReq as $store) {
            if (!isset($stores[$store [$this->arParams['ID']]])) {

                try {
                    $w = \Bitrix\Catalog\StoreTable::add([
                        "TITLE" => $store[$this->arParams['NAME']],
                        "CODE" => $this->getCode($store [$this->arParams['NAME']]),
                        "XML_ID" => $store[$this->arParams['ID']],
                        "ADDRESS" => $store[$this->arParams['ADDRESS']],
                    ]);
                    //pr($w->getErrors());
                } catch (\Exception $e) {
                    pr($e->getMessage());
                }
            }
        }
        return true;
    }

    private function getStore(){

        $stores = \Bitrix\Catalog\StoreTable::getlist([])->fetchAll();
        $stores = array_column($stores, NULL, "XML_ID");
        return $stores;
    }
}