<?php


namespace Kocmo\Exchange\Tree;


class Typeprice extends Builder
{
    function __construct()
    {
        parent::__construct();
        $this->entry = $this->arParams['TYPE_PRICE_ENTRY'];
        $this->fillInOutputArr();
    }
}