<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 06.10.2019
 * Time: 13:55
 */

namespace Asdrubael\Utils;


class BxProduct extends BxHelper
{
    const DETAIL_PICTURE = 'ФайлКартинки';

    private $matchXmlId = [];
    private $exportEnd = false;
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


    public function addProducts()
    {
        $this->startTimestamp = time();
        $oElement = new \CIBlockElement();
        $offsetKey = $this->treeBuilder->getOffsetKey();
        $prodReqArr = $this->treeBuilder->getRequestArr();


        foreach ($this->productsGenerator($prodReqArr) as $arFields) {

            if ((time() - $this->startTimestamp) > self::TIME_LIMIT) {
                return false;
            }
            $this->addProduct($arFields, $oElement);
            ++$_SESSION[$offsetKey];
            //echo '<pre>' . print_r($arFields, true) . '</pre>';die();
        }
        $this->exportEnd = true;
        return true;
    }

    public function exportEndStatus (){

        if($this->exportEnd){
            return true;
        }
        else false;
    }

    public function addProduct(array $arFields, $oElement = false)
    {
        if($oElement == false){
            $oElement = new \CIBlockElement();
        }
        $prod = $this->getProductFromIblock($arFields["XML_ID"]);
//        echo '<pre>' . print_r($arFields, true) . '</pre>';
//        echo '<pre>' . print_r($prod, true) . '</pre>';die();
        $id = 0;

        if ($prod === false) {
            //echo '<pre>' . print_r($arFields, true) . '</pre>';die();
            $id = $oElement->Add($arFields);
        } else {
            $oElement->Update($prod['ID'], $arFields);
        }

        if( intval($id) > 0){
            return $id;
        }
        return false;
    }

    private function getProductFromIblock($xml_id)
    {

        if (!is_string($xml_id)) {
            return false;
        }
        $res = \CIBlockElement::GetList(
            [],
            ["XML_ID" => $xml_id, "IBLOCK_ID" => $this->catalogId],
            false,
            false,
            ["ID", "IBLOCK_ID", "XML_ID", "NAME", "CODE"]
        );

        if ($fields = $res->fetch()) {
            return $fields;
        }
        return false;
    }

    private function productsGenerator($prodReqArr)
    {
        $productsSectionsId = $this->treeBuilder->getProductParentsXmlId();//xml_id родителей товара
        $this->matchXmlId = $this->getSectionMatch(array_keys($productsSectionsId));//сопоставленные id и xml_id

        foreach ($prodReqArr as $prod) {

            $props = [];

            if (count($prod[static::PROPERTIES][0])) {

                foreach ($prod[static::PROPERTIES][0] as $key => $prop) {

                    $code = $this->getPropertyCode($key);

                    if ($this->checkRef($prop)) {
                        $value = $this->getFromReferenceBook($key, $prop, $code);
                    } else {
                        $value = $prop;
                    }

                    $props[$code] = $value;
                }
            }

            //echo '<pre>' . print_r($props, true) . '</pre>';die();

            $arFields = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => $this->catalogId,
                "IBLOCK_SECTION_ID" => $this->matchXmlId[$prod[self::PARENT_ID]],
                "XML_ID" => $prod[self::ID],
                "NAME" => $prod[self::FULL_NAME],
                "CODE" => \CUtil::translit($prod[self::NAME], 'ru') . time(),
                "DETAIL_TEXT" => $prod[self::DESCRIPTION],
                "DETAIL_PICTURE" => $this->getPhoto($prod[static::DETAIL_PICTURE]),
                "PROPERTY_VALUES" => $props
            );

            yield $arFields;
        }
    }

    private function getFromReferenceBook($key, $xml_id, $code)
    {

        $arrProp = [];
        $arrProp[$code] = Array("VALUE" => $this->getEnumId($xml_id, $key, $code));
        return $arrProp;
    }

    private function getEnumId($xml_id, $key, $code)
    {

        $property_enums = \CIBlockPropertyEnum::GetList([], Array("IBLOCK_ID" => $this->catalogId, "XML_ID" => $xml_id));

        if ($enum_fields = $property_enums->GetNext()) {
            return $enum_fields['ID'];
        } else {
            $value = $this->treeBuilder->getRefValue($key, $xml_id);
            $ibpenum = new \CIBlockPropertyEnum;

            $propId = $this->getPropIdFromCode($code);

            if (intval($propId) > 0 && !empty($value) ) {
                if ($enumId = $ibpenum->Add(['PROPERTY_ID' => $propId, 'VALUE' => $value, "XML_ID" => $xml_id])) {
                    return $enumId;
                }
            }
        }
        return false;
    }

    private function getPropIdFromCode($code)
    {

        $res = \CIBlockProperty::GetByID($code, $this->catalogId);

        if ($ar_res = $res->GetNext()) {
            return $ar_res['ID'];
        }
    }

    private function getPropertyCode($outCode)
    {

        $newStr = "";

        for ($i = 0; $i < mb_strlen($outCode); $i++) {
            $char = mb_substr($outCode, $i, 1);

            if (strpos('АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ', $char) !== false && $i) {
                $newStr .= '_' . $char;
            } else {
                $newStr .= $char;
            }
        }

        return \CUtil::translit($newStr, 'ru', ['change_case' => 'U']);
    }

    private function getSectionMatch($allXmlId)
    {

        if (count($allXmlId)) {
            $res = \CIBlockSection::GetList(
                [],
                ["XML_ID" => $allXmlId, 'IBLOCK_ID' => $this->catalogId],
                false,
                ['ID', 'IBLOCK_ID', 'XML_ID']
            );
        }

        $xmlIdFromReq = [];

        while ($fields = $res->fetch()) {
            $xmlIdFromReq[$fields['XML_ID']] = $fields['ID'];
        }
        return $xmlIdFromReq;
    }

    private function getPhoto($gui)
    {
        $ImgArr = $this->treeBuilder->getPicture($gui);
        $expansion = key($ImgArr);
        if($expansion == 'Error'){
            return false;
        }
        if (!empty($ImgArr[$expansion])) {

            $fileData = base64_decode($ImgArr[$expansion]);
            $fileName = $_SERVER['DOCUMENT_ROOT'] . '/upload/temp-photo.' . $expansion;
            file_put_contents($fileName, $fileData);

            $file = \CFile::MakeFileArray($fileName);

            $file['MODULE_ID'] = 'sellwin.1CExchange';
            //$file['description'] = $gui;
            //$file['name'] = $gui;
            //$file['name'] = $gui . '.' . $expansion;

            $fileSave = \CFile::SaveFile(
                $file,
                '/iblock'
            );
            return \CFile::MakeFileArray($fileSave);
        }

        return false;
    }
}