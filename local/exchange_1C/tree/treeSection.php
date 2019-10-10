<?php


namespace Asdrubael\Utils;


class treeSection extends treeHandler
{
    const PRODUCT_LIMIT = 6000;
    const OFFSET_KEY = 'SECTION_OFFSET';
    const POINT_OF_ENTRY = 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetFolder/GoodsOnlyGroup';

    protected $tempJsonFileName = '/upload/tempSection.json';

    function __construct()
    {
        parent::__construct();
        $this->tempJsonPath = $_SERVER['DOCUMENT_ROOT'] . $this->tempJsonFileName;
        $this->fillInOutputArr();
    }

    protected function fillInOutputArr()
    {

        if (file_exists($this->tempJsonPath) && !empty($_SESSION[self::OFFSET_KEY])) {

            $this->startOffset = $_SESSION[self::OFFSET_KEY];
            $_SESSION[self::OFFSET_KEY] = 0;
            $this->updateJsonFile();
            $this->setSliceFromJson();

            if (count($this->outputArr) == 0) {

                $this->outputArr = false;
                $this->delTempFile();
            }
        } else {

            $_SESSION[self::OFFSET_KEY] = 0;
            $getParamsStr = "";

            foreach ($_GET as $key => $param) {
                if (in_array($key, $this->allowedGetParams)) {
                    $getParamsStr .= $key . '=' . $param . '&';
                }
            }

            $this->send(self::POINT_OF_ENTRY . '?' . $getParamsStr);
        }

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

    public function getTree()
    {
        if( !count($this->tree)){
            $this->createTree();
        }
        return $this->tree;
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

    public function getAllXmlId(){

        $allIdArr = [];

        foreach( $this->structGenerator( $this->getTree() ) as $value){
            $allIdArr[] = $value[self::ID];
        }
        return $allIdArr;
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
}