<?php
namespace Kocmo\Exchange\Tree;

abstract class Builder
{

    protected $arParams = [];
    protected $tempJsonPath = false;
    protected $tree = [];
    protected $outputArr = [];
    protected $allowedGetParams = [
        'group'
    ];

    protected $referenceBooks = [];
    protected $startOffset = 0;

    function __construct()
    {
        $this->setParams();
    }

    protected function setParams(){

        $arParam = require $GLOBALS['kocmo.exchange.config-path'];
        $dir = end( explode('/', __DIR__) );
        $this->arParams = $arParam[$dir];
    }

    abstract public function fillInOutputArr();

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

    public function getGuid( $str ){
        return str_replace(["g_", "_"], ["", "-"], $str);
    }

    public function getStrFromGuid( $guid ){
        return "g_" . str_replace("-", "_", $guid);
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
}