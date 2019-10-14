<?php


namespace Kocmo\Exchange\Bx;


class Property extends Product
{
    protected $prepareProperties = [];

    public function __construct(\Kocmo\Exchange\Tree\Handler $treeBuilder, $catalogId)
    {
        parent::__construct($treeBuilder, $catalogId);
        $this->prepareProperties();
    }

    private function prepareProperties(){

        $reqArr = $this->treeBuilder->getRequestArr();

        if( is_array($reqArr) && count($reqArr) ){

            $props = [];

            foreach ($reqArr as $key => $item){

                $code = $this->getPropertyCode($key);

                if( is_array($item) ){
                    $props[$code] = ['PROPERTY_TYPE' => 'L', 'MULTIPLE' => 'Y', 'NAME' => $key, "CODE" => $code];
                    //$props[$code] = $this->getFromReferenceBook($key, $item, $code);
                }
                elseif( $this->checkRef($item) ){
                    $props[$code] = ['PROPERTY_TYPE' => 'L', 'MULTIPLE' => 'N', 'NAME' => $key, "CODE" => $code];
                    //$props[$code] = $this->getFromReferenceBook($key, $item, $code);
                }
                elseif( is_string($item) && !empty($item)/*&& $item !== '00000000-0000-0000-0000-000000000000'*/){
                    $props[$code] = ['PROPERTY_TYPE' => 'S', 'NAME' => $key, "CODE" => $code];
                    //$props[$code] = $item;
                }
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
       // echo '<pre>' . print_r($this->prepareProperties, true) . '</pre>';die();
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

//    protected function getFromReferenceBook($key, $xml_id, $code)
//    {
//
//        $arrProp = [];
//
//        if( is_array($xml_id) ){
//            $arrProp[$code] = Array("VALUE" => $this->getEnumIdArr($xml_id, $key, $code));
//        }
//        else{
//            $arrProp[$code] = Array("VALUE" => $this->getEnumId($xml_id, $key, $code));
//        }
//
//        return $arrProp;
//    }
//
}