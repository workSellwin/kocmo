<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 06.10.2019
 * Time: 12:54
 */

namespace Kocmo\Exchange\Bx;

use Bitrix\Main\Type\DateTime,
    Kocmo\Exchange,
    Kocmo\Exchange\Tree\Builder,
    \Bitrix\Main\Loader,
    \Bitrix\Main;

abstract class Helper
{
//    private $module = 'kocmo.exchange';
    protected $arParams = [];
    /* @var $treeBuilder Builder */
    protected $treeBuilder = null;
    /* @var $utils Exchange\Utils */
    protected $utils = null;
    protected $errors = [];
    protected $catalogId = false;
    protected $startTimestamp = false;
    protected $finishTimestamp = false;
    protected $timeLimit = 60;
    protected $status = 'waiting';
    protected $timestamp = null;

    /**
     * Helper constructor.
     * @param Builder $treeBuilder
     */
    public function __construct(Builder $treeBuilder)
    {
        $this->timestamp = DateTime::createFromTimestamp(time());

        $this->status = 'run';

        try{
            $this->setParams();

            Loader::includeModule('iblock');
            Loader::includeModule('catalog');

            if (intval($this->arParams['IBLOCK_CATALOG_ID']) > 0) {
                $this->catalogId = intval($this->arParams['IBLOCK_CATALOG_ID']);

                if (\CCatalog::GetByID($this->catalogId) === false) {
                    throw new \Error("infoblock with code $this->catalogId does not exist or is not a trade catalog");
                }
            } else {
                throw new \Error('catalog id empty!');
            }
            $this->treeBuilder = $treeBuilder;

        } catch(Main\LoaderException $e) {

        } catch(\Error $e) {
            $error[] = $e;
        }
    }

    abstract public function update(): bool;

    protected function setParams(){

        $this->utils = new Exchange\Utils();
        $arParam = require $GLOBALS['kocmo.exchange.config-path'];
        $dir = end( explode('/', __DIR__) );
        $this->arParams = $arParam[$dir];
    }

    protected function getFile($externalId){

        if(!is_string($externalId)){
            return false;
        }

        $res = \CFile::GetList([], ["EXTERNAL_ID" => $externalId]);

        if( $fields = $res->fetch() ){
            return $fields;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    protected function checkTime(){
        
        if(!isset($this->startTimestamp)){
            $this->startTimestamp = time();
            return false;
        }

        $time = time();
        $t = $time - $this->startTimestamp;

        if( $t > $this->timeLimit ){
            $this->finishTimestamp = $time;
            return true;
        }
        else{
            return false;
        }
    }

    function getStatus(){
        return $this->status;
    }
}