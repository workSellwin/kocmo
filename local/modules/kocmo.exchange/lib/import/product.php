<?php


namespace Kocmo\Exchange\Import;


class Product extends Base
{
    public function __construct()
    {
    }

    public function update($param = []) : bool {

        if(!is_array($param)){
            return false;
        }

        if( isset($param[0]) && gettype($param[0]) === 'string' ){

            foreach($param as $xmlId) {
                $tree = new \Kocmo\Exchange\Tree\Product(['UID' => $xmlId, 'PRODUCT_LIMIT' => 1]);
                $tree->fillInOutputArr();
                $ra = $tree->getRequestArr();
                $error = false;
//                pr($ra, 50);
//                die();
                if( !is_array($ra) || !is_array($ra[0]) ){
                    $error = true;
                    $this->setError($xmlId . ": response error\n");
                }
                if( count($ra) !== 1){
                    $error = true;
                    $this->setError($xmlId . ": not found\n");
                }

                if( !$error ) {
                    $bx = \Kocmo\Exchange\StaticFactory::factory(30);
                    $id = $bx->updateOne($ra[0]);

                    if (!$id) {
                        $this->setError($xmlId . ": not updated\n");
                    }
                }
            }
        }
        elseif( isset($param[0]) && isset($param[0]['UID']) ) {
            $bx = \Kocmo\Exchange\StaticFactory::factory(30);
            $bx->updateOne($param[0]);
        }


        return true;
    }
}