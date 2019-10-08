<?php


namespace Asdrubael\Utils;


/**
 * Class BxImage
 * @package Asdrubael\Utils
 */
class BxImage extends BxHelper
{
    const DETAIL_PICTURE = 'ФайлКартинки';

    protected $treeBuilder = null;

    /**
     * BxImage constructor.
     * @param treeHandler $treeBuilder
     * @param $catalogId
     * @throws \Bitrix\Main\LoaderException
     */
    function __construct(treeHandler $treeBuilder, $catalogId)
    {
        parent::__construct($treeBuilder, $catalogId);
    }

    public function upload(){
        foreach ( $this->imageGenerator($this->treeBuilder->getRequestArr() ) as $file ){
            echo '<pre>' . print_r($file, true) . '</pre>';
        }
    }

    public function imageGenerator( $arr ){

        foreach ($arr as $item ){
            if( $this->checkRef($item[self::DETAIL_PICTURE]) ){
                //echo '<pre>' . print_r($item, true) . '</pre>';
                yield $this->getPhoto($item[self::DETAIL_PICTURE]);
            }
            else{
                continue;
            }
        }
    }

    private function getPhoto($gui)
    {
        $ImgArr = $this->treeBuilder->getPicture($gui);
        $expansion = key($ImgArr);

        if (!empty($ImgArr[$expansion])) {

            $fileData = base64_decode($ImgArr[$expansion]);
            $fileName = $_SERVER['DOCUMENT_ROOT'] . '/upload/temp-photo.' . $expansion;
            file_put_contents($fileName, $fileData);

            $file = \CFile::MakeFileArray($fileName);
            $file['MODULE_ID'] = 'sellwin.1CExchange';
            $file['description'] = $gui;

            $fileSave = \CFile::SaveFile(
                $file,
                '/iblock'
            );

            return $fileSave;//\CFile::MakeFileArray($fileSave);
        }

        return false;
    }
}