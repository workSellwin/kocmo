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

    private $treeBuilder = null;
    private $error = [];
    private $catalogId = false;
    private $conformityHash = [];

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
                $res = \CIBlockSection::GetList([], ["XML_ID" => $allXmlId, false, ['ID', 'IBLOCK_ID', 'NAME', 'CODE', 'XML_ID', 'DEPTH_LEVEL']]);
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

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

//    private function structGenerator( $tree ){
//
//        foreach( $tree as &$section ){
//
//            yield $this->prepareSection($section);
//            if( count($section[self::CHILDREN]) ){
//                yield from $this->structGenerator($section[self::CHILDREN]);
//            }
//        }
//    }
//
//    private function prepareSection(&$section){
//
//        $tempArr = [];
//
//        $allowedFields = [
//            self::ID, self::PARENT_ID, self::NAME, self::DEPTH_LEVEL
//        ];
//
//        foreach( $section as $k => $fld){
//            if( in_array($k, $allowedFields) ){
//                $tempArr[$k] = $fld;
//            }
//        }
//        return $tempArr;
//    }
}