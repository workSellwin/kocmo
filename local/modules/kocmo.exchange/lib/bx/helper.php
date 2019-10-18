<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 06.10.2019
 * Time: 12:54
 */

namespace Kocmo\Exchange\Bx;

abstract class Helper
{
//    const TIME_LIMIT = 50;
//    const PARENT_ID = 'Родитель';
//    const ID = "UID";
//    const NAME = "Наименование";
//    const CHILDREN = 'CHILDREN';
//    const DEPTH_LEVEL = 'DEPTH_LEVEL';
//    const FULL_NAME = "НаименованиеПолное";
//    const PROPERTIES = "Свойства";
//    const DESCRIPTION = 'Описание';

    protected $arParams = [];
    protected $treeBuilder = null;
    protected $error = [];
    protected $catalogId = false;
    protected $startTimestamp = false;
    protected $finishTimestamp = false;

    /**
     * Helper constructor.
     * @param \Kocmo\Exchange\Tree\Builder $treeBuilder
     * @param $catalogId
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct(\Kocmo\Exchange\Tree\Builder $treeBuilder, $catalogId)
    {
        try{
            $this->setParams();

            \Bitrix\Main\Loader::includeModule('iblock');
            \Bitrix\Main\Loader::includeModule('catalog');

            if (intval($catalogId) > 0) {
                $this->catalogId = intval($catalogId);

                if (\CCatalog::GetByID($this->catalogId) === false) {
                    throw new \Error("infoblock with code $catalogId does not exist or is not a trade catalog");
                }
            } else {
                throw new \Error('catalog id empty!');
            }
            $this->treeBuilder = $treeBuilder;

        } catch(\Error $e) {
            $error[] = $e;
        }
    }

    protected function setParams(){

        $arParam = require $GLOBALS['kocmo.exchange.config-path'];
        $dir = end( explode('/', __DIR__) );
        $this->arParams = $arParam[$dir];
    }

    protected function checkRef($val)
    {

        if (is_string($val) && strlen($val) === 36 && $val != '00000000-0000-0000-0000-000000000000') {
            $arr = explode('-', $val);

            if (strlen($arr[0]) === 8 && strlen($arr[1]) === 4 && strlen($arr[2]) === 4
                && strlen($arr[3]) === 4 && strlen($arr[4]) === 12) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    protected function getFile($externalId){

        if(!is_string($externalId)){
            return false;
        }

        $res = CFile::GetList([], ["EXTERNAL_ID" => $externalId]);

        if( $fields = $res->fetch() ){
            return $fields;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    protected function getPropertyCode($outCode)
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
}