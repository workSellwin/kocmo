<?php


namespace Kocmo\Exchange\Import;
use Kocmo\Exchange\Bx;

class Rest extends Base
{
    function __construct()
    {
        parent::__construct();
    }

    function update ($json) : bool {
        $bx = new Bx\Rest();
        $bx->update(false, $json);

        return true;
    }
}