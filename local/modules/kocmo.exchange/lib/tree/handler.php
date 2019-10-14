<?php
namespace Kocmo\Exchange\Tree;

abstract class Handler
{
    const PRODUCT_LIMIT = 1000;
    const WAITING_TIME = 0;
    const PARENT_ID = 'Родитель';
    const ID = "UID";
    const CHILDREN = 'CHILDREN';
    const DEPTH_LEVEL = 'DEPTH_LEVEL';
    const NAME = "Наименование";
    const FULL_NAME = "НаименованиеПолное";
    const PROPERTIES = "Свойства";

    protected $tempJsonPath = false;
    protected $tree = [];
    protected $outputArr = [];
    protected $allowedGetParams = [
        'group'
    ];

    protected $referenceBooks = [];
    protected $startOffset = 0;
    protected $status = ['status' => 'start'];

    function __construct()
    {
        $this->status['status'] = 'run';
    }

    abstract public function fillInOutputArr();

    /**
     * @return array|bool
     */
    public function getRequestArr()
    {
        return  $this->outputArr;
    }

    protected function setSliceFromJson(){

        $this->outputArr = array_slice(
            $this->outputArr,
            0,
            self::PRODUCT_LIMIT
        );
    }

    protected function updateJsonFile(){

        $file = file_get_contents($this->tempJsonPath);
        $fromFileArr = json_decode($file, true);

        $this->outputArr = array_slice(
            $fromFileArr,
            $this->startOffset
        );

        if( count($this->outputArr) ){
            file_put_contents($this->tempJsonPath, json_encode($this->outputArr));
        }
        else{
            $this->delTempFile();
        }
    }

    protected function delTempFile(){
        $this->status['status'] = 'end';
        return unlink( $this->tempJsonPath );
    }

    public function getStatus(){
        return $this->status;
    }

    protected function send($uri, $getArray = true )
    {
        $success = false;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $uri);

        if ($response->getStatusCode() == 200) {
            //file_put_contents($this->tempJsonPath, $response->getBody());
            //$outArr = json_decode($response->getBody(), true);
            $this->outputArr = json_decode($response->getBody(), true);

//            $this->outputArr = array_slice(
//                $outArr,
//                0,
//                static::PRODUCT_LIMIT
//             );

            $success = true;
        } else {
            throw new \Error("error: status: " . $response->getStatusCode());
        }

        return $success;
    }

    public function getOffsetKey(){
        return static::OFFSET_KEY;
    }
}