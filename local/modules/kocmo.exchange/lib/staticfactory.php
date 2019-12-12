<?php


namespace Kocmo\Exchange;
use Kocmo\Exchange\Bx;

final class StaticFactory
{
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
            default:
                return new Bx\End();
        }
    }

    static function nextStep($step){
        return $step + 10;
    }
}