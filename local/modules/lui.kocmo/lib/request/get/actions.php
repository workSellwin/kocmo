<?php

namespace Lui\Kocmo\Request\Get;

class Actions extends BaseGet
{
    /**
     * Actions constructor.
     * @param string $shop
     * @throws \Exception
     */
    public function __construct(string $shop = '')
    {
        parent::__construct(\Lui\Kocmo\Request\Config\Url::GetActions());
        $this->SetQuery(['shop' => $shop]);
    }

    public function SetQuery(array $arQuery)
    {
        $this->arQuery = $arQuery;
    }
}
