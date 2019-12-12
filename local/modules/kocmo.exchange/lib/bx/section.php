<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 06.10.2019
 * Time: 13:56
 */

namespace Kocmo\Exchange\Bx;


/**
 * Class Section
 * @package Kocmo\Exchange\Bx
 */
class Section extends Helper
{
    private $conformityHash = [];//xml_id => id

    /**
     * BxSection constructor.
     * @param $catalogId
     */
    public function __construct()
    {
        /** @var \Kocmo\Exchange\Tree\Section $this->treeBuilder*/
        $treeBuilder = new \Kocmo\Exchange\Tree\Section();
        parent::__construct($treeBuilder);
    }

    public function update() : bool {
        /** @var \Kocmo\Exchange\Tree\Section $this->treeBuilder*/

        if (is_array( $this->treeBuilder->getTree() )) {

            $allXmlId = $this->treeBuilder->getAllXmlId();

            if (count($allXmlId)) {
                $res = \CIBlockSection::GetList(
                    [],
                    ["XML_ID" => $allXmlId, "IBLOCK_ID" => $this->catalogId],
                    false,
                    ['ID', 'IBLOCK_ID', 'NAME', 'CODE', 'XML_ID', 'DEPTH_LEVEL']
                );

                while ($fields = $res->fetch()) {
                    $this->conformityHash[ $fields['XML_ID'] ] = $fields['ID'];
                }
            }
            $cIBlockSection = new \CIBlockSection;

            foreach ($this->treeBuilder->structGenerator($this->treeBuilder->getTree()) as $section) {

                if ( isset($this->conformityHash[ $section[$this->arParams['ID']] ]) ) {

                    $section['ID'] = $this->conformityHash[$section['UID']];
                    $this->updateSection($section, $cIBlockSection);
                }
                else{
                    $this->addSection($section, $cIBlockSection);
                }
            }

            $this->status = 'end';

            return true;
        } else {
            throw new \Error("tree not found");
        }
    }

    private function addSection(array $arFieldsFrom1C, $cIBlockSection = false)
    {
        $arFields = $this->prepareFields($arFieldsFrom1C);

        if ($arFields == false) {
            throw new \Error("arFields incorrect");
        }

        if (!$cIBlockSection) {
            $cIBlockSection = new \CIBlockSection;
        }

        $id = $cIBlockSection->Add($arFields);

        if (intval($id) == 0) {
//            $this->error[] = $cIBlockSection->LAST_ERROR;
//            return false;
            throw new \Error($cIBlockSection->LAST_ERROR);
        } else {
            $this->conformityHash[$arFieldsFrom1C[$this->arParams['ID']]] = $id;
        }
        return true;
    }

    protected function updateSection(array $arFieldsFrom1C, $cIBlockSection = false){

        $arFields = $this->prepareFields($arFieldsFrom1C);

        if ($arFields == false) {
            throw new \Error("arFields incorrect");
        }

        if (!$cIBlockSection) {
            $cIBlockSection = new \CIBlockSection;
        }

        $success = $cIBlockSection->Update($arFieldsFrom1C['ID'], $arFields);

        if (!$success) {

            throw new \Error($cIBlockSection->LAST_ERROR);
        } else {
            return true;
        }
    }

    private function prepareFields(array $from1CArr)
    {
        $neededFields = [
            'ACTIVE' => 'Y',
            'IBLOCK_SECTION_ID' => $this->conformityHash[$from1CArr[$this->arParams['PARENT_ID']]],
            'IBLOCK_ID' => $this->catalogId,
            'NAME' => $from1CArr[$this->arParams['NAME']],
            'SORT' => 500,
            'XML_ID' => $from1CArr[$this->arParams['ID']],
            'DEPTH_LEVEL' => $from1CArr[$this->arParams['DEPTH_LEVEL']],
            'CODE' => \CUtil::translit($from1CArr[$this->arParams['NAME']], 'ru')
        ];

        return $neededFields;
    }
}