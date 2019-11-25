<?php


namespace Kocmo\Exchange;
use Kocmo\Exchange\Bx;

final class StaticFactory
{

    private $stages = [
        '0' => 'section',
        '10' => 'property',
        '20' => 'db_product',
        '30' => 'offer',
        '40' => 'product',
        '50' => 'store',
        '60' => 'rest',
        '70' => 'type_price',
        '80' => 'price',
        '90' => 'image',
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
                return new Bx\Offer();
                break;
            case '40':
                return new Bx\Product();
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
            default:
                return null;
        }
    }

    static function nextStep($step){

        if($step == 30){
            return $step + 20;
        }
        elseif($step == 90){

        }
        return $step + 10;
    }
}