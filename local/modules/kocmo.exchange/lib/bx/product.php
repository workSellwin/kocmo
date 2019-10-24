<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 06.10.2019
 * Time: 13:55
 */

namespace Kocmo\Exchange\Bx;
use \Bitrix\Catalog;

class Product extends Helper
{
    private $productMatchXmlId = [];
    protected $arProperty = [];
    //private $sectionMatchXmlId = [];
    //private $exportEnd = false;

    /**
     * BxProduct constructor.
     * @param \Kocmo\Exchange\Tree\Builder $treeBuilder
     * @param $catalogId
     */
    public function __construct($catalogId)
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Product();
        parent::__construct($treeBuilder, $catalogId);
        unset($treeBuilder);
//        $propsArr = $this->treeBuilder->send4();
//
//        foreach($propsArr as $prop){
//            //$uid = $prop['UID'];
//            //unset($prop['UID']);
//            $guiMatch = $this->treeBuilder->getStrFromGuid($prop['UID']);
//            $this->arProperty[$guiMatch] = $prop;
//        }
        //pr($this->arProperty);
    }

    public function addProductsInDb(){

        $arrForDb = $this->treeBuilder->send2();
        $lastUid = true;

        if( is_array($arrForDb) && count($arrForDb) ) {
            foreach ($this->prepareFieldsGen($arrForDb) as $item) {

                try{
                    $result = \Kocmo\Exchange\DataTable::add($item);
//                    if(!$result->isSuccess()){
//                        pr($result);
//                    }
                    $lastUid = $item["UID"];
                } catch ( \Bitrix\Main\DB\SqlQueryException $e ){
                    //например попытка добавить с не уникальным UID
                }
            }
        }
        else{
            return $lastUid;
        }
        return $lastUid;
    }

    public function addProductsFromDb()
    {
        $oElement = new \CIBlockElement();
        $this->setMatchXmlId();

        foreach ($this->getTempDataGen() as $row){

            $detailPic = $row['DETAIL_PICTURE'];
            unset($row['DETAIL_PICTURE']);

            $id = $this->addProduct($row, $oElement);

            if( $id > 0 && $this->checkRef($row['DETAIL_PICTURE'])) {

                try {

                    \Kocmo\Exchange\ProductImageTable::add(["IMG_GUI" => $detailPic, "PRODUCT_ID" => $id]);
                } catch (\Bitrix\Main\DB\SqlQueryException $e) {
                    //например попытка добавить с не уникальным IMG_GUI
                }
            }
        }
        $connection = \Bitrix\Main\Application::getConnection();
        $connection->truncateTable(\Kocmo\Exchange\DataTable::getTableName());

        return true;
    }

    public function addProductFromDb($xmlId){

        if( !is_string($xmlId) ){
            return false;
        }
        $iterator = \Kocmo\Exchange\DataTable::getList(['filter' => ['UID' => $xmlId]]);
        $oElement = new \CIBlockElement();
        $this->setMatchXmlId();
        $sectionsMatch = $this->getAllSectionsXmlId();

        if( $row = $iterator->fetch() ){

            $row = json_decode($row['JSON'], true);

            $props = [];

            if (count($row[$this->arParams['PROPERTIES']])) {

                foreach ($row[$this->arParams['PROPERTIES']] as $key => $prop) {

                    $code = $this->getPropertyCode($key);

//                    if ($this->checkRef($prop) || is_array($prop) ) {
//                        $value = $this->getFromReferenceBook($key, $prop, $code);
//                    } else {
                    $value = $prop;
                    //}

                    $props[$code] = $value;
                }
            }

            $oImage = new Image($this->catalogId);
            $arPic = $oImage->getPhoto( $row[$this->arParams['PIC_FILE']] );

            if( empty($arPic)){
                $arPic = "";
            }

            $arFields = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => $this->catalogId,
                "IBLOCK_SECTION_ID" => $sectionsMatch[$row[$this->arParams['PARENT_ID']][0]],
                "XML_ID" => $row[$this->arParams['ID']],
                "NAME" => $row[$this->arParams['FULL_NAME']],
                "CODE" => \CUtil::translit($row[$this->arParams['NAME']], 'ru') . time(),
                "DETAIL_TEXT" => $row[$this->arParams['DESCRIPTION']],
                "DETAIL_PICTURE" => $arPic,
                "PROPERTY_VALUES" => $props
            );
            $row = $arFields;

            $id = $this->addProduct($row, $oElement);

//            if( $id > 0 && $this->checkRef($row['DETAIL_PICTURE'])) {
//
//                try {
//
//                    \Kocmo\Exchange\ProductImageTable::add(["IMG_GUI" => $row['DETAIL_PICTURE'], "PRODUCT_ID" => $id]);
//                } catch (\Bitrix\Main\DB\SqlQueryException $e) {
//                    //например попытка добавить с не уникальным IMG_GUI
//                }
//            }
        }

//        foreach ($this->getTempDataGen() as $row){
//
//            $id = $this->addProduct($row, $oElement);
//
//            if( $id > 0 && $this->checkRef($row['DETAIL_PICTURE'])) {
//
//                try {
//
//                    \Kocmo\Exchange\ProductImageTable::add(["IMG_GUI" => $row['DETAIL_PICTURE'], "PRODUCT_ID" => $id]);
//                } catch (\Bitrix\Main\DB\SqlQueryException $e) {
//                    //например попытка добавить с не уникальным IMG_GUI
//                }
//            }
//        }
    }

    public function getTempDataGen(){

        $iterator = \Kocmo\Exchange\DataTable::getList(['limit'=>1000]);
        $sectionsMatch = $this->getAllSectionsXmlId();

        while($row = $iterator->fetch() ){
            $row = json_decode($row['JSON'], true);

            $props = [];

            if (count($row[$this->arParams['PROPERTIES']])) {

                foreach ($row[$this->arParams['PROPERTIES']] as $key => $prop) {

                    $code = $this->getPropertyCode($key);

//                    if ($this->checkRef($prop) || is_array($prop) ) {
//                        $value = $this->getFromReferenceBook($key, $prop, $code);
//                    } else {
                        $value = $prop;
                    //}

                    $props[$code] = $value;
                }
            }

            $arFields = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => $this->catalogId,
                "IBLOCK_SECTION_ID" => $sectionsMatch[$row[$this->arParams['PARENT_ID']]],
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

    public function addProduct(array $arFields, $oElement = false)
    {
        if($oElement == false){
            $oElement = new \CIBlockElement();
        }

        $prod = $this->getProductFromIblock($arFields["XML_ID"]);

        $id = 0;

        if ($prod === false) {
            //$arFields['IBLOCK_SECTION_ID'] = 2;//пока нет разделов
           // unset($arFields['PROPERTY_VALUES'], $arFields['DETAIL_PICTURE']);
            $id = $oElement->Add($arFields);
//            if(!$id){
//                echo "Error: ".$oElement->LAST_ERROR;
//            }
            //pr($arFields);
           // pr($id);
            Catalog\Model\Product::add(array('fields' => ['ID' => $id]));//add to b_catalog_product
        } else {
            if( $oElement->Update($prod, $arFields) ){
                $id = $prod;
            }
        }
        return intval($id);
    }

    private function getProductFromIblock($xml_id)
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

            $bxPropEnum = new \CIBlockPropertyEnum;

            foreach($valueArr as $val){

                $propId = $this->getPropIdFromCode($code);

                if (intval($propId) > 0 ) {
                    if ($enumId = $bxPropEnum->Add(['PROPERTY_ID' => $propId, 'VALUE' => $val])) {
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
}