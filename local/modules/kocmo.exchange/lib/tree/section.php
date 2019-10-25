<?php


namespace Kocmo\Exchange\Tree;


class Section extends Builder
{
    function __construct()
    {
        parent::__construct();
    }

    public function fillInOutputArr()
    {

        $this->send($this->arParams['SECT_POINT_OF_ENTRY']);

        $tempArr = [];

        foreach ($this->outputArr as $key => $item) {

            if (is_array($item[$this->arParams['PARENT_ID']]) && count($item[$this->arParams['PARENT_ID']])) {
                foreach ($item[$this->arParams['PARENT_ID']] as $parentId) {
                    $temp = $item;
                    $temp[$this->arParams['PARENT_ID']] = $parentId;
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
        if(!count($this->outputArr)){
            $this->fillInOutputArr();
        }
        $length = count($this->outputArr);

        foreach( $this->outputArr as $key => $item ){

            if( $item[$this->arParams['PARENT_ID']] === "" )
            {
                $this->tree[$item[$this->arParams['ID']]] = $item;
                $this->tree[$item[$this->arParams['ID']]][$this->arParams['DEPTH_LEVEL']] = 0;
                $this->tree[$item[$this->arParams['ID']]][$this->arParams['CHILDREN']] = [];
                //unset($this->outputArr[$key]);
            }
            elseif( is_array($item[$this->arParams['PARENT_ID']]) && count($item[$this->arParams['PARENT_ID']]) )
            {
            }
            elseif( strlen($item[$this->arParams['PARENT_ID']]) > 0 )
            {
                if( $this->putChild($item, $this->tree) ) {
                    //unset($this->outputArr[$key]);
                }
            }
        }

        if( $length > count($this->outputArr) )
        {
            $this->createTree();
        }
    }

    private function putChild($outputItem, &$treeArr, $depthLvl = 1)
    {
        $needId = $outputItem[$this->arParams['PARENT_ID']];

        foreach( $treeArr as &$item ){

            if( $item[$this->arParams['ID']] == $needId && !$this->checkExist($outputItem[$this->arParams['ID']], $item[$this->arParams['CHILDREN']]) )
            {
                $outputItem[$this->arParams['DEPTH_LEVEL']] = $depthLvl;
                $item[$this->arParams['CHILDREN']][] = array_merge($outputItem, [$this->arParams['CHILDREN'] => []]);
                return true;
            }
            elseif( is_array( $item[$this->arParams['CHILDREN']] ) && count( $item[$this->arParams['CHILDREN']] ) )
            {
                $this->putChild( $outputItem, $item[$this->arParams['CHILDREN']], $depthLvl+1);
            }
        }
        return false;
    }

    private function checkExist( $need, $arr )
    {
        foreach( $arr as $item )
        {
            if( $item[$this->arParams['ID']] == $need)
            {
                return true;
            }
        }
        return false;
    }

    public function getAllXmlId(){

        $allIdArr = [];

        foreach( $this->structGenerator( $this->getTree() ) as $value){
            $allIdArr[] = $value[$this->arParams['ID']];
        }
        return $allIdArr;
    }

    public function structGenerator( $tree ){

        foreach( $tree as &$section ){

            yield $this->prepareSection($section);
            if( count($section[$this->arParams['CHILDREN']]) ){
                yield from $this->structGenerator($section[$this->arParams['CHILDREN']]);
            }
        }
    }

    private function prepareSection(&$section){

        $tempArr = [];

        $allowedFields = [
            $this->arParams['ID'], $this->arParams['PARENT_ID'], $this->arParams['NAME'], $this->arParams['DEPTH_LEVEL']
        ];

        foreach( $section as $k => $fld){
            if( in_array($k, $allowedFields) ){
                $tempArr[$k] = $fld;
            }
        }
        return $tempArr;
    }
}