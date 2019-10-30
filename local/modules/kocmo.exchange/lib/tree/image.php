<?php


namespace Kocmo\Exchange\Tree;


class Image extends Builder
{
    protected $allowedFields = [
        'UID', 'ФайлКартинки'
    ];

    function __construct()
    {
        parent::__construct();
    }

    public function fillInOutputArr()
    {
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