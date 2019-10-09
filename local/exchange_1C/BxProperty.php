<?php


namespace Asdrubael\Utils;


class BxProperty extends BxHelper
{

    public function __construct(treeHandler $treeBuilder, $catalogId)
    {
        parent::__construct($treeBuilder, $catalogId);
    }

    public function updateProperty(){
        echo '<pre>' . print_r($this, true) . '</pre>';die();
    }
}