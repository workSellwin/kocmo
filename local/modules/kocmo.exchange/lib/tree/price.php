<?php


namespace Kocmo\Exchange\Tree;


class Price extends Builder
{
    function __construct()
    {
        parent::__construct();
        $this->fillInOutputArr();
    }
}