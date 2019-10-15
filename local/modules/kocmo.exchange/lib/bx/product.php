<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 06.10.2019
 * Time: 13:55
 */

namespace Kocmo\Exchange\Bx;

class Product extends Helper
{
    const DETAIL_PICTURE = 'ФайлКартинки';

    private $productMatchXmlId = [];
    private $sectionMatchXmlId = [];
    private $exportEnd = false;

    /**
     * BxProduct constructor.
     * @param \Kocmo\Exchange\Tree\Handler $treeBuilder
     * @param $catalogId
     */
    public function __construct($catalogId)
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Product();
        parent::__construct($treeBuilder, $catalogId);
    }

    public function addProductsInDb(){

        $arrForDb = $this->treeBuilder->send2();
        //$getImageUri = $this->treeBuilder->getImageUri();
        $rowsId = [];

        if( is_array($arrForDb) && count($arrForDb) ) {
            foreach ($this->prepareFieldsGen($arrForDb) as $item) {

                //$imgGui = $item['IMG_GUI'];
               // unset($item['IMG_GUI']);

                try{
                    $rowsId[] = \Kocmo\Exchange\DataTable::add($item);
                } catch ( \Bitrix\Main\DB\SqlQueryException $e ){
                    //например попытка добавить с не уникальным UID
                }
//                try{
//                    //\Kocmo\Exchange\ProductImageTable::add(["IMG_GUI" => $imgGui, "PRODUCT_ID" => ]);
//                } catch ( \Bitrix\Main\DB\SqlQueryException $e ){
//                    //например попытка добавить с не уникальным IMG_GUI
//                }
            }
        }
        else{
            return false;
        }
        return count($rowsId) ? $rowsId : false;
    }

    public function addProductsFromDb()
    {
        $this->startTimestamp = time();
        $oElement = new \CIBlockElement();

        foreach ($this->getTempDataGen() as $row){

            if ((time() - $this->startTimestamp) > static::TIME_LIMIT) {
                return false;
            }
            $id = $this->addProduct($row, $oElement);

            if( $id > 0 && $this->checkRef($row['DETAIL_PICTURE'])) {
                try {
                    \Kocmo\Exchange\ProductImageTable::add(["IMG_GUI" => $row['DETAIL_PICTURE'], "PRODUCT_ID" => $id]);
                } catch (\Bitrix\Main\DB\SqlQueryException $e) {
                    //например попытка добавить с не уникальным IMG_GUI
                }
            }
        }
        $connection = \Bitrix\Main\Application::getConnection();
        $connection->truncateTable(\Kocmo\Exchange\DataTable::getTableName());
    }

    public function getTempDataGen(){

        $iterator = \Kocmo\Exchange\DataTable::getList([]);
        $sectionsMatch = $this->getAllSectionsXmlId();

        while($row = $iterator->fetch() ){
            $row = json_decode($row['JSON'], true);
            //pr($row);
            $props = [];

            if (count($row[static::PROPERTIES][0])) {

                foreach ($row[static::PROPERTIES][0] as $key => $prop) {

                    $code = $this->getPropertyCode($key);

                    if ($this->checkRef($prop) || is_array($prop) ) {
                        $value = $this->getFromReferenceBook($key, $prop, $code);
                    } else {
                        $value = $prop;
                    }

                    $props[$code] = $value;
                }
            }

            $arFields = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => $this->catalogId,
                "IBLOCK_SECTION_ID" => $sectionsMatch[$row[self::PARENT_ID]],
                "XML_ID" => $row[self::ID],
                "NAME" => $row[self::FULL_NAME],
                "CODE" => \CUtil::translit($row[self::NAME], 'ru') . time(),
                "DETAIL_TEXT" => $row[self::DESCRIPTION],
                "DETAIL_PICTURE" => $row[static::DETAIL_PICTURE],
                "PROPERTY_VALUES" => $props
            );

            yield $arFields;
        }
    }

    protected function getAllSectionsXmlId(){

        $entity = \Bitrix\Iblock\Model\Section::compileEntityByIblock($this->catalogId);
        $iterator = $entity::getList([
            "filter" => ["IBLOCK_ID" => $this->catalogId],
            "select" => ["XML_ID", "ID"]
        ]);
        $sections = [];

        while($row = $iterator->fetch() ){
            $sections[$row['XML_ID']] = $row['ID'];
        }
        return $sections;
    }

    private function prepareFieldsGen(&$prodReqArr){

        foreach( $prodReqArr as $key => $item ){
            yield [
                "UID" => $key,
                "JSON" => $item["JSON"],
                //"IMG_GUI" => $item["IMG_GUI"]
            ];
        }
    }

    public function addProducts()
    {
        if( empty($this->treeBuilder) ||  !is_array($this->treeBuilder)){
            throw new \Error("tree not exist!");
        }
        $this->startTimestamp = time();
        $this->setMatchXmlId();

        $oElement = new \CIBlockElement();
        //$offsetKey = $this->treeBuilder->getOffsetKey();
        $prodReqArr = $this->treeBuilder->getRequestArr();

        foreach ($this->productsGenerator($prodReqArr) as $arFields) {

            if ((time() - $this->startTimestamp) > static::TIME_LIMIT) {
                return false;
            }
            $id = $this->addProduct($arFields, $oElement);
            //++$_SESSION[$offsetKey];
        }
        $this->exportEnd = true;
        return true;
    }

    private function setMatchXmlId(){

        $res = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => $this->catalogId],
            false,
            false,
            ["ID", "IBLOCK_ID", "XML_ID"]
        );

        while($fields = $res->fetch()) {
            $this->productMatchXmlId[$fields["XML_ID"]] = $fields["ID"];
        }
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
        $id = 0;

        if ($prod === false) {
            $id = $oElement->Add($arFields);
        } else {
            //echo '<pre>' . print_r($arFields, true) . '</pre>';
            if( $oElement->Update($prod['ID'], $arFields) ){
                $id = $prod['ID'];
            }
        }

        return intval($id);
    }

    private function getProductFromIblock($xml_id)
    {

        if (!is_string($xml_id)) {
            return false;
        }

        if( isset( $this->productMatchXmlId[$xml_id] ) ){
            return $this->productMatchXmlId[$xml_id];
        }
        else{
            return false;
        }
//        $res = \CIBlockElement::GetList(
//            [],
//            ["XML_ID" => $xml_id, "IBLOCK_ID" => $this->catalogId],
//            false,
//            false,
//            ["ID", "IBLOCK_ID", "XML_ID", "NAME", "CODE"]
//        );
//
//        if ($fields = $res->fetch()) {
//            return $fields;
//        }
       // return false;
    }

    private function productsGenerator($prodReqArr)
    {
        $productsSectionsId = $this->treeBuilder->getProductParentsXmlId();//xml_id родителей товара
        $this->sectionMatchXmlId = $this->getSectionMatch(array_keys($productsSectionsId));//сопоставленные id и xml_id

        foreach ($prodReqArr as $prod) {

            $props = [];

            if (count($prod[static::PROPERTIES][0])) {

                foreach ($prod[static::PROPERTIES][0] as $key => $prop) {

                    $code = $this->getPropertyCode($key);

                    if ($this->checkRef($prop) || is_array($prop) ) {
                        $value = $this->getFromReferenceBook($key, $prop, $code);
                    } else {
                        $value = $prop;
                    }

                    $props[$code] = $value;
                }
            }

            //echo '<pre>' . print_r($props, true) . '</pre>';

            $arFields = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => $this->catalogId,
                "IBLOCK_SECTION_ID" => $this->sectionMatchXmlId[$prod[self::PARENT_ID]],
                "XML_ID" => $prod[self::ID],
                "NAME" => $prod[self::FULL_NAME],
                "CODE" => \CUtil::translit($prod[self::NAME], 'ru') . time(),
                "DETAIL_TEXT" => $prod[self::DESCRIPTION],
                //"DETAIL_PICTURE" => $this->getPhoto($prod[static::DETAIL_PICTURE]),
                "PROPERTY_VALUES" => $props
            );

            yield $arFields;
        }
    }

    protected function getFromReferenceBook($key, $value, $code)
    {
        if(is_array($value)){
            $arrProp = $this->getEnumIdArr($value, $code);
        }
        else{
            $arrProp = Array("VALUE" => $this->getEnumId($value, $key, $code));
        }
        return $arrProp;
    }

    protected function getEnumId($xml_id, $key, $code)
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

    protected function getEnumIdArr(array $valueArr, $code)
    {
//        if($code == 'TIP_VOLOS'){//for test
//            $valueArr = ['ДляВсехТиповВолос (Для всех типов волос)', 'Нормальные', 'Сухие'];
//        }
        if (count($valueArr) == 0) {
            return false;
        }
        $returnArr = [];

        $property_enums = \CIBlockPropertyEnum::GetList([], Array("IBLOCK_ID" => $this->catalogId, "CODE" => $code));

        while ($enum_fields = $property_enums->GetNext()) {
            $returnArr[$enum_fields['ID']] = $enum_fields["VALUE"];
        }

        $valueArr = array_filter($valueArr, function($val) use ($returnArr){
            foreach($returnArr as $item){
                if($item == $val){
                    return false;
                }
            }
            return true;
        });

        if( count($valueArr) ){

            $ibpenum = new \CIBlockPropertyEnum;

            foreach($valueArr as $val){

                $propId = $this->getPropIdFromCode($code);

                if (intval($propId) > 0 ) {
                    if ($enumId = $ibpenum->Add(['PROPERTY_ID' => $propId, 'VALUE' => $val])) {
                        $returnArr[$enumId] = $val;
                    }
                }
            }
        }
        return array_keys($returnArr);
    }

    private function getPropIdFromCode($code)
    {

        $res = \CIBlockProperty::GetByID($code, $this->catalogId);

        if ($ar_res = $res->GetNext()) {
            return $ar_res['ID'];
        }
        return false;
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