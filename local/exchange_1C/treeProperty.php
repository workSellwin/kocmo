<?php


namespace Asdrubael\Utils;


class treeProperty extends treeProduct
{
    const PRODUCT_LIMIT = 1;

    function __construct()
    {
        parent::__construct();
    }

    public function getProperties(){
        echo '<pre>' . print_r($this, true) . '</pre>';die();
    }

    protected function fillInOutputArr(){

        if( file_exists( $this->tempJsonPath ) && !empty($_SESSION[self::OFFSET_KEY]) ){
            $this->outputArr = file_get_contents($this->tempJsonPath, true);
        }
        else{
            $this->send(static::POINT_OF_ENTRY );
        }
    }
}