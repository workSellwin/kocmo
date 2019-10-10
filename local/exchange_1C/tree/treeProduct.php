<?php


namespace Asdrubael\Utils;


class treeProduct extends treeHandler
{
    const PRODUCT_LIMIT = 1000;
    const OFFSET_KEY = 'PRODUCT_OFFSET';
    const POINT_OF_ENTRY = 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetFolder/GoodsItems';
    const REFERENCE_URL = 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetReference/';
    const GET_IMAGE_URI = 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetImage/';

    protected $tempJsonFileName = '/upload/tempProduct.json';
    protected $referenceBooksGuid = [
        "СтранаПроисхождения" => "42d1086a-9ccb-11e8-a215-00505601048d",
        "ТоварнаяГруппа" => "42d1086e-9ccb-11e8-a215-00505601048d",
        "Производитель" => "42d1082a-9ccb-11e8-a215-00505601048d",
        "Марка" => "42d1082e-9ccb-11e8-a215-00505601048d",
        "Коллекция" => "42d1081c-9ccb-11e8-a215-00505601048d",
    ];
    protected $multyDefaultProperty = [
        'Статус', 'ТипКожи', 'ТипВолос', 'СостояниеВолос', 'SPFФактор', 'СтепеньФиксации',
    ];

    function __construct()
    {
        parent::__construct();
        $this->tempJsonPath = $_SERVER['DOCUMENT_ROOT'] . $this->tempJsonFileName;

        $this->fillInOutputArr();
    }

    protected function fillInOutputArr(){

        if( empty($_GET['group']) && file_exists( $this->tempJsonPath ) && !empty($_SESSION[self::OFFSET_KEY]) ){

            $this->startOffset = $_SESSION[self::OFFSET_KEY];
            $_SESSION[self::OFFSET_KEY] = 0;
            $this->updateJsonFile();
            $this->setSliceFromJson();

            if( count($this->outputArr) == 0){

                $this->outputArr = false;
                $this->delTempFile();
            }
        }
        else{

            $_SESSION[self::OFFSET_KEY] = 0;
            $getParamsStr = "";

            foreach( $_GET as $key => $param){
                if( in_array($key, $this->allowedGetParams) ){
                    $getParamsStr .= $key . '=' . $param . '&';
                }
            }

            $this->send(self::POINT_OF_ENTRY . '?' . $getParamsStr);

           //$this->send(static::POINT_OF_ENTRY );
        }
    }

    public function getProductParentsXmlId(){

        $returnVal = [];

        foreach( $this->outputArr as $item){
            $returnVal[$item[static::PARENT_ID]] =
                isset($returnVal[$item[static::PARENT_ID]]) ? ++$returnVal[$item[static::PARENT_ID]] : 0;
        }
        return $returnVal;
    }

    private function getRefereceBook($gui){

       // $arr = $this->send(static::REFERENCE_URL . $gui);
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', static::REFERENCE_URL . $gui);

        if ($response->getStatusCode() == 200) {
            $outArr = json_decode($response->getBody(), true);
        }

        return $outArr;
    }

        public function getRefValue($book, $gui){

        if( !isset($this->referenceBooks[$book]) || !count($this->referenceBooks[$book])){
            $this->referenceBooks[$book] = $this->getRefereceBook($this->referenceBooksGuid[$book]);
        }

        if( empty($this->referenceBooks[$book]) ){
            return false;
        }

        foreach($this->referenceBooks[$book] as $item){
            if( $item[static::ID] == $gui ){
                return $item[static::NAME];
            }
        }
        return false;
    }

    public function getPicture( $gui ){

        ++$_SESSION[self::OFFSET_KEY];
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', self::GET_IMAGE_URI . $gui);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true );
        }
        return false;
    }
}