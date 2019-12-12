<?php


namespace Lui\Kocmo\Request\Get;


class Certificate extends BaseGet
{
    /**
     * Certificate constructor.
     * @param string $id
     */
    public function __construct(string $id = '')
    {
        parent::__construct(\Lui\Kocmo\Request\Config\Url::GetCertificate());
        $this->SetQuery(['id' => $id]);
    }

    public function SetQuery(array $arQuery)
    {
        $this->arQuery = $arQuery;
    }
}
