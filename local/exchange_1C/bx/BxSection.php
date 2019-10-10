<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 06.10.2019
 * Time: 13:56
 */

namespace Asdrubael\Utils;


class BxSection extends BxHelper
{
    const SECTION_ACTIVE = 'Y';

    private $conformityHash = [];

    /**
     * BxSection constructor.
     * @param treeHandler $treeBuilder
     * @param $catalogId
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct(treeHandler $treeBuilder, $catalogId)
    {
        parent::__construct($treeBuilder, $catalogId);
    }

    public function createStruct()
    {
        /** @var treeHandler $this->treeBuilder*/

        if (is_array( $this->treeBuilder->getTree() )) {

            $allXmlId = $this->treeBuilder->getAllXmlId();

            if (count($allXmlId)) {
                $res = \CIBlockSection::GetList(
                    [],
                    ["XML_ID" => $allXmlId, "IBLOCK_ID" => $this->catalogId],
                    false,
                    ['ID', 'IBLOCK_ID', 'NAME', 'CODE', 'XML_ID', 'DEPTH_LEVEL']
                );
            }

            $xmlIdFromReq = [];

            while ($fields = $res->fetch()) {
                $xmlIdFromReq[] = $fields['XML_ID'];
            }

            $cIBlockSection = new \CIBlockSection;

            foreach ($this->treeBuilder->structGenerator($this->treeBuilder->getTree()) as $section) {

                if (in_array($section[self::ID], $xmlIdFromReq)) {
                    continue;
                }
                $this->addSection($section, $cIBlockSection);
            }
            return true;
        } else {
            $this->error[] = "tree not found";
            return false;
        }
    }

    private function addSection(array $arFieldsFrom1C, $cIBlockSection = false)
    {

        $arFields = $this->prepareFields($arFieldsFrom1C);
        //echo '<pre>' . print_r($arFieldsFrom1C, true) . '</pre>';
        if ($arFields == false) {
            $this->error[] = "arFields incorrect";
            return false;
        }
        if (!$cIBlockSection) {
            $cIBlockSection = new \CIBlockSection;
        }

        $id = $cIBlockSection->Add($arFields);

        if (intval($id) == 0) {
            $this->error[] = $cIBlockSection->LAST_ERROR;
            return false;
        } else {
            $this->conformityHash[$arFieldsFrom1C[self::ID]] = $id;
        }
        return true;
    }

    private function prepareFields(array $from1CArr)
    {

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
}