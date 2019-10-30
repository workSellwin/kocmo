<?php


namespace Kocmo\Exchange\Bx;


class Typeprice extends Helper
{
    function __construct($catalogId)
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Typeprice();
        parent::__construct($treeBuilder, $catalogId);
    }

    public function update(){

        $typePrice = $this->getTypePrice();
        $arReq = $this->treeBuilder->getRequestArr();

        foreach($arReq as $key => $tp){
            if( !isset($typePrice[ $tp [$this->arParams['ID'] ] ]) ){

                try {
                    \Bitrix\Catalog\GroupTable::add([
                        "NAME" => $this->getCode($tp [$this->arParams['NAME'] ]),
                        "XML_ID" => $tp [$this->arParams['ID'] ],
                    ]);
                }
                catch(\Exception $e){

                }
            }
        }
    }

    private function getTypePrice(){

        $priceType = \Bitrix\Catalog\GroupTable::getlist([])->fetchAll();
        $priceType = array_column($priceType, NULL, "XML_ID");
        return $priceType;
    }
}