<?php

namespace Lui\Kocmo\Action;

use Lui\Kocmo\Interfaces\ActionsInterfaces;

class Favourites implements ActionsInterfaces
{

    /**
     * Возвращает доступные методы запроса!
     *
     * @return array
     */
    public function Available()
    {
        return ['Add', 'Delete'];
    }

    public function Add($arParams){
        return $arParams;
    }

}
