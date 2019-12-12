<?php


namespace Lui\Kocmo\Request\Get;


class EGifts extends BaseGet
{

    /**
     * Certificate constructor.
     * @param string $id
     */
    public function __construct(string $json = '')
    {
        parent::__construct(\Lui\Kocmo\Request\Config\Url::GetNumberEGifts());
        $this->SetQuery(['json' => $json]);
    }

    public function SetQuery(array $arQuery)
    {
        $this->arQuery = $arQuery;
    }

}
