<?php


namespace Kocmo\Exchange\Tree;


class Product extends Handler
{
    //const PRODUCT_LIMIT = 1000;
//    const PIC_FILE = 'ФайлКартинки';
//    const OFFSET_KEY = 'PRODUCT_OFFSET';
//    const POINT_OF_ENTRY = '';
//    const REFERENCE_URL = 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetReference/';
//    const GET_IMAGE_URI = 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetImage/';

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

        //$this->fillInOutputArr();
    }

    public function fillInOutputArr(){

        $getParamsStr = "";

        foreach ($_GET as $key => $param) {
            if (in_array($key, $this->allowedGetParams)) {
                $getParamsStr .= $key . '=' . $param . '&';
            }
        }
        $getParamsStr = 'group=00f9b68a-85ea-11e9-b3b3-005056aa8896';
        $this->send($this->arParams['PROD_POINT_OF_ENTRY'] . '?' . $getParamsStr);
    }

    public function send2()
    {
        $getParamsStr = "";

        foreach( $_GET as $key => $param){
            if( in_array($key, $this->allowedGetParams) ){
                $getParamsStr .= $key . '=' . $param . '&';
            }
        }
        $getParamsStr = 'group=00f9b68a-85ea-11e9-b3b3-005056aa8896';//temp
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->arParams['PROD_POINT_OF_ENTRY'] . '?' . $getParamsStr);
        $arrForDb = [];

        if ($response->getStatusCode() == 200) {

            $outArr = json_decode($response->getBody(), true);

            foreach( $outArr as $key => $item ){

                $arrForDb[$item['UID']]['JSON'] = json_encode($item);
                $arrForDb[$item['UID']]["IMG_GUI"] = $item[$this->arParams['PIC_FILE']];
                $outArr[$key] = null;
            }

        } else {
            throw new \Error("error: status: " . $response->getStatusCode());
        }

        return count($arrForDb) ? $arrForDb : false;
    }

    public function getProductParentsXmlId(){

        $returnVal = [];

        foreach( $this->outputArr as $item){
            $returnVal[$item[$this->arParams['PARENT_ID']]] =
                isset($returnVal[$item[$this->arParams['PARENT_ID']]]) ? ++$returnVal[$item[$this->arParams['PARENT_ID']]] : 0;
        }
        return $returnVal;
    }

    private function getRefereceBook($gui){
        
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->arParams['REFERENCE_URL'] . $gui);

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
            if( $item[$this->arParams['ID']] == $gui ){
                return $item[$this->arParams['NAME']];
            }
        }
        return false;
    }

    public function getPicture( $gui ){

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->arParams['GET_IMAGE_URI'] . $gui);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true );
        }
        return false;
    }

    public function getImageUri(){
        return $this->arParams['GET_IMAGE_URI'];
    }
}