<?php
namespace Asdrubael\Utils;

class treeHandler
{
    const PARENT_ID = 'Родитель';
    const ID = "UID";
    const CHILDREN = 'CHILDREN';
    const DEPTH_LEVEL = 'DEPTH_LEVEL';
    const NAME = "Наименование";

    private $tree = [];
    private $outputArr = [];
    private $points = [
        "sections" => 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetFolder/GoodsOnlyGroup',
        "goods" => 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetFolder/GoodsItems',
    ];
    private $allowedGetParams = [
        'group'
    ];

    function __construct()
    {
        if( $_GET['mode'] == "get_sections" ) {
            $uri = $this->points['sections'];
        }
        if( $_GET['mode'] == "get_struct_sections" ) {
            $uri = $this->points['sections'];
        }
        elseif( $_GET['mode'] == "get_goods" ){
            $uri = $this->points['goods'];
        }
        elseif( $_GET['mode'] == "create_structure" ){
            $uri = $this->points['sections'];
        }
        elseif( $_GET['mode'] == "get_all_xmlid" ){
            $uri = $this->points['sections'];
        }


        if( empty($uri) ){
            echo "URL not defined";
            die();
        }

        $getParamsStr = "";

        foreach( $_GET as $key => $param){
            if( in_array($key, $this->allowedGetParams) ){
                $getParamsStr .= $key . '=' . $param . '&';
            }
        }
        $tempArr = [];

        $outputArr = $this->send($uri . '?' . $getParamsStr);

        if(!empty($outputArr)) {

            foreach ($outputArr as $key => $item) {
                if (is_array($item[self::PARENT_ID]) && count($item[self::PARENT_ID])) {
                    foreach ($item[self::PARENT_ID] as $parentId) {
                        $temp = $item;
                        $temp[self::PARENT_ID] = $parentId;
                        $tempArr[] = $temp;
                    }
                    unset($outputArr[$key]);
                }
            }
        }
        $this->outputArr = array_merge($outputArr, $tempArr);
    }

    private function send($uri)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $uri);

        if ($response->getStatusCode() == 200) {
            $outArr = json_decode($response->getBody(), true);
        } else {
            echo "error: status: " . $response->getStatusCode();
            die();
        }
        return $outArr;
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
}