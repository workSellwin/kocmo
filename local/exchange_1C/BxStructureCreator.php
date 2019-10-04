<?php

namespace Asdrubael\Utils;
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

class BxStructureCreator
{
    /**
     * BxStructureCreator constructor.
     */
    const PARENT_ID = 'Родитель';
    const ID = "UID";
    const NAME = "Наименование";
    const CHILDREN = 'CHILDREN';
    const DEPTH_LEVEL = 'DEPTH_LEVEL';
    const SECTION_ACTIVE = 'Y';
    const FULL_NAME = "НаименованиеПолное";
    const PROPERTIES = "Свойства";
    const DESCRIPTION = 'Описание';
    const DETAIL_PICTURE = 'ФайлКартинки';

    private $treeBuilder = null;
    private $error = [];
    private $catalogId = false;
    private $conformityHash = [];
    private $matchXmlId = [];

    public function __construct(treeHandler $treeBuilder, $catalogId )
    {
        if (\Bitrix\Main\Loader::includeModule('iblock')) {
            $this->treeBuilder = $treeBuilder;

            if( intval($catalogId) > 0){
                $this->catalogId = $catalogId;
            }
            else{
                die('catalog id empty!');
            }

        } else {
            die('module iblock not find!');
        }
    }

    public function createStruct(){

        if(is_array($this->treeBuilder->getTree())){

            $allXmlId = $this->treeBuilder->getAllXmlId();

            if( count($allXmlId) ) {
                $res = \CIBlockSection::GetList(
                    [],
                    ["XML_ID" => $allXmlId, "IBLOCK_ID" => $this->catalogId],
                    false,
                    ['ID', 'IBLOCK_ID', 'NAME', 'CODE', 'XML_ID', 'DEPTH_LEVEL']
                );
            }

            $xmlIdFromReq = [];

            while( $fields = $res->fetch() ){
                $xmlIdFromReq[] = $fields['XML_ID'];
            }

            //echo '<pre>' . print_r($xmlIdFromReq, true) . '</pre>';
            $cIBlockSection = new \CIBlockSection;

            foreach($this->treeBuilder->structGenerator($this->treeBuilder->getTree()) as $section){
                //echo '<pre>' . print_r($section, true) . '</pre>';
                if( in_array($section[self::ID], $xmlIdFromReq) ){
                    continue;
                }
                //echo '<pre>' . print_r($section, true) . '</pre>';
                $this->addSection($section, $cIBlockSection);
            }
            return true;
        }
        else{
            $this->error[] = "tree not found";
            return false;
        }
    }

    private function addSection( array $arFieldsFrom1C, $cIBlockSection = false ){

        $arFields = $this->prepareFields($arFieldsFrom1C);
        echo '<pre>' . print_r($arFieldsFrom1C, true) . '</pre>';
        if($arFields == false){
            $this->error[] = "arFields incorrect";
            return false;
        }
        if( !$cIBlockSection ){
            $cIBlockSection = new \CIBlockSection;
        }

        $id = $cIBlockSection->Add($arFields);
        //$id = 1;
        if( intval($id) == 0 ){
            $this->error[] = $cIBlockSection->LAST_ERROR;
            return false;
        }
        else{
            $this->conformityHash[$arFieldsFrom1C[self::ID]] = $id;
        }
        return true;
    }

    private function prepareFields( array $from1CArr ){

        $neededFields = [
            'ACTIVE' => self::SECTION_ACTIVE,
            'IBLOCK_SECTION_ID' => $this->conformityHash[$from1CArr[self::PARENT_ID]],
            'IBLOCK_ID' => $this->catalogId,
            'NAME' => $from1CArr[self::NAME],
            'SORT' => 500,
            'XML_ID' => $from1CArr[self::ID],
            'DEPTH_LEVEL' => $from1CArr[self::DEPTH_LEVEL],
            'CODE' => \CUtil::translit($from1CArr[self::NAME], 'ru')
        ];

        return $neededFields;
    }

    public function prepareProducts(){

        $prodReqArr = $this->treeBuilder->getRequestArr();//массив из запроса
        $oElement = new \CIBlockElement();
$counter = 0;
        foreach($this->productsGenerator($prodReqArr) as $arFields){
//echo $counter++;
            $prod = $this->getProducts($arFields["XML_ID"]);

            if($prod === false){
                $id = $oElement->Add($arFields);
            }
            else{
                $oElement->Update($prod['ID'], $arFields);
            }
            echo '<pre>' . print_r( $id, true) . '</pre>';
            echo '<pre>' . print_r( $arFields, true) . '</pre>';
//            if( is_array( $arFields['DETAIL_PICTURE'] ) ) {
//                echo '<pre>' . print_r($arFields, true) . '</pre>';
//                die();
//            }
        }
    }

    private function getProducts( $xml_id ){

        if( !is_string($xml_id) ){
            return false;
        }
        $res = \CIBlockElement::GetList(
            [],
            ["XML_ID" => $xml_id, "IBLOCK_ID" => $this->catalogId],
            false,
            false,
            ["ID", "IBLOCK_ID", "XML_ID", "NAME", "CODE"]
        );

        if( $fields = $res->fetch() ){
            return $fields;
        }
        return false;
    }

    public function addProduct(){

    }

    private function productsGenerator($prodReqArr){
        //echo '<pre>' . print_r( $prodReqArr, true) . '</pre>';//die();
        $productsSectionsId = $this->treeBuilder->getProductParentsXmlId();//xml_id родителей товара
        $this->matchXmlId = $this->getSectionMatch( array_keys($productsSectionsId) );//сопоставленные id и xml_id

        foreach ( $prodReqArr as $prod ){

            $props = [];

            if( count($prod[self::PROPERTIES][0]) ) {

                foreach ($prod[self::PROPERTIES][0] as $key => $prop) {

                    $code = $this->getPropertyCode($key);

                    if( $this->checkRef($prop) ){
                        $value = $this->getFromReferenceBook($key, $prop, $code);
                    }
                    else{
                        $value = $prop;
                    }

                    $props[$code] = $value;
                }
            }

            $arFields = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => $this->catalogId,
                "IBLOCK_SECTION_ID" => $this->matchXmlId[$prod[self::PARENT_ID]],
                "XML_ID" => $prod[self::ID],
                "NAME" => $prod[self::FULL_NAME],
                "CODE" => \CUtil::translit($prod[self::NAME], 'ru') . time(),
                "DETAIL_TEXT" => $prod[self::DESCRIPTION],
                "DETAIL_PICTURE" => $this->getPhoto($prod[self::DETAIL_PICTURE]),
                "PROPERTY_VALUES" => $props
            );

            yield $arFields;
        }
    }

    private function getFromReferenceBook($key, $xml_id, $code){

        $arrProp = [];
        $arrProp[$code] = Array("VALUE" => $this->getEnumId($xml_id, $key, $code) );
        return $arrProp;
    }

    private function getEnumId($xml_id, $key, $code){

        $property_enums = \CIBlockPropertyEnum::GetList([], Array("IBLOCK_ID" => $this->catalogId, "XML_ID" => $xml_id));

        if($enum_fields = $property_enums->GetNext()){
            return $enum_fields['ID'];
        }
        else{
            $value = $this->treeBuilder->getRefValue($key, $xml_id);
            $ibpenum = new \CIBlockPropertyEnum;

            $propId = $this->getPropIdFromCode($code);

            if(intval($propId) > 0) {
                if ($enumId = $ibpenum->Add(['PROPERTY_ID' => $propId, 'VALUE' => $value, "XML_ID" => $xml_id])) {
                    return $enumId;
                }
            }
        }
        return false;
    }

    private function getPropIdFromCode($code){

        $res = \CIBlockProperty::GetByID($code, $this->catalogId);

        if($ar_res = $res->GetNext()){
            return $ar_res['ID'];
        }
    }

    private function getPropertyCode($outCode){

        $newStr = "";

        for($i = 0; $i < mb_strlen($outCode); $i++){
            $char = mb_substr($outCode, $i, 1);

            if( strpos('АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ', $char) !== false && $i){
                $newStr .= '_' . $char;
            }
            else{
                $newStr .= $char;
            }
        }

        return \CUtil::translit($newStr, 'ru', ['change_case' => 'U']);
    }

    private function getSectionMatch($allXmlId){

        if( count($allXmlId) ) {
            $res = \CIBlockSection::GetList(
                [],
                ["XML_ID" => $allXmlId, 'IBLOCK_ID' => $this->catalogId],
                false,
                ['ID', 'IBLOCK_ID', 'XML_ID']
            );
        }

        $xmlIdFromReq = [];

        while( $fields = $res->fetch() ){
            $xmlIdFromReq[$fields['XML_ID']] = $fields['ID'];
        }
        return $xmlIdFromReq;
    }

    private function checkRef ($val){

        if( is_string($val) && strlen($val) === 36 && $val != '00000000-0000-0000-0000-000000000000'){
            $arr = explode('-' , $val);

            if(strlen($arr[0]) === 8 && strlen($arr[1]) === 4 && strlen($arr[2]) === 4
                && strlen($arr[3]) === 4 && strlen($arr[4]) === 12){
                return true;
            }
            return false;
        }
        else{
            return false;
        }
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    private function getPhoto($gui)
    {
        if ($this->checkRef($gui)) {

            $base64Img = $this->treeBuilder->getPicture($gui);
            if( !empty($base64Img) ) {
                $fileData = base64_decode($base64Img);
                $fileName = $_SERVER['DOCUMENT_ROOT'] . '/upload/temp/temp-photo.png';
                file_put_contents($fileName, $fileData);

                $file = \CFile::MakeFileArray($fileName);
                $fileSave = \CFile::SaveFile(
                    $file,
                    '/iblock'
                );

                return \CFile::MakeFileArray($fileSave);
            }
        }
        return "";
    }
}