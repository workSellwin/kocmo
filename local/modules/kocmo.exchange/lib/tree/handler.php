<?php
namespace Kocmo\Exchange\Tree;

abstract class Handler
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
        //pr($uri);die();
        $response = $client->request('GET', $uri);

        if ($response->getStatusCode() == 200) {

            $this->outputArr = json_decode($response->getBody(), true);
            $success = true;
        } else {
            throw new \Error("error: status: " . $response->getStatusCode());
        }
        return $success;
    }
}