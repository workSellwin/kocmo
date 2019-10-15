<?php


namespace Kocmo\Exchange\Tree;


class Image extends Handler
{
    
    const GET_IMAGE_URI = 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetImage/';

    protected $tempJsonFileName = '/upload/tempImage.json';

    protected $allowedFields = [
        'UID', 'ФайлКартинки'
    ];

    function __construct()
    {
        parent::__construct();
        $this->tempJsonPath = $_SERVER['DOCUMENT_ROOT'] . $this->tempJsonFileName;
    }

    public function fillInOutputArr()
    {
        $getParamsStr = "";

        foreach ($_GET as $key => $param) {
            if (in_array($key, $this->allowedGetParams)) {
                $getParamsStr .= $key . '=' . $param . '&';
            }
        }

        $this->send($this->arParams['PROD_POINT_OF_ENTRY']);

    }

    public function getPicture($gui)
    {

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->arParams['GET_IMAGE_URI'] . $gui);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);
        }
        return false;
    }
}