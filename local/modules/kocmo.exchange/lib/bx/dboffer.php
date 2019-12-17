<?php


namespace Kocmo\Exchange\Bx;

use \Bitrix\Catalog,
    \Bitrix\Main\Application,
    \Kocmo\Exchange,
    \Bitrix\Main\DB;

class Dboffer extends Helper
{

    protected $defaultLimit = 1000;

    /**
     * Product constructor.
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct()
{
    $treeBuilder = new Exchange\Tree\Offer();
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
                return $last;
            }

            try{
                $item['ENTRY'] = 'offer';
                //echo '<pre>' . print_r($item, true) . '</pre>';

                $result = Exchange\DataTable::add($item);

                //if($result->isSuccess()){
                    $this->utils->setModuleData($this->arParams['OFFER_LAST_UID'], $item["UID"]);
                //}
            } catch ( DB\SqlQueryException $e ){
                //например попытка добавить с не уникальным UID
//                echo '<pre>' . print_r($item, true) . '</pre>';
//                die();

                $this->utils->setModuleData($this->arParams['OFFER_LAST_UID'], $item["UID"]);
                $this->errors[] = $e->getMessage();
            } catch (\Exception $e ){
                $this->utils->setModuleData($this->arParams['OFFER_LAST_UID'], $item["UID"]);
                $this->errors[] = $e->getMessage();
            }
        }
//        echo '<pre>' . print_r($this->utils->getModuleData($this->arParams['OFFER_LAST_UID']), true) . '</pre>';
//        echo '<pre>' . print_r($this->errors, true) . '</pre>';
//        die();
    }
    if($last === true){
        $this->utils->setModuleData($this->arParams['OFFER_LAST_UID'], '');
        $this->status = 'end';
    }
    return $last;
}

    private function prepareFieldsGen(&$prodReqArr)
    {
        foreach ($prodReqArr as $key => $item) {
            yield [
                "UID" => $key,
                "JSON" => $item["JSON"],
                //"IMG_GUI" => $item["IMG_GUI"]
            ];
        }
    }

    static public function truncateTable()
    {
        $connection = Application::getConnection();
        $connection->truncateTable(Exchange\DataTable::getTableName());
    }
}