<?php


namespace Kocmo\Exchange\Tree;


class Product extends Handler
{

    protected $conformity = [];
    protected $conformityName = [];

    function __construct()
    {
        parent::__construct();
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

    public function send3(){
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetCatalog');
        $arrForDb = [];

        if ($response->getStatusCode() == 200) {

            $outArr = json_decode($response->getBody(), true);
            //$firstIter = true;

            foreach( $outArr as $key => $item ){

                //if($firstIter) {
                    $prepareItem = [];

                    foreach ($item as $k => $v) {

                        $arItem = $this->getConformity($k, $v);

//                        if (!empty($arItem['VALUE']) && !isset($this->conformityName[$arItem['VALUE']]) ) {
//                            $this->conformityName[$arItem['VALUE']] = $k;
//                        }
                    }
                    //$firstIter = false;
                //}

                $arrForDb[$item['UID']]['JSON'] = json_encode($item);
                $arrForDb[$item['UID']]["IMG_GUI"] = $item[ $this->conformity[$this->arParams['PIC_FILE']] ];
                $outArr[$key] = null;
            }
        } else {
            throw new \Error("error: status: " . $response->getStatusCode());
        }
    }

    private function getConformity($k, $v){

        if( isset($this->conformity[$k]) && !empty($this->conformity[$k][$v]) ) {
            return $this->conformity[$k][$v];
        }
        else{
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', '/' . $k);
            if ($response->getStatusCode() == 200) {
                $arOut = json_decode($response->getBody(), true);
                if(count($arOut)){
                    $this->conformity[$k] = $arOut;
                    if( !empty($this->conformity[$k][$v]) ) {
                        return $this->conformity[$k][$v];
                    }
                }
            }
        }

        return false;
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