<?php


namespace Kocmo\Exchange;
use Kocmo\Exchange\Bx,
    Kocmo\Exchange\Import;

final class StaticFactory
{
    static $ImportStages = [
        'section' => 200,
//        'property' => 10,
        'product' => 230,
//        'store' => 50,
        'rest' => 260,
//        'typeprice' => 70,
        'price' => 280,
//        'Image' => 90,
//        'end' => 100,
    ];

    static function factory($stage = '0'){

        switch($stage){
            case '0':
                return new Bx\Section();
                break;
            case '10':
                return new Bx\Property();
                break;
            case '20':
                return new Bx\Dbproduct();
                break;
            case '30':
                return new Bx\Product();
                break;
            case '40':
                return new Bx\Offer();
                break;
            case '50':
                return new Bx\Store();
                break;
            case '60':
                return new Bx\Rest();
                break;
            case '70':
                return new Bx\Typeprice();
                break;
            case '80':
                return new Bx\Price();
                break;
            case '90':
                return new Bx\Image();
                break;
            case '100':
                return new Bx\End();
                break;
            case '200':
                return new Import\Section();
                break;
            case '230':
                return new Import\Product();
                break;
            case '260':
                return new Import\Rest();
                break;
            case '280':
                return new Import\Price();
                break;
        }
    }

    static function nextStep($step){
        return $step + 10;
    }

    static function getActionStage(string $action){

        $action = strtolower($action);

        if(isset(self::$ImportStages[$action])) {
            return self::$ImportStages[$action];
        }
        return false;
    }
}