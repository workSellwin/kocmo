<?php

namespace Lui\Kocmo\BD\HB;

use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

class HardIB
{
    /**
     * @var \Bitrix\Main\Entity\DataManager
     */
    protected $ob;

    /**
     * HardIB constructor.
     * @param int $id
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\SystemException
     */
    public function __construct(int $id)
    {
        if (!\Bitrix\Main\Loader::includeModule('highloadblock')) {
            throw new  \Exception('No Module highloadblock');
        }
        $this->ob = $this->GetDataClass($id);
    }

    /**
     * @param int $id
     * @return \Bitrix\Main\Entity\DataManager
     * @throws \Bitrix\Main\SystemException
     */
    protected function GetDataClass(int $id)
    {
        $entity = HLBT::compileEntity(HLBT::getById($id)->fetch());
        return $entity->getDataClass();
    }

    /**
     * @return mixed
     */
    public function GetFields()
    {
        return $this->ob::getFields();
    }

    /**
     * @param array $arFilter
     * @param array $arOrder
     * @param array $arSelect
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    public function GetList($arFilter = [], $arOrder = ['UF_NAME' => 'ASC'], $arSelect = ['*']): array
    {

        $arItem = [];
        $rsData = $this->ob::getList([
            'order' => $arOrder,
            'select' => $arSelect,
            'filter' => $arFilter
        ]);
        while ($el = $rsData->fetch()) {
            $arItem[] = $el;
        }
        return $arItem;
    }

    /**
     * @return int
     * @throws \Bitrix\Main\SystemException
     */
    public function GetCount()
    {
        return $this->ob::getCount();
    }

    /**
     * @param $arData
     * @return \Bitrix\Main\Entity\AddResult
     * @throws \Exception
     */
    public function Add($arData)
    {
        return $this->ob::add($arData);
    }

    /**
     * @param $id
     * @return \Bitrix\Main\Entity\DeleteResult
     * @throws \Exception
     */
    public function Delete($id)
    {
        return $this->ob::delete($id);
    }

    /**
     * @param $id
     * @param $arField
     * @return \Bitrix\Main\Entity\UpdateResult
     * @throws \Exception
     */
    public function Update($id, $arField)
    {
        return $this->ob::update($id, $arField);
    }

    /**
     * @param string $xml
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    public function GetXml(string $xml)
    {
        return $this->GetList(['UF_XML_ID' => $xml]);
    }

    public function GetTableName()
    {
        return $this->ob::getTableName();
    }


    /**
     * @param array $arXml
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    public function GetXmlS(array $arXml)
    {
        return $this->GetList(['XML_ID' => $arXml]);
    }

    /**
     * @return \Bitrix\Main\DB\Result
     */
    public function Truncate()
    {
        $table = $this->GetTableName();
        $sql = "TRUNCATE TABLE {$table}";
        return \Bitrix\Main\Application::getConnection()->query($sql);
    }

}
