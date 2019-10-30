<?php


namespace Kocmo\Exchange\Tree;


class Rest extends Builder
{
    function __construct()
    {
        parent::__construct();
        $this->entry = $this->arParams['REST_ENTRY'];
        $this->fillInOutputArr();
    }
}