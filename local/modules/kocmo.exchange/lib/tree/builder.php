<?php
namespace Kocmo\Exchange\Tree;
use Kocmo\Exchange;

abstract class Builder
{
    /* @var $utils \Kocmo\Exchange\Utils */
    protected $utils = null;
    protected $arParams = [];
    protected $strReqParams = false;
    protected $tree = [];
    protected $outputArr = [];
    protected $allowedGetParams = [];
    protected $defaultGetParams = [];
    protected $entry = false;
    protected $referenceBooks = [];
    protected $startOffset = 0;


    function __construct()
    {
        $this->setParams();
    }

    protected function setParams(){

        $this->utils = new Exchange\Utils();

        if(!empty($GLOBALS['kocmo.exchange.config-path'])) {

            $arParam = require $GLOBALS['kocmo.exchange.config-path'];
            $dir = end(explode('/', __DIR__));
            $this->arParams = $arParam[$dir];
        }
        $this->setReqParam();
    }

    protected function setReqParam(){

        if (count($_GET)) {
            $get = $_GET;

            if (count($this->defaultGetParams)) {
                foreach ($this->defaultGetParams as $key => $dp) {
                    if (empty($get[$key])) {
                        $get[$key] = $dp;
                    }
                }
            }
            $getParamsStr = "";

            foreach ($get as $key => $param) {
                if (in_array($key, $this->allowedGetParams)) {
                    $getParamsStr .= $key . '=' . $param . '&';
                }
            }

            $this->strReqParams = $getParamsStr;
        }
    }

    public function fillInOutputArr(){

        if( !is_string($this->entry) ){
            return false;
        }
        $this->send($this->entry);
    }

    /**
     * @return array|bool
     */
    public function getRequestArr()
    {
        return  $this->outputArr;
    }

    protected function send($uri)
    {
        $success = false;
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $uri);

        if ($response->getStatusCode() == 200) {

            $this->outputArr = json_decode($response->getBody(), true);
            $success = true;
        } else {
            throw new \Error("error: status: " . $response->getStatusCode());
        }
        return $success;
    }

    protected function getReqParams(){

        if(empty($this->strReqParams)){
            return "";
        }
        else{
            return $this->strReqParams;
        }
    }

    public function setOutputArr($data){
        $this->outputArr = $data;
    }
}