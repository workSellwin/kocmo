<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 06.10.2019
 * Time: 13:55
 */

namespace Kocmo\Exchange\Bx;
use \Bitrix\Catalog;

class Dbproduct extends Helper
{
    private $productMatchXmlId = [];
    protected $arProperty = [];
    protected $arEnumMatch = [];
    protected $defaultLimit = 1000;

    /**
     * Product constructor.
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Product();
        parent::__construct($treeBuilder);
        unset($treeBuilder);
    }

    public function update(){

        $this->startTimestamp = time();
        $arForDb = $this->treeBuilder->getProductsFromReq();

        $lastUid = true;

        if( is_array($arForDb) && count($arForDb) ) {

            foreach ($this->prepareFieldsGen($arForDb) as $item) {

                if($this->checkTime()){
                    return $lastUid;
                }

                try{
                    $result = \Kocmo\Exchange\DataTable::add($item);

                    if($result->isSuccess()){
                        $lastUid = $item["UID"];
                    }
                } catch ( \Bitrix\Main\DB\SqlQueryException $e ){
                    //например попытка добавить с не уникальным UID
                    $this->errors[] = $e->getMessage();
                }
            }
        }
        return $lastUid;
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

    static public function truncateTable(){
        $connection = \Bitrix\Main\Application::getConnection();
        $connection->truncateTable(\Kocmo\Exchange\DataTable::getTableName());
    }
}