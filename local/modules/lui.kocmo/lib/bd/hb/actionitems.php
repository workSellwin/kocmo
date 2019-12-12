<?php


namespace Lui\Kocmo\BD\HB;


class ActionItems extends HardIB
{
    public function __construct()
    {
        parent::__construct(7);
    }

    /**
     * @param $xmlID
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    public function GetLink($xmlID)
    {
        return $this->GetList(['UF_ACTIONS' => $xmlID]);
    }

    /**
     * @param int $id
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    public function GetProduct(int $id)
    {
        return $this->GetList(['UF_LINK' => $id]);
    }
}
