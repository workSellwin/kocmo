<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 06.10.2019
 * Time: 13:55
 */

namespace Kocmo\Exchange\Bx;

use \Bitrix\Catalog,
    \Kocmo\Exchange,
    \Bitrix\Main\DB;

class Product extends Helper
{
    private $productMatchXmlId = [];
    protected $offerMatchXmlId = [];
    protected $arProperty = [];
    protected $arEnumMatch = [];
    protected $defaultLimit = 2000;
    protected $ENTRY = 'product';

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $treeBuilder = new Exchange\Tree\Product();
        parent::__construct($treeBuilder);
        unset($treeBuilder);
    }

    public function update(): bool
    {
        $this->startTimestamp = time();
        $oElement = new \CIBlockElement();
        $this->setMatchXmlId();
        $end = true;

        foreach ($this->productsGenerator() as $row) {

            $end = false;

            if ($this->checkTime()) {
                return $end;
            }

            $rowId = $row['ROW_ID'];
            $detailPic = $row['DETAIL_PICTURE'];
            unset($row['DETAIL_PICTURE'], $row['ROW_ID']);

            try {
                $id = $this->addProduct($row, $oElement, $rowId);
            } catch (\Exception $e) {
                $this->errors[] = $e;
            }

            if ($id > 0 && $this->utils->checkRef($detailPic)) {

                try {
                    Exchange\ProductImageTable::add(["IMG_GUI" => $detailPic, "PRODUCT_ID" => $id]);
                } catch (DB\SqlQueryException $e) {
                    //например попытка добавить с не уникальным IMG_GUI
                } catch (\Exception $e) {

                }
            }
        }

        if ($end) {
            $this->status = 'end';
        } else {
            $this->status = 'run';
        }
        return $end;
    }

    public function updateOne($row)
    {

        $this->setMatchXmlId();
        $sectionsMatch = $this->getAllSectionsXmlId();
        $this->setEnumMatch();

        $row = $this->treeBuilder->prepareItem($row);

        $props = [];

        if (count($row[$this->arParams['PROPERTIES']])) {

            foreach ($row[$this->arParams['PROPERTIES']] as $key => $prop) {

                $code = $this->utils->getCode($key);

                if ($this->utils->checkRef($prop) && isset($this->arEnumMatch[$prop])) {
                    $value = $this->arEnumMatch[$prop];
                } elseif (is_array($prop)) {

                    $value = [];

                    foreach ($prop as $v) {

                        if (isset($this->arEnumMatch[$v])) {
                            $value[] = $this->arEnumMatch[$v];
                        }
                    }
                } else {
                    $value = $prop;
                }

                $props[$code] = $value;
            }
        }

        $arrIblockSectionId = [];

        if (is_array($row[$this->arParams['PARENT_ID']])) {

            foreach ($row[$this->arParams['PARENT_ID']] as $sectionXmlId) {
                $arrIblockSectionId[] = $sectionsMatch[$sectionXmlId];
            }
        }

        $arFields = array(
            //"ACTIVE" => "Y",
            "IBLOCK_ID" => $this->catalogId,
            "IBLOCK_SECTION" => $arrIblockSectionId,
            "XML_ID" => $row[$this->arParams['ID']],
            "NAME" => $row[$this->arParams['FULL_NAME']],
            "CODE" => \CUtil::translit($row[$this->arParams['NAME']], 'ru') . time(),
            "DETAIL_TEXT" => $row[$this->arParams['DESCRIPTION']],
            "PROPERTY_VALUES" => $props
        );

        if (!empty($row[$this->arParams['PIC_FILE']])) {

            $objImg = new Image();
            $arPic = $objImg->getPhoto($row[$this->arParams['PIC_FILE']]);
            $arFields["DETAIL_PICTURE"] = $arPic;
        }

        $id = 0;

        try {
            $id = $this->addProduct($arFields);
        } catch (\Exception $e) {
            $this->errors[] = $e;
        }

        return $id;
    }

    public function productsGenerator()
    {

        if (Exchange\DataTable::getCount() == 0) {
            return false;
        }

        $iterator = Exchange\DataTable::getList([
            'filter' => ['ENTRY' => $this->ENTRY],
            'limit' => $this->defaultLimit
        ]);

        $sectionsMatch = $this->getAllSectionsXmlId();
        $this->setEnumMatch();

        while ($row = $iterator->fetch()) {

            $rowId = $row['ID'];
            $row = json_decode($row['JSON'], true);
            $props = [];

            if (count($row[$this->arParams['PROPERTIES']])) {

                foreach ($row[$this->arParams['PROPERTIES']] as $key => $prop) {

                    $code = $this->utils->getCode($key);

                    if ($this->utils->checkRef($prop) && isset($this->arEnumMatch[$prop])) {
                        $value = $this->arEnumMatch[$prop];
                    } elseif (is_array($prop)) {

                        $value = [];

                        foreach ($prop as $v) {

                            if (isset($this->arEnumMatch[$v])) {
                                $value[] = $this->arEnumMatch[$v];
                            }
                        }
                    } else {
                        $value = $prop;
                    }

                    $props[$code] = $value;
                }
            }

            if( $this->utils->checkRef($row["PARENT"]) ) {

                $parentId = $this->getProductFromIBlock($row["PARENT"]);

                if(intval($parentId) > 0) {
                    $props[$this->arParams['PROP_REF']] = $parentId;
                }
            }

            $arIBlockSectionId = [];

            if (is_array($row[$this->arParams['PARENT_ID']])) {

                foreach ($row[$this->arParams['PARENT_ID']] as $sectionXmlId) {
                    $arIBlockSectionId[] = $sectionsMatch[$sectionXmlId];
                }
            }

            $arFields = array(
                "ROW_ID" => $rowId,
                "ACTIVE" => "Y",
                "IBLOCK_ID" => $this->catalogId,
                "IBLOCK_SECTION" => $arIBlockSectionId,
                "XML_ID" => $row[$this->arParams['ID']],
                "NAME" => $row[$this->arParams['FULL_NAME']],
                "CODE" => \CUtil::translit($row[$this->arParams['NAME']], 'ru') . time(),
                "DETAIL_TEXT" => $row[$this->arParams['DESCRIPTION']],
                "DETAIL_PICTURE" => $row[$this->arParams['PIC_FILE']],
                "PROPERTY_VALUES" => $props
            );
            yield $arFields;
        }
    }

    protected function setEnumMatch()
    {

        $property_enums = \CIBlockPropertyEnum::GetList([], ["IBLOCK_ID" => $this->catalogId]);

        while ($enum_fields = $property_enums->GetNext()) {
            if (!$this->utils->checkRef($enum_fields['XML_ID'])) {
                continue;
            }
            $this->arEnumMatch[$enum_fields['XML_ID']] = $enum_fields['ID'];
        }
    }

    protected function getAllSectionsXmlId()
    {

        $entity = \Bitrix\Iblock\Model\Section::compileEntityByIblock($this->catalogId);

        $iterator = $entity::getList([
            "filter" => ["IBLOCK_ID" => $this->catalogId],
            "select" => ["XML_ID", "ID"]
        ]);

        $sections = [];

        while ($row = $iterator->fetch()) {
            $sections[$row['XML_ID']] = $row['ID'];
        }

        return $sections;
    }

    /**
     * @param array $arFields
     * @param bool $oElement
     * @param bool $rowId
     * @return int
     * @throws \Exception
     */
    public function addProduct(array $arFields, $oElement = false, $rowId = false)
    {
        if ($oElement == false) {
            $oElement = new \CIBlockElement();
        }

        if( empty($arFields["PROPERTY_VALUES"]["CML2_LINK"]) ){
            $prod = $this->getProductFromIBlock($arFields["XML_ID"]);
        }
        else{
            $parentId = $arFields["PROPERTY_VALUES"]["CML2_LINK"];
            $tempVal = array_search($parentId, $this->productMatchXmlId);

            if( strpos($tempVal, 'p_' ) !== 0) {
                $parentXmlId = 'p_' . $tempVal;

                if (!isset($this->productMatchXmlId[$parentXmlId])) {

                    $el = new \CIBlockElement;
                    $el->Update($parentId, ["XML_ID" => $parentXmlId]);
                    $this->productMatchXmlId[$parentXmlId] = $parentId;
                }
            }
            $prod = $this->getOfferFromIBlock($arFields["XML_ID"]);
        }

        $id = 0;

        if ($prod === false) {

            $id = $oElement->Add($arFields);

            if (intval($id) > 0) {

                if (intval($rowId) > 0) {
                    $deleteResult = Exchange\DataTable::delete($rowId);
                }

                Catalog\Model\Product::add(['fields' => ['ID' => $id]]);//add to b_catalog_product
            } else {
                throw new \Exception("Error: " . $oElement->LAST_ERROR);
            }

        }
        else {
            unset($arFields["CODE"]);
            if ($oElement->Update($prod, $arFields)) {

                $id = $prod;

                if (intval($rowId) > 0) {
                    $deleteResult = Exchange\DataTable::delete($rowId);
                }
            }
        }
        return intval($id);
    }

    private function getProductFromIBlock($xml_id)
    {

        if (!is_string($xml_id)) {
            return false;
        }

        if (isset($this->productMatchXmlId[$xml_id])) {
            return $this->productMatchXmlId[$xml_id];
        } else {
            return false;
        }
    }

    private function getOfferFromIBlock($xml_id)
    {

        if (!is_string($xml_id)) {
            return false;
        }

        if (isset($this->offerMatchXmlId[$xml_id])) {
            return $this->offerMatchXmlId[$xml_id];
        } else {
            return false;
        }
    }

    private function setMatchXmlId()
    {

        if($this->catalogId == $this->arParams['IBLOCK_CATALOG_ID']){
            $iBlockIds = $this->catalogId;
        }
        else{
            $iBlockIds = [$this->catalogId, $this->arParams['IBLOCK_CATALOG_ID']];
        }

        $res = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => $iBlockIds],
            false,
            false,
            ["ID", "IBLOCK_ID", "XML_ID"]
        );

        while ($fields = $res->fetch()) {
            if($fields["IBLOCK_ID"] == $this->arParams['IBLOCK_CATALOG_ID']) {
                $this->productMatchXmlId[$fields["XML_ID"]] = $fields["ID"];
            }
            else{
                $this->offerMatchXmlId[$fields["XML_ID"]] = $fields["ID"];
            }
        }
    }
}