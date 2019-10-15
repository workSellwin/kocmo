<?php


namespace Kocmo\Exchange\Bx;


class Property extends Helper
{
    protected $prepareProperties = [];

    public function __construct($catalogId)
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Property();
        parent::__construct($treeBuilder, $catalogId);
        $this->prepareProperties();
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

    public function updateProperty(){

        $ibp = new \CIBlockProperty;

        foreach( $this->prepareProperties as $key => $value ){
            if( !$this->checkProp($key) ){
                $arFields = $this->getDefaultArFields( $value );

                $ibp->Add( $arFields );
            }
        }
       return true;
    }

    protected function checkProp($code){

        if( !is_string($code)){
            return false;
        }
        $res = \CIBlockProperty::GetList([], ["IBLOCK_ID"=>$this->catalogId, "CODE" => $code]);
        if( $fields = $res->fetch() ){
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