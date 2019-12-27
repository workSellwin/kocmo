<?php


namespace Kocmo\Exchange\Import;
use \Kocmo\Exchange;

class Product extends Base
{
    private $queueLimit = 100;

    public function __construct()
    {
        parent::__construct();
    }

    public function update($param = []) : bool {

        if(!is_array($param)){
            $this->setError("Invalid data format");
            return false;
        }

        if( isset($param[0]) && gettype($param[0]) === 'string' ){

            if( count($param) > $this->queueLimit ){
                $this->setError("Maximum queue limit exceeded");
                return false;
            }

            foreach($param as $xmlId) {

                $tree = new Exchange\Tree\Product(['UID' => $xmlId, 'PRODUCT_LIMIT' => 1]);
                $tree->fillInOutputArr();
                $ra = $tree->getRequestArr();
                $error = false;

                if( !is_array($ra) || !is_array($ra[0]) ){
                    $error = true;
                    $this->setError($xmlId . ": response error\n");
                }
                if( count($ra) !== 1){
                    $error = true;
                    $this->setError($xmlId . ": not found\n");
                }

                if( !$error ) {
                    $bx = Exchange\StaticFactory::factory(30);
                    $id = $bx->updateOne($ra[0]);

                    if (!$id) {
                        $this->setError($xmlId . ": not updated\n");
                    }
                    else{
                        $this->setValues($xmlId);
                    }
                }
            }
            return true;
        }
//        elseif( isset($param[0]) && isset($param[0]['UID']) ) {
//            $bx = Exchange\StaticFactory::factory(30);
//            $bx->updateOne($param[0]);
//        }

        return false;
    }
}