<?php


namespace Kocmo\Exchange\Bx;

class Property extends Helper
{
    protected $prepareProperties = [];
    protected $props = [];
    protected $propsEnum = [];

    public function __construct($catalogId)
    {
        try{
            \Bitrix\Main\Loader::includeModule('iblock');

            $treeBuilder = new \Kocmo\Exchange\Tree\Property();
            parent::__construct($treeBuilder, $catalogId);
            $this->prepareProperties();

            $res = \Bitrix\Iblock\PropertyTable::getList( ['filter' => ["IBLOCK_ID"=> $catalogId, "ACTIVE" => 'Y'] ] );

            while( $fields = $res->fetch() ){
                $this->props[$fields['CODE']] = [
                    "ID" => $fields['ID'],
                    "CODE" => $fields['CODE'],
                    "NAME" => $fields['NAME'],
                    "PROPERTY_TYPE" => $fields['PROPERTY_TYPE'],
                    "MULTIPLE" => $fields['MULTIPLE'],
                ];
            }

            $property_enums = \CIBlockPropertyEnum::GetList([], Array("IBLOCK_ID" => $catalogId));

            while($enum_fields = $property_enums->GetNext()){
                if($this->checkRef($enum_fields['XML_ID'])) {
                    $this->propsEnum[$enum_fields['XML_ID']] = $enum_fields;
                }
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

                $code = $this->getCode($item['NAME']);
                $item["CODE"] = $code;
                $item["XML_ID"] = $item["EXTERNAL_ID"] = $item['UID'];
                unset($item['UID']);

                $props[$code] = $item;
            }
            //pr($props);die();
            $this->prepareProperties = $props;
        }
    }

    public function update(){

        foreach( $this->prepareProperties as $key => $value ){

            if( !$this->checkProp($key) ){

                try {
                    $arFields = $this->getDefaultArFields($value);
                    $result = \Bitrix\Iblock\PropertyTable::add($arFields);

                    if ($result->isSuccess()) {

                        if ($arFields["PROPERTY_TYPE"] == 'L') {
                            $this->addEnum($arFields["XML_ID"], $result->getId());
                        }
                    }
                } catch (\Exception $e){

                }
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
        if(isset($this->props[$code])){
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
            "XML_ID" => $options['XML_ID'],
            "PROPERTY_TYPE" => $options['PROPERTY_TYPE'],
            "IBLOCK_ID" => $this->catalogId,//номер вашего инфоблока
            //"LIST_TYPE" => "L",
            "MULTIPLE" => $options['MULTIPLE'] == "Y" ? "Y" : "N",
        ];
    }

    protected function fillPropertyList(){

    }

    protected function addEnum($xml_id, $propId){

        $arEnum = $this->treeBuilder->getEnum($xml_id);

        if( count($arEnum)){

            //$this->addEnumInDb($xml_id, $arEnum);
            $ibpenum = new \CIBlockPropertyEnum;

            foreach ($arEnum as $enum){
                if( !isset($this->propsEnum[$enum[$this->arParams['ID']]]) ){
                    $enumId = $ibpenum->Add([
                        'PROPERTY_ID' => $propId,
                        'VALUE' => $enum[$this->arParams['NAME']],
                        "XML_ID" => $enum[$this->arParams['ID']]
                    ]);
                }
            }
        }
    }

    protected function addEnumInDb($xml_id, $arEnum){

        if( !is_array($arEnum) ){
            return false;
        }
        try {
            $result = \Kocmo\Exchange\PropsTable::add([
                "UID" => $xml_id,
                "JSON" => json_encode($arEnum),
            ]);
        }catch(\Exception $e){

        }
        return true;
    }

    protected function updateEnumInDb($xml_id, $arEnum){

        if( !is_array($arEnum) ){
            return false;
        }
        try {
            $res = \Kocmo\Exchange\PropsTable::getlist(["limit" => 1, "filter" => ["UID" => $xml_id]]);

            if($row = $res->fetch() ){

                $result = \Kocmo\Exchange\PropsTable::update($row["ID"], [
                    "UID" => $xml_id,
                    "JSON" => json_encode($arEnum),
                ]);
            }

        }catch(\Exception $e){

        }
        return true;
    }
}