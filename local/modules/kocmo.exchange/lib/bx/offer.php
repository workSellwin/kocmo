<?php


namespace Kocmo\Exchange\Bx;


class Offer extends Product
{
    public function __construct()
    {
        //$treeBuilder = new \Kocmo\Exchange\Tree\Offer();
        parent::__construct();
        $this->treeBuilder->setPointOfEntry( $this->arParams['OFFERS_POINT_OF_ENTRY'] );
        $this->catalogId = $this->arParams['IBLOCK_OFFERS_ID'];
    }

}