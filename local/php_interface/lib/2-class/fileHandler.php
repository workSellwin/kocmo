<?php

use Asdrubael\Bx\FileTable;
use Asdrubael\S3\CloudStorageClient;
use \Bitrix\Main\Loader;

class FileHandler
{

    const BUCKET = 'bhby';
    const PROTOCOL = "https://";
    const CLOUD_HOST = '.io.activecloud.com/';
    private $propertyId = [13, 144];

    static function OnGetFileSRCHandler($ar){

        if(strpos($ar['MODULE_ID'], 'cloud') === false){
            return '';
        }
        $src = self::PROTOCOL . self::BUCKET . self::CLOUD_HOST . $ar['SUBDIR'] . '/' . $ar['FILE_NAME'];

        return $src;
    }

    static function OnFileSaveHandler(&$arFile){

        if( empty($arFile['product_xml_id']) ){
            return false;
        }

//    if( !CFile::IsImage($arFile['tmp_name']) ){
//        return false;
//    }

        \Bitrix\Main\Loader::includeModule('kocmo.exchange');//composer

        $f = fopen($arFile['tmp_name'], "r");

        if (!$f) {
            return false;
        }
        $contents = fread($f, filesize($arFile['tmp_name']));
        fclose($f);

        if($contents === false) {
            return false;
        }

        $imgArray = getimagesize($arFile['tmp_name']);

        if(!$imgArray[0] || !$imgArray[1]){
            return false;
        }

        $arFile["SUBDIR"] = $arFile['product_xml_id'] . '/' . $imgArray[0] .'x'. $imgArray[1];
        $arFile["FILE_NAME"] = $arFile['name'];
        $arFile['MODULE_ID'] = 'my_cloud';
        $arFile['HANDLER_ID'] = 'my_cloud';

        $storageClient = new Asdrubael\S3\CloudStorageClient();
        $result = $storageClient->putObject(
            self::BUCKET,
            $arFile["SUBDIR"] . '/' . $arFile["FILE_NAME"],
            $contents
        );

        if(is_array($imgArray))
        {
            $arFile["WIDTH"] = $imgArray[0];
            $arFile["HEIGHT"] = $imgArray[1];
        }
        else
        {
            $arFile["WIDTH"] = 0;
            $arFile["HEIGHT"] = 0;
        }

        return true;
    }

    static function OnAfterResizeImageHandler(&$file, $data, $callbackData, &$cacheImageFile, &$cacheImageFileTmp, &$arImageSize){

        if(strpos($file['MODULE_ID'], 'cloud') !== false){

            Loader::includeModule('kocmo.exchange');//composer
            $storageClient = new Asdrubael\S3\CloudStorageClient();

            preg_match("#([\s\S]+)\/(\d+x\d+)#", $file["SUBDIR"], $parseData);
            $resizeResolution = $data[0]['width'] . 'x' . $data[0]['height'];

            if( !count($parseData)){
                return false;
            }

            $path = $storageClient->getFilePath(
                self::BUCKET,
                ['id' => $parseData[1], 'resolution' => $resizeResolution, 'strict' => false, 'file_name' => $file['FILE_NAME']]
            );
            //pr($path, 14);
            preg_match("#(\d+)x(\d+)#", $path, $resolutionMatch);

            if( is_string($path) && strlen($path) > 10){

                $arImageSize = [
                    $resolutionMatch[1], $resolutionMatch[2]
                ];

                global $arCloudImageSizeCache;
                $arCloudImageSizeCache[$path] = $arImageSize;
                $cacheImageFile = $path;
                return true;
            }

            $fileId = CFile::SaveFile( CFile::MakeFileArray($file['SRC']), 'tmp');

            if(intval($fileId) > 0){

                $rf = CFile::ResizeImageGet(
                    $fileId,
                    $data[0],
                    $data[1]
                );

                if(!is_array($rf) || empty($rf['src'])){
                    return false;
                }

                $rf['src'] = $_SERVER['DOCUMENT_ROOT'] . $rf['src'];

                $imgArray = getimagesize($rf['src']);
                $size = filesize($rf['src']);

                if(!$imgArray[0] || !$imgArray[1]){
                    return false;
                }

                $f = fopen($rf['src'], "r");

                if (!$f) {
                    return false;
                }

                $contents = fread($f, $size);
                fclose($f);
                CFile::Delete($fileId);

                if($contents === false) {
                    return false;
                }
                $resizeSubDir = $parseData[1] . '/' . $imgArray[0] . 'x' . $imgArray[1];
                $result = $storageClient->putObject(
                    self::BUCKET,
                    $resizeSubDir . '/' . $file["FILE_NAME"],
                    $contents
                );

                $metadata = $result->get('@metadata');

                if($metadata['statusCode'] == 200){

                    $arImageSize = [
                        intval($imgArray[0]), intval($imgArray[1]), intval($size)
                    ];

                    $cacheImageFile = $metadata['effectiveUri'];

                    global $arCloudImageSizeCache;
                    $arCloudImageSizeCache[$cacheImageFile] = $arImageSize;
                    return true;
                }
                return false;
            }
        }
    }

    static function OnFileDeleteHandler(&$arFile){

        preg_match("#([\d\w\-]+)\/(\d+x\d+)#", $arFile['SUBDIR'], $matches);

        if( count($matches) ){
            Loader::includeModule('kocmo.exchange');//composer
            $storageClient = new Asdrubael\S3\CloudStorageClient();
            $storageClient->deleteObjects(self::BUCKET, $matches[1], [$arFile['FILE_NAME']]);
        }
    }

    function getBindFiles($filter, $select = []){

//        $filter = ["IBLOCK_ID" => 2, "ID" => 38301];
//        $select = ["ID", "DETAIL_PICTURE", "PREVIEW_PICTURE", "PROPERTY_13"];
        $res = CIBlockElement::GetList([], $filter, false, false, $select);
        $FILES = [];

        while($fields = $res->fetch() ){

            if(intval($fields['PREVIEW_PICTURE']) > 0 && empty($FILES[$fields["EXTERNAL_ID"]]['PREVIEW_PICTURE'])){
                $FILES[$fields["EXTERNAL_ID"]]['PREVIEW_PICTURE'] = intval($fields['PREVIEW_PICTURE']);
            }
            if(intval($fields['DETAIL_PICTURE']) > 0 && empty($FILES[$fields["EXTERNAL_ID"]]['DETAIL_PICTURE'])){
                $FILES[$fields["EXTERNAL_ID"]]['DETAIL_PICTURE'] = intval($fields['DETAIL_PICTURE']);
            }

            foreach($fields as $key => $field){
                preg_match("#^PROPERTY_(\d+)_VALUE$#", $key, $matches);

                if( count($matches) ){
                    if( !in_array(intval($field), $FILES[$fields["EXTERNAL_ID"]][$matches[1]])){
                        $FILES[$fields["EXTERNAL_ID"]][$matches[1]][] = intval($field);
                    }
                }
                unset($matches);
            }
        }
        //pr($FILES, 14);
        return $FILES;
    }

    function moveToCloud(){

        \Bitrix\Main\Loader::includeModule('kocmo.exchange');//composer

        $bindFiles = $this->getBindFiles(["IBLOCK_ID" => 2, "ID" => 38301], ["ID", "EXTERNAL_ID", "DETAIL_PICTURE", "PREVIEW_PICTURE", "PROPERTY_13", "PROPERTY_144"]);
        $allFiles = [];
        $binds = [];
        $fileNames = [];
        //pr($bindFiles, 14);
        if(count($bindFiles)){

            foreach($bindFiles as $productId => $fileIds){

                foreach($fileIds as $name => $fileId) {

                    if(is_array($fileId)){

                        if(count($fileId)){

                            foreach($fileId as $index => $fi){

                                $allFiles[] = $fi;
                                $binds[$fi] = $productId;
                                $fileNames[$fi] = "PROPERTY_" . $name . '_' . $index;
                            }
                        }
                    }
                    else{
                        $allFiles[] = $fileId;
                        $binds[$fileId] = $productId;
                        $fileNames[$fileId] = $name;
                    }
                }
            }
        }
        $fAr = Asdrubael\Bx\FileTable::getList(["filter" => ["!MODULE_ID" => "%cloud", "ID" => $allFiles]])->fetchAll();

        if(count($fAr)) {

            foreach ($fAr as $fileAr) {

                $path = $_SERVER['DOCUMENT_ROOT'] . CFile::GetPath($fileAr['ID']);
                $f = fopen($path, "r");

                if (!$f) {
                    continue;
                }
                $contents = fread($f, filesize($path));
                fclose($f);

                if($contents === false) {
                    continue;
                }
                if(in_array($fileNames[$fileAr["ID"]], ["DETAIL_PICTURE", "PREVIEW_PICTURE"])){
                    $fileAr["FILE_NAME"] = $fileNames[$fileAr["ID"]] . $this->getExtansion($fileAr["FILE_NAME"]);
                }
                else{
                    $fileAr["FILE_NAME"] = $fileNames[$fileAr["ID"]] . $this->getExtansion($fileAr["FILE_NAME"]);
                }

                $fileAr["SUBDIR"] = $binds[$fileAr["ID"]] . '/' . $fileAr['WIDTH'] .'x'. $fileAr['HEIGHT'];
                $fileAr['MODULE_ID'] = 'my_cloud';
                $fileAr['HANDLER_ID'] = 'my_cloud';

                $storageClient = new Asdrubael\S3\CloudStorageClient();

                $result = $storageClient->putObject(
                    self::BUCKET,
                    $fileAr["SUBDIR"] . '/' . $fileAr["FILE_NAME"],
                    $contents
                );

                Asdrubael\Bx\FileTable::update($fileAr["ID"], $fileAr);
                //pr($fileAr, 14);
            }
        }

    }

    private function getFileName($key){

        if(!is_string($key)){
            return false;
        }

        preg_match("#\/([^\/]+)$#", $key, $matches);

        if(isset($matches[1])){
            return $matches[1];
        }
        return false;
    }

    private function getExtansion($name){

        $arr = explode('.', $name);
        $extension = $arr[count($arr)-1];

        if($extension){
            return '.' . $extension;
        }
        else{
            return "";
        }
    }
}