<?php


namespace Kocmo\Exchange\Import;
use Kocmo\Exchange\Bx;

class Price extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update ($json) : bool{

        $bx = new Bx\Price();
        $bx->update(false, $json);

        return true;
    }
}