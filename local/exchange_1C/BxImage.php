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
        echo '<pre>' . print_r($this->treeBuilder->getRequestArr(), true) . '</pre>';
    }

    private function getPhoto($gui)
    {
        if ($this->checkRef($gui)) {

            $base64Img = $this->treeBuilder->getPicture($gui);
            if (!empty($base64Img)) {
                $fileData = base64_decode($base64Img);
                $fileName = $_SERVER['DOCUMENT_ROOT'] . '/upload/temp/temp-photo.jpg';
                file_put_contents($fileName, $fileData);

                $file = \CFile::MakeFileArray($fileName);
                $fileSave = \CFile::SaveFile(
                    $file,
                    '/iblock'
                );

                return \CFile::MakeFileArray($fileSave);
            }
        }
        return "";
    }
}