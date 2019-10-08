<?php


namespace Asdrubael\Utils;


class treeImage extends treeHandler
{
    const IMAGE_LIMIT = 1000;

    protected $allowedFields = [
        'UID', 'ФайлКартинки'
    ];

    function __construct()
    {
        parent::__construct();
    }

    protected function fillInOutputArr($uri)
    {
        if( file_exists( $this->tempJsonPath ) && !empty($_SESSION['image_offset']) ){

            $this->startOffset = $_SESSION['image_offset'];
            $_SESSION['image_offset'] = 0;
            $this->updateJsonFile();
            $this->setSliceFromJson();

            if( count($this->outputArr) == 0){

                $this->outputArr = false;
                $this->delTempFile();
            }
        }
        else{

            $_SESSION['image_offset'] = 0;
            $getParamsStr = "";

            foreach( $_GET as $key => $param){
                if( in_array($key, $this->allowedGetParams) ){
                    $getParamsStr .= $key . '=' . $param . '&';
                }
            }

            $this->send($uri);
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

        ++$_SESSION['image_offset'];
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->points['image'] . $gui);
        //echo '<pre>' . print_r($this->points['image'] . $gui, true) . '</pre>';
        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true );
        }
        return false;
    }
}