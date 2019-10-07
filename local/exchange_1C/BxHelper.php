<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 06.10.2019
 * Time: 12:54
 */

namespace Asdrubael\Utils;
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

abstract class BxHelper
{
    const TIME_LIMIT = 50;
    const PARENT_ID = 'Родитель';
    const ID = "UID";
    const NAME = "Наименование";
    const CHILDREN = 'CHILDREN';
    const DEPTH_LEVEL = 'DEPTH_LEVEL';
    const FULL_NAME = "НаименованиеПолное";
    const PROPERTIES = "Свойства";
    const DESCRIPTION = 'Описание';

    protected $treeBuilder = null;
    protected $error = [];
    protected $catalogId = false;
    protected $startTimestamp = false;
    protected $finishTimestamp = false;

    /**
     * BxHelper constructor.
     * @param treeHandler $treeBuilder
     * @param $catalogId
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct(treeHandler $treeBuilder, $catalogId)
    {
        if (
            \Bitrix\Main\Loader::includeModule('iblock')
            && \Bitrix\Main\Loader::includeModule('catalog')
        ) {

            if (intval($catalogId) > 0) {
                $this->catalogId = intval($catalogId);

                if (\CCatalog::GetByID($this->catalogId) === false) {
                    throw new \Error('infoblock with code $ID does not exist or is not a trade catalog');
                }
            } else {
                throw new \Error('catalog id empty!');
            }
            $this->treeBuilder = $treeBuilder;

        } else {
            throw new \Error('module "iblock" or "catalog" not find!');
        }
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

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }
}