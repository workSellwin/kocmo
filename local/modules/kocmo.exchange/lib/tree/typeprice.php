<?php


namespace Kocmo\Exchange\Tree;


class Typeprice extends Builder
{
    function __construct()
    {
        parent::__construct();
        $this->fillInOutputArr();
    }

    public function fillInOutputArr(){
        $this->send($this->arParams['TYPE_PRICE']);
    }
}