<?php
namespace Asdrubael\Utils;

class treeHandler
{
    const PRODUCT_LIMIT = 6000;
    const WAITING_TIME = 0;
    const PARENT_ID = 'Родитель';
    const ID = "UID";
    const CHILDREN = 'CHILDREN';
    const DEPTH_LEVEL = 'DEPTH_LEVEL';
    const NAME = "Наименование";
    const FULL_NAME = "НаименованиеПолное";
    const PROPERTIES = "Свойства";

    protected $tempJsonFileName = [
        'products' => '/upload/tempProduct.json',
        'sections' => '/upload/tempSection.json',
        'images' => '/upload/tempImage.json'
    ];
    protected $tempJsonPath = false;
    protected $tree = [];
    protected $outputArr = [];
    protected $points = [
        "sections" => 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetFolder/GoodsOnlyGroup',
        "goods" => 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetFolder/GoodsItems',
        "reference" => 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetReference/',
        "image" => 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetImage/',
    ];
    protected $allowedGetParams = [
        'group'
    ];
    protected $referenceBooksGuid = [
        "СтранаПроисхождения" => "42d1086a-9ccb-11e8-a215-00505601048d",
        "ТоварнаяГруппа" => "42d1086e-9ccb-11e8-a215-00505601048d",
        "Производитель" => "42d1082a-9ccb-11e8-a215-00505601048d",
        "Марка" => "42d1082e-9ccb-11e8-a215-00505601048d",
        "Коллекция" => "42d1081c-9ccb-11e8-a215-00505601048d",
    ];
    protected $referenceBookds = [];
    protected $startOffset = 0;

    function __construct()
    {

        if( $_GET['mode'] == "get_sections" ) {
            $uri = $this->points['sections'];
            $this->tempJsonPath = $_SERVER['DOCUMENT_ROOT'] . $this->tempJsonFileName['sections'];
        }
        if( $_GET['mode'] == "get_struct_sections" ) {
            $uri = $this->points['sections'];
            $this->tempJsonPath = $_SERVER['DOCUMENT_ROOT'] . $this->tempJsonFileName['sections'];
        }
        elseif( $_GET['mode'] == "get_goods" ){
            $uri = $this->points['goods'];
            $this->tempJsonPath = $_SERVER['DOCUMENT_ROOT'] . $this->tempJsonFileName['products'];
        }
        elseif( $_GET['mode'] == "create_structure" ){
            $uri = $this->points['sections'];
            $this->tempJsonPath = $_SERVER['DOCUMENT_ROOT'] . $this->tempJsonFileName['sections'];
        }
        elseif( $_GET['mode'] == "get_all_xmlid" ){
            $uri = $this->points['sections'];
            $this->tempJsonPath = $_SERVER['DOCUMENT_ROOT'] . $this->tempJsonFileName['sections'];
        }
        elseif( $_GET['mode'] == "add_products" ){
            $uri = $this->points['goods'];
            $this->tempJsonPath = $_SERVER['DOCUMENT_ROOT'] . $this->tempJsonFileName['products'];
        }
        elseif( $_GET['mode'] == "save_image" ){
            $uri = $this->points['goods'];
            $this->tempJsonPath = $_SERVER['DOCUMENT_ROOT'] . $this->tempJsonFileName['images'];
        }

        if( empty($uri) ){
            echo "URL not defined";
            die();
        }

        $this->fillInOutputArr($uri);
    }

    protected function fillInOutputArr($uri){

        if( file_exists( $this->tempJsonPath ) && !empty($_SESSION['offset']) ){

            $this->startOffset = $_SESSION['offset'];
            $_SESSION['offset'] = 0;
            $this->updateJsonFile();
            $this->setSliceFromJson();

            if( count($this->outputArr) == 0){

                $this->outputArr = false;
                $this->delTempFile();
            }
        }
        else{

            $_SESSION['offset'] = 0;
            $getParamsStr = "";

            foreach( $_GET as $key => $param){
                if( in_array($key, $this->allowedGetParams) ){
                    $getParamsStr .= $key . '=' . $param . '&';
                }
            }

            $this->send($uri . '?' . $getParamsStr);
        }

        if(!empty($this->outputArr) && $uri == $this->points['sections']) {

            $tempArr = [];

            foreach ($this->outputArr as $key => $item) {
                if (is_array($item[self::PARENT_ID]) && count($item[self::PARENT_ID])) {
                    foreach ($item[self::PARENT_ID] as $parentId) {
                        $temp = $item;
                        $temp[self::PARENT_ID] = $parentId;
                        $tempArr[] = $temp;
                    }
                    unset($this->outputArr[$key]);
                }
            }
            $this->outputArr = array_merge($this->outputArr, $tempArr);
        }
    }

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
        file_put_contents($this->tempJsonPath, json_encode($this->outputArr));
    }

    protected function delTempFile(){
        return unlink( $this->tempJsonPath );
    }

    protected function send($uri)
    {
        $success = false;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $uri);

        if ($response->getStatusCode() == 200) {
            file_put_contents($this->tempJsonPath, $response->getBody());
            $outArr = json_decode($response->getBody(), true);

            $this->outputArr = array_slice(
                $outArr,
                0,
                self::PRODUCT_LIMIT
             );

            $success = true;
        } else {
            echo "error: status: " . $response->getStatusCode();
            die();
        }

        return $success;
    }
    /**
     * @param array $tree
     */
    public function setTree($tree)
    {
        $this->tree = $tree;
    }

    private function createTree()
    {
        $length = count($this->outputArr);

        foreach( $this->outputArr as $key => $item ){

            if( $item[self::PARENT_ID] === "" )
            {
                $this->tree[$item[self::ID]] = $item;
                $this->tree[$item[self::ID]][self::DEPTH_LEVEL] = 0;
                $this->tree[$item[self::ID]][self::CHILDREN] = [];

                unset($this->outputArr[$key]);
            }
            elseif( is_array($item[self::PARENT_ID]) && count($item[self::PARENT_ID]) )
            {
            }
            elseif( strlen($item[self::PARENT_ID]) > 0 )
            {
                if( $this->putChild($item, $this->tree) )
                    unset($this->outputArr[$key]);
            }
        }

        if( $length > count($this->outputArr) )
        {
            $this->createTree();
        }
        //echo '<pre>' . print_r($this->outputArr, true) . '</pre>';
    }

    private function putChild($outputItem, &$treeArr, $depthLvl = 1)
    {
        $needId = $outputItem[self::PARENT_ID];

        foreach( $treeArr as &$item ){

            if( $item[self::ID] == $needId && !$this->checkExist($outputItem[self::ID], $item[self::CHILDREN]) )
            {
                $outputItem[self::DEPTH_LEVEL] = $depthLvl;
                $item[self::CHILDREN][] = array_merge($outputItem, [self::CHILDREN => []]);
                return true;
            }
            elseif( is_array( $item[self::CHILDREN] ) && count( $item[self::CHILDREN] ) )
            {
                $this->putChild( $outputItem, $item[self::CHILDREN], $depthLvl+1);
            }
        }
        return false;
    }

    private function checkExist( $need, $arr )
    {
        foreach( $arr as $item )
        {
            if( $item[self::ID] == $need)
            {
                return true;
            }
        }
        return false;
    }

    public function getTree()
    {
        if( !count($this->tree)){
            $this->createTree();
        }
        return $this->tree;
    }

    public function structGenerator( $tree ){

        foreach( $tree as &$section ){

            yield $this->prepareSection($section);
            if( count($section[self::CHILDREN]) ){
                yield from $this->structGenerator($section[self::CHILDREN]);
            }
        }
    }

    private function prepareSection(&$section){

        $tempArr = [];

        $allowedFields = [
            self::ID, self::PARENT_ID, self::NAME, self::DEPTH_LEVEL
        ];

        foreach( $section as $k => $fld){
            if( in_array($k, $allowedFields) ){
                $tempArr[$k] = $fld;
            }
        }
        return $tempArr;
    }

    public function getAllXmlId(){

        $allIdArr = [];

        foreach( $this->structGenerator( $this->getTree() ) as $value){
            $allIdArr[] = $value[self::ID];
        }
        return $allIdArr;
    }

    public function getProductParentsXmlId(){

        $returnVal = [];

        foreach( $this->outputArr as $item){
            $returnVal[$item[self::PARENT_ID]] =
                isset($returnVal[$item[self::PARENT_ID]]) ? ++$returnVal[$item[self::PARENT_ID]] : 0;
        }
        return $returnVal;
    }

    private function getRefereceBook($gui){
        $arr = $this->send($this->points['reference'] . $gui);
        return $arr;
    }

    public function getRefValue($book, $gui){

        if( !isset($this->referenceBookds[$book]) || !count($this->referenceBookds[$book])){
            $this->referenceBookds[$book] = $this->getRefereceBook($this->referenceBooksGuid[$book]);
        }

        if( empty($this->referenceBookds[$book]) ){
            return false;
        }

        foreach($this->referenceBookds[$book] as $item){
            if( $item[self::ID] == $gui ){
                return $item[self::NAME];
            }
        }
        return false;
    }
}