<?php


namespace Kocmo\Exchange\Bx;

class Property extends Helper
{
    protected $prepareProperties = [];
    protected $props = [];
    protected $propsEnum = [];

    public function __construct()
    {
        try{
            \Bitrix\Main\Loader::includeModule('iblock');

            $treeBuilder = new \Kocmo\Exchange\Tree\Property();
            parent::__construct($treeBuilder);
            $this->prepareProperties();

            $res = \Bitrix\Iblock\PropertyTable::getList( ['filter' => ["IBLOCK_ID"=> $this->arParams['IBLOCK_CATALOG_ID'], "ACTIVE" => 'Y'] ] );

            while( $fields = $res->fetch() ){
                $this->props[$fields['XML_ID']] = [
                    "ID" => $fields['ID'],
                    "CODE" => $fields['CODE'],
                    "NAME" => $fields['NAME'],
                    "PROPERTY_TYPE" => $fields['PROPERTY_TYPE'],
                    "MULTIPLE" => $fields['MULTIPLE'],
                ];
            }

            $property_enums = \CIBlockPropertyEnum::GetList([], Array("IBLOCK_ID" => $this->arParams['IBLOCK_CATALOG_ID']));

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

                $props[$item['XML_ID']] = $item;
            }

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
                $arFields = $this->getDefaultArFields($value);

                $propId = $this->props[$arFields['XML_ID']]['ID'];
                if( intval($propId) > 0) {
                    unset($arFields['NAME']);
                    $result = \Bitrix\Iblock\PropertyTable::update(intval($propId), $arFields);

                    if ($result->isSuccess()) {

                        if ($arFields["PROPERTY_TYPE"] == 'L') {
                            $this->addEnum($arFields["XML_ID"], $result->getId());
                        }
                    }
                }
            }
        }
        $this->status = 'end';
        return true;
    }

    protected function checkProp($xmlId){

        if(isset($this->props[$xmlId])){
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