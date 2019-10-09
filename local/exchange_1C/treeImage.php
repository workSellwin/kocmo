<?php


namespace Asdrubael\Utils;


class treeImage extends treeHandler
{
    const IMAGE_LIMIT = 1000;
    //const PRODUCT_LIMIT = 6000;
    const OFFSET_KEY = 'IMAGE_OFFSET';
    const POINT_OF_ENTRY = 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetFolder/GoodsItems';
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

    protected function fillInOutputArr()
    {
        if( file_exists( $this->tempJsonPath ) && !empty($_SESSION[self::OFFSET_KEY]) ){

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

            $this->send(self::POINT_OF_ENTRY);
        }
    }

    protected function send($uri){

        $success = false;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $uri);

        if ($response->getStatusCode() == 200) {

            $outArr = json_decode($response->getBody(), true);

            foreach($outArr as &$item){
                foreach($item as $key => $value){
                    if( !in_array($key, $this->allowedFields) ){
                        unset($item[$key]);
                    }
                }
            }
            file_put_contents($this->tempJsonPath, json_encode($outArr));
            $this->outputArr = array_slice(
                $outArr,
                0,
                self::IMAGE_LIMIT
            );

            $success = true;
        } else {
            echo "error: status: " . $response->getStatusCode();
            die();
        }
        return $success;
    }


    public function getPicture( $gui ){

        ++$_SESSION[self::OFFSET_KEY];
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', self::GET_IMAGE_URI . $gui);
        //echo '<pre>' . print_r($this->points['image'] . $gui, true) . '</pre>';
        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true );
        }
        return false;
    }
}