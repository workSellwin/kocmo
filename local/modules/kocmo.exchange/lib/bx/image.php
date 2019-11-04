<?php


namespace Kocmo\Exchange\Bx;


/**
 * Class BxImage
 * @package Asdrubael\Utils
 */
class Image extends Helper
{
    //const DETAIL_PICTURE = 'ФайлКартинки';

    protected $treeBuilder = null;

    /**
     * Image constructor.
     * @param \Kocmo\Exchange\Tree\Builder $treeBuilder
     * @param $catalogId
     * @throws \Bitrix\Main\LoaderException
     */
    function __construct($catalogId)
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Image();
        parent::__construct($treeBuilder);
    }

    public function updateDetailPictures(){

        $iterator = \Kocmo\Exchange\ProductImageTable::getList([]);
        $oElement = new \CIBlockElement();

        while($row = $iterator->fetch() ) {
            $arPic = $this->getPhoto($row['IMG_GUI']);
            //pr($arPic);
            if( is_array($arPic) ){
                $oElement->Update($row['PRODUCT_ID'], ["DETAIL_PICTURE" => $arPic]);
            }
        }

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