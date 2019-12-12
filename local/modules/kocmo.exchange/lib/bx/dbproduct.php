<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 06.10.2019
 * Time: 13:55
 */

namespace Kocmo\Exchange\Bx;
use \Bitrix\Catalog,
    \Bitrix\Main\Application,
    \Kocmo\Exchange,
    \Bitrix\Main\DB;

class Dbproduct extends Helper
{

    protected $defaultLimit = 1000;

    /**
     * Product constructor.
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        $treeBuilder = new Exchange\Tree\Product();
        parent::__construct($treeBuilder);
        unset($treeBuilder);
    }

    public function update() : bool{

        $this->startTimestamp = time();
        $arForDb = $this->treeBuilder->getProductsFromReq();
        $last = true;

        if( is_array($arForDb) && count($arForDb) ) {

            $last = false;

            foreach ($this->prepareFieldsGen($arForDb) as $item) {

                if($this->checkTime()){

                    if( count($this->errors) ){
                        file_put_contents( $_SERVER['DOCUMENT_ROOT'] . '/export-log.txt', print_r($this->errors, true), FILE_APPEND );
                    }
                    return $last;
                }

                try{
                    if( empty($item['PARENT']) || $item['PARENT'] == $item['UID']){
                        $item['ENTRY'] = 'product';
                        //$item['UID'] = 'p_' . $item['UID'];
                    }
                    else{
                        $item['ENTRY'] = 'offer';
                    }
                    unset($item['PARENT']);

                    $result = Exchange\DataTable::add($item);

                    if($result->isSuccess()){
                        $this->utils->setModuleData($this->arParams['PRODUCT_LAST_UID'], $item["UID"]);
                    }
                    else{
                        $this->errors[] = "false - " . $item["UID"] . "\n";
                    }

                } catch ( DB\SqlQueryException $e ){
                    $this->errors[] = 'DB\SqlQueryException - ' . $e->getMessage();
                }
            }
        }
        if($last === true){
            if( count($this->errors) ){
                file_put_contents( $_SERVER['DOCUMENT_ROOT'] . '/export-log.txt', print_r($this->errors, true), FILE_APPEND );
            }
            $this->utils->setModuleData($this->arParams['PRODUCT_LAST_UID'], '');
            $this->status = 'end';
        }
        return $last;
    }

    private function prepareFieldsGen(&$prodReqArr){

        foreach( $prodReqArr as $key => $item ){
            yield [
                "UID" => $key,
                "JSON" => $item["JSON"],
                "PARENT" => $item["PARENT"]
            ];
        }
    }

    static public function truncateTable(){
        $connection = Application::getConnection();
        $connection->truncateTable(Exchange\DataTable::getTableName());
    }
}