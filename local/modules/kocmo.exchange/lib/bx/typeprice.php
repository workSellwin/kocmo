<?php


namespace Kocmo\Exchange\Bx;
use \Bitrix\Catalog;


class Typeprice extends Helper
{
    function __construct()
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Typeprice();
        parent::__construct($treeBuilder);
    }

    public function update() : bool {

        $typePrice = $this->getTypePrice();
        $arReq = $this->treeBuilder->getRequestArr();

        foreach($arReq as $key => $tp){

            if( !isset($typePrice[ $tp [$this->arParams['ID'] ] ]) ){

                try {
                    Catalog\GroupTable::add([
                        "NAME" => $this->utils->getCode($tp [$this->arParams['NAME'] ]),
                        "XML_ID" => $tp [$this->arParams['ID'] ],
                        //"SORT" => 123,
                    ]);
                }
                catch(\Exception $e){

                }
            }
        }
        $this->status = 'end';
        return true;
    }

    private function getTypePrice(){

        $priceType = Catalog\GroupTable::getlist([])->fetchAll();
        $priceType = array_column($priceType, NULL, "XML_ID");
        return $priceType;
    }
}