<?php


namespace Kocmo\Exchange\Tree;


class Offer extends Builder
{
    protected $conformityName = [];
    protected $arProperty = [];
    protected $allowedGetParams = ['count', 'item'];
    protected $defaultGetParams = ['count' => 500];
    protected $pointOfEntry = false;
    protected $reqParam = [];

    function __construct($params = [])
    {
        if( count($params) ){
            foreach($params as $key => $param)  {
                $this->reqParam[$key] = $param;
            }
        }
        parent::__construct();
        $this->setPointOfEntry($this->arParams['OFFERS_POINT_OF_ENTRY']);
    }

    public function setPointOfEntry($url){

        if(empty($url) || !is_string($url)){
            return false;
        }
        $this->pointOfEntry = $url;

        return true;
    }


    protected function setReqParam(){

        if( $this->utils->checkRef( $this->reqParam['UID'] ) ){
            $itemXmlId = $this->reqParam['UID'];
        }
        elseif( $this->utils->checkRef( $this->utils->getModuleData( $this->arParams['OFFER_LAST_UID'] ) ) ){
            $itemXmlId = $this->utils->getModuleData( $this->arParams['OFFER_LAST_UID'] );
        }

        $limit = $this->reqParam['PRODUCT_LIMIT'] ? $this->reqParam['PRODUCT_LIMIT'] : $this->arParams['PRODUCT_LIMIT'];
        if( !empty($itemXmlId) ){
            $this->strReqParams = 'item=' . $itemXmlId . '&count=' . $limit;
        }

    }

    public function fillInOutputArr(){//?

        $getParamsStr = '?' . $this->getReqParams();
        $this->send($this->arParams['PROD_POINT_OF_ENTRY'] . $getParamsStr);
    }

    public function getProductsFromReq()
    {
        if(empty($this->pointOfEntry)){
            return false;
        }

        $arProps = $this->getPropsFromReq();

        foreach($arProps as $prop){

            $guiMatch = $this->utils->getStrFromGuid($prop['UID']);
            $this->arProperty[$guiMatch] = $prop;
        }

        $getParamsStr =  '?' . $this->getReqParams();

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->pointOfEntry . $getParamsStr);
        $arForDb = [];

        if ($response->getStatusCode() == 200) {

            $arProducts = json_decode($response->getBody(), true);

            foreach( $arProducts as $key => $item ){

                $prepareItem = [];

                foreach( $item as $k => $v ){

                    if($k == $this->arParams['ID']){
                        $g_uid = $this->arParams['ID'];
                    }
                    elseif($k == $this->arParams['PARENT']){
                        $g_uid = $this->arParams['PARENT'];
                    }
                    elseif($k == $this->arParams['PROPERTIES']){
                        $g_uid = $this->arParams['PROPERTIES'];
                    }
                    else{
                        $g_uid = $this->arProperty[$k][$this->arParams['NAME']];
                    }

                    if( $k == $this->arParams['PROPERTIES'] ){

                        $tempProps = [];

                        foreach ($v as $propGui => $propVal){
                            $tempProps[ $this->arProperty[$propGui][$this->arParams['NAME']] ] = $propVal;
                        }
                        $prepareItem[ $g_uid ] = $tempProps;
                    }
                    else{
                        $prepareItem[ $g_uid ] = $v;
                    }
                }

                $arForDb[$prepareItem['UID']]['JSON'] = json_encode($prepareItem);
                $arForDb[$prepareItem['UID']]["IMG_GUI"] = $prepareItem[$this->arParams['PIC_FILE']];
                $arProducts[$key] = null;
            }

        } else {
            throw new \Error("error: status: " . $response->getStatusCode());
        }

        return count($arForDb) ? $arForDb : false;
    }

    public function getPropsFromReq()
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $this->arParams['PROP_POINT_OF_ENTRY']);

        if ($response->getStatusCode() == 200) {

            $arProps = json_decode($response->getBody(), true);
        } else {
            throw new \Error("error: status: " . $response->getStatusCode());
        }
        return $arProps;
    }

    protected function getRefVal( $propGui, $valGui ){
        return "";
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