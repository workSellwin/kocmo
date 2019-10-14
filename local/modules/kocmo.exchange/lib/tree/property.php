<?php


namespace Kocmo\Exchange\Tree;


class Property extends Product
{
    const PRODUCT_LIMIT = 1;

    function __construct()
    {
        parent::__construct();
    }

    public function fillInOutputArr(){

        $this->send(static::POINT_OF_ENTRY . '?group=f7465fbc-c80a-11e9-a247-00505601048d');//gui group может быть любая
        $this->outputArr = $this->outputArr[0][static::PROPERTIES][0];
    }
}