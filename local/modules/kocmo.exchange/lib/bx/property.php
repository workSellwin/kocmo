<?php


namespace Kocmo\Exchange\Bx;


class Property extends Helper
{
    protected $prepareProperties = [];
    protected $issetProps = [];

    public function __construct($catalogId)
    {
        try{
            \Bitrix\Main\Loader::includeModule('iblock');

            $treeBuilder = new \Kocmo\Exchange\Tree\Property();
            parent::__construct($treeBuilder, $catalogId);
            $this->prepareProperties();

            $res = \Bitrix\Iblock\PropertyTable::getList( ['filter' => ["IBLOCK_ID"=> $catalogId, "ACTIVE" => 'Y'] ] );

            while( $fields = $res->fetch() ){
                $this->issetProps[$fields['CODE']] = [
                    "ID" => $fields['ID'],
                    "CODE" => $fields['CODE'],
                    "NAME" => $fields['NAME'],
                    "PROPERTY_TYPE" => $fields['PROPERTY_TYPE'],
                    "MULTIPLE" => $fields['MULTIPLE'],
                ];
            }
        } catch(\Error $error){
            //
        }

    }

    private function prepareProperties(){

        $reqArr = $this->treeBuilder->getRequestArr();

        if( is_array($reqArr) && count($reqArr) ){

            $props = [];

            foreach ($reqArr as $item){

                $code = $this->getPropertyCode($item['NAME']);
                $item["CODE"] = $code;
                $props[$code] = $item;
            }
            $this->prepareProperties = $props;
        }
    }

    public function update(){

        $ibp = new \CIBlockProperty;

        foreach( $this->prepareProperties as $key => $value ){
            if( !$this->checkProp($key) ){
                $arFields = $this->getDefaultArFields( $value );

                $ibp->Add( $arFields );
            }
            else{
                //свойство есть, возможно стоит его обновить
            }
        }
       return true;
    }

    protected function checkProp($code){
//old core
//        if( !is_string($code)){
//            return false;
//        }
//        $res = \CIBlockProperty::GetList([], ["IBLOCK_ID"=>$this->catalogId, "CODE" => $code]);
//        if( $fields = $res->fetch() ){
//            return true;
//        }
//        return false;
        if(isset($this->issetProps[$code])){
            return true;
        }
        return false;
    }

    protected function getDefaultArFields ($options ){
        return  [
            "NAME" => $options['NAME'],
            "ACTIVE" => "Y",
            "SORT" => "500",
            "CODE" => $options['CODE'],
            "PROPERTY_TYPE" => $options['PROPERTY_TYPE'],
            //"USER_TYPE" => "directory",
            "IBLOCK_ID" => $this->catalogId,//номер вашего инфоблока
            //"LIST_TYPE" => "L",
            "MULTIPLE" => $options['MULTIPLE'] == "Y" ? "Y" : "N",
            //"USER_TYPE_SETTINGS" => array("size"=>"1", "width"=>"0", "group"=>"N", "multiple"=>"N", "TABLE_NAME"=>"b_producers")
        ];
    }
}