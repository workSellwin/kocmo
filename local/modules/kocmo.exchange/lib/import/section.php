<?php


namespace Kocmo\Exchange\Import;
use Kocmo\Exchange\Bx;

class Section extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    function update ($param = []) : bool {

        if(isset($param[0]) && $param[0] === "update"){
            $bx = new Bx\Section();
            return $bx->update();
        }
        else{
            $this->setError("un correct param");
            return false;
        }
    }
}