<?php
use Asdrubael\S3\CloudStorageClient;

class ElementHandler
{
    static private $allowFields = ['DETAIL_PICTURE', 'PREVIEW_PICTURE'];
    static private $allowProperty = ['13'];

    static function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {

        if(count(static::$allowFields)){

            foreach(static::$allowFields as $field){
                if( isset($arFields[$field]) && !empty($arFields[$field]["name"]) && !empty($arFields['XML_ID']) ){
                    static::prepareFeild($arFields, $field);
                }
            }
        }

        if(count(static::$allowProperty)){

            foreach(static::$allowProperty as $prop){
                if( isset($arFields['PROPERTY_VALUES'][$prop]) && !empty($arFields['XML_ID']) ){
                    static::prepareProp($arFields, $prop);
                }
            }
        }
//        pr($arFields['PROPERTY_VALUES'][13], 14);
//        die();
    }

//    static function OnBeforeIBlockElementAddHandler(&$arFields)
//    {
//        $arFields['DETAIL_PICTURE']['product_xml_id'] = $arFields['XML_ID'];
//        $arFields['DETAIL_PICTURE']["name"] =
//            'DETAIL_PICTURE' . '.' . static::getExtansion($arFields['DETAIL_PICTURE']["name"]);
//    }

    static function OnAfterIBlockElementDeleteHandler($arFields){

        if( !empty($arFields['EXTERNAL_ID']) ){

            \Bitrix\Main\Loader::includeModule('kocmo.exchange');//composer

            $storageClient = new Asdrubael\S3\CloudStorageClient();
            $storageClient->deleteObjects('bhby', $arFields['EXTERNAL_ID']);
        }
    }

    static private function getExtansion($name){

        $arr = explode('.', $name);
        $extension = $arr[count($arr)-1];

        if($extension){
            return '.' . $extension;
        }
        else{
            return "";
        }
    }

    static function prepareFeild(&$arFields, $field){

        $arFields[$field]['product_xml_id'] = $arFields['XML_ID'];
        $arFields[$field]["name"] = $field . static::getExtansion($arFields[$field]["name"]);
    }

    static function prepareProp(&$arFields, $prop){

        if(count($arFields['PROPERTY_VALUES'][$prop])){//multi

            foreach($arFields['PROPERTY_VALUES'][$prop] as &$p){

                if( !empty($p['VALUE']["name"]) ){

                    $p['VALUE']['product_xml_id'] = $arFields['XML_ID'];
                    $p['VALUE']["name"] = 'PROPERTY_' . $prop . '_'
                        . static::getFileTempName($p['VALUE']["tmp_name"]) . static::getExtansion($p['VALUE']["name"]);
                }
            }
        }
    }

    static function getFileTempName($tmp_name){

        preg_match("#\/([\d\w]+)\/default$#U", $tmp_name, $matches);
        return $matches[1];
    }
}