<?php


namespace Kocmo\Exchange\Bx;


/**
 * Class Image
 * @package Kocmo\Exchange\Bx
 */
class Image extends Helper
{
    protected $treeBuilder = null;

    /**
     * Image constructor.
     * @throws \Bitrix\Main\LoaderException
     */
    function __construct()
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Image();
        parent::__construct($treeBuilder);
    }

    public function update(){

        $this->startTimestamp = time();
        $this->status = 'run';

        $iterator = \Kocmo\Exchange\ProductImageTable::getList([
            //"limit" => 100,
        ]);
        $oElement = new \CIBlockElement();

        while($row = $iterator->fetch() ) {

            if($this->checkTime()){
                return false;
            }

            $arPic = $this->getPhoto($row['IMG_GUI']);

            if( is_array($arPic) ){
                if( $oElement->Update($row['PRODUCT_ID'], ["DETAIL_PICTURE" => $arPic]) ){
                    \Kocmo\Exchange\ProductImageTable::delete($row['ID']);
                }
                else{

                }
            }
        }

        //if(6){
            $this->status = 'end';
        //}
        $connection = \Bitrix\Main\Application::getConnection();
        $connection->truncateTable(\Kocmo\Exchange\ProductImageTable::getTableName());

        return true;
    }

    public function getPhoto($gui)
    {
        $ImgArr = $this->treeBuilder->getPicture($gui);
        $expansion = key($ImgArr);

        if (!empty($ImgArr[$expansion])) {

            $fileData = base64_decode($ImgArr[$expansion]);

            $fileName = $_SERVER['DOCUMENT_ROOT'] . '/upload/temp-photo.' . $expansion;
            file_put_contents($fileName, $fileData);

            $file = \CFile::MakeFileArray($fileName);
            $file['MODULE_ID'] = 'kocmo.exchange';
            //$file['description'] = $gui;

            $fileSave = \CFile::SaveFile(
                $file,
                '/iblock'
            );

            return \CFile::MakeFileArray($fileSave);
        }

        return false;
    }
}