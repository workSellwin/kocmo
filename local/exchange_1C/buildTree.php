<?php
namespace Asdrubael\Utils;

class BuildTree
{
    const PARENT_ID = 'Родитель';
    const ID = "UID";
    const CHILDREN = 'CHILDREN';
    const DEPTH_LEVEL = 'DEPTH_LEVEL';

    private $tree = [];
    private $outputArr = [];

    function __construct($outputArr)
    {
        $tempArr = [];

        foreach( $outputArr as $key => $item )
        {
            if( is_array($item[self::PARENT_ID]) && count($item[self::PARENT_ID]) )
            {
                foreach( $item[self::PARENT_ID] as $parentId)
                {
                    $temp = $item;
                    $temp[self::PARENT_ID] = $parentId;
                    $tempArr[] = $temp;
                }
                unset($outputArr[$key]);
            }
        }
        //echo '<pre>' . print_r($outputArr, true) . '</pre>';
        $this->outputArr = array_merge($outputArr, $tempArr);
    }

    public function createTree()
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

    public function putChild($outputItem, &$treeArr, $depthLvl = 1)
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
        return $this->tree;
    }
}