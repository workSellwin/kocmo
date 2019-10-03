<?php

namespace Asdrubael\Utils;
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

class BxStructureCreater
{
    /**
     * BxStructureCreater constructor.
     */
    const PARENT_ID = 'Родитель';
    const ID = "UID";
    const NAME = "Наименование";
    const CHILDREN = 'CHILDREN';
    const DEPTH_LEVEL = 'DEPTH_LEVEL';
    const SECTION_ACTIVE = 'Y';

    private $tree = null;
    private $error = [];
    private $catalogId = false;
    private $conformityHash = [];

    public function __construct( array $tree, $catalogId )
    {
        if (\Bitrix\Main\Loader::includeModule('iblock')) {
            $this->tree = $tree;

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

    /**
     * @return array
     */
    public function getTree()
    {
        return $this->tree;
    }

    public function createStruct(){

        if(is_array($this->tree)){

            $cIBlockSection = new \CIBlockSection;

            foreach($this->structGenerator($this->tree) as $section){
                //echo '<pre>' . print_r($section, true) . '</pre>';
                $this->addSection($section, $cIBlockSection);
            }
            //echo '<pre>' . print_r($this->conformityHash, true) . '</pre>';
        }
        else{
            $this->error[] = "tree not found";
            return false;
        }
    }

    private function addSection( array $arFieldsFrom1C, $cIBlockSection = false ){

        $arFields = $this->prepareFields($arFieldsFrom1C);
        echo '<pre>' . print_r($arFields, true) . '</pre>';
        if($arFields == false){
            $this->error[] = "arFields incorrect";
            return false;
        }
        if( !$cIBlockSection ){
            $cIBlockSection = new \CIBlockSection;
        }

        //$id = $cIBlockSection->Add($arFields);
$id = 1;
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

    private function structGenerator( $tree ){

        foreach( $tree as &$section ){

            yield $this->prepareSection($section);
            if( count($section['CHILDREN']) ){
                yield from $this->structGenerator($section['CHILDREN']);
            }
        }
    }

    private function prepareSection(&$section){

        $tempArr = [];

        $allowedFields = [
            self::ID, self::PARENT_ID, self::NAME, self::DEPTH_LEVEL
        ];

        foreach( $section as $k => $fld){
            if( in_array($k, $allowedFields) ){
                $tempArr[$k] = $fld;
            }
        }
        return $tempArr;
    }
}