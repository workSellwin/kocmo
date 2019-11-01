<?php


namespace Kocmo\Exchange\Bx;


class Offer extends Product
{
    public function __construct($catalogId)
    {
        //$treeBuilder = new \Kocmo\Exchange\Tree\Offer();
        parent::__construct($catalogId);
        $this->treeBuilder->setPointOfEntry( $this->arParams['OFFERS_POINT_OF_ENTRY'] );
    }

}