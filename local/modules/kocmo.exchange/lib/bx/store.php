<?php


namespace Kocmo\Exchange\Bx;


class Store extends Helper
{
    function __construct($catalogId)
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Store();
        parent::__construct($treeBuilder, $catalogId);
    }

    public function update()
    {

        $stores = $this->getStore();
        $arReq = $this->treeBuilder->getRequestArr();
pr($stores);
pr($arReq);
        foreach ($arReq as $store) {
            if (!isset($stores[$store [$this->arParams['ID']]])) {

                try {
                    \Bitrix\Catalog\StoreTable::add([
                        "TITLE" => $store [$this->arParams['NAME']],
                        "CODE" => $this->getCode($store [$this->arParams['NAME']]),
                        "XML_ID" => $store [$this->arParams['ID']],
                        "ADDRESS" => $store [$this->arParams['ADDRESS']],
                    ]);
                } catch (\Exception $e) {

                }
            }
        }
    }

    private function getStore(){

        $stores = \Bitrix\Catalog\StoreTable::getlist([])->fetchAll();
        $stores = array_column($stores, NULL, "XML_ID");
        return $stores;
    }
}