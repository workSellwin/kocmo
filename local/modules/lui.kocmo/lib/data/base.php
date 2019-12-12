<?php

namespace Lui\Kocmo\Data;

abstract class Base implements IblockInterface
{
    protected $key = 'ID';

    /**
     * Base constructor.
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        if (!\Bitrix\Main\Loader::includeModule('iblock'))
            throw new  \Exception('No Module IBlock');
    }

    /**
     * @param string $key
     * @return $this|IblockInterface
     */
    public function SetKey(string $key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param int $id
     * @return array
     */
    public function GetID(int $id): array
    {
        $arFilter = Array("ID" => $id);
        return $this->GetData($arFilter);
    }

    /**
     * @param array $arId
     * @return array
     */
    public function GetIDs(array $arId): array
    {
        $arFilter = ["ID" => $arId];
        return $this->GetData($arFilter);
    }

    /**
     * @param string $id
     * @return array
     */
    public function GetXmlID(string $id): array
    {
        $arFilter = Array("XML_ID" => $id);
        return $this->GetData($arFilter);
    }

    /**
     * @param array $arXmlIDs
     * @return array
     */
    public function GetXmlIDs(array $arXmlIDs): array
    {
        $arFilter = Array("XML_ID" => $arXmlIDs);
        return $this->GetData($arFilter);
    }

    /**
     * @param array $arFilter
     * @return array
     */
    abstract function GetData(array $arFilter): array;



}
