<?php

namespace Lui\Kocmo\Request\Set;

use Bitrix\Main\Loader;
use Lui\Kocmo\BD\HB\ActionItems;
use Lui\Kocmo\BD\HB\ActionsList;
use Lui\Kocmo\Data\IblockElement;

class Actions
{
    /**
     * @var array
     */
    protected $arData = [];
    protected $arUnionActionsList = [];
    protected $arUnionProduct = [];
    /**
     * @var ActionsList
     */
    protected $obLists;
    /**
     * @var ActionItems
     */
    protected $obItems;

    public function __construct()
    {
        Loader::includeModule('kocmo.exchange');
        $this->obLists = new ActionsList();
        $this->obItems = new ActionItems();
    }

    /**
     * @throws \Exception
     */
    public function Update()
    {
        /* TODO */
        // 1 Получение данных от 1с
        if ($this->arData = $this->GetData1C()) {
            // 2 Отчистка HB
            $this->TruncateAll();
            // 3 Запись Списка Акций
            try {
                $this->SetActionsList();
            } catch (\Exception  $e) {
                AddMessage2Log($e->getMessage());
            }
            // 5 связи для товаров
            $this->SetUnionProduct();
            // 4 Запись Связей Акция ITEM
            $this->SetActionItems();
        }

    }

    /**
     * Отчистка HB
     */
    protected function TruncateAll()
    {
        $this->obLists->Truncate();
        $this->obItems->Truncate();
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function GetData1C()
    {
        $ob = new \Lui\Kocmo\Request\Get\Actions();
        return $ob->Send();
    }

    /**
     * @throws \Exception
     */
    protected function SetActionsList()
    {
        foreach ($this->GetActionsList() as $arEl) {
            $ob = $this->obLists->Add($arEl);
            $this->arUnionActionsList[$arEl['UF_XML_ID']] = $ob->getId();
        }
    }

    /**
     *
     *
     *
     * @return array
     */
    protected function GetActionsList()
    {
        $arData = [];
        $sort = 100;
        foreach ($this->arData as $dt) {
            if ($dt['allitems'] == 'N' and $dt['goods']) {
                $arData[] = [
                    'UF_XML_ID' => $dt['UID'],
                    'UF_NAME' => $dt['name'],
                    'UF_SORT' => $sort,
                ];
                $sort += 20;
            }
        }
        return $arData;
    }


    /**
     * @throws \Exception
     */
    protected function SetActionItems()
    {
        foreach ($this->GetActionsItems() as $arEl) {
            $this->obItems->Add($arEl);
        }
    }

    /**
     * @return array
     */
    protected function GetActionsItems()
    {
        $arData = [];
        $sort = 100;
        foreach ($this->arData as $dt) {
            if ($dt['allitems'] == 'N' and $dt['goods']) {
                $idActions = $this->arUnionActionsList[$dt['UID']];
                foreach ($dt['goods'] as $uid) {
                    $link = $this->arUnionProduct[$uid];
                    $arData[] = [
                        'UF_XML_ID' => $uid,
                        'UF_NAME' => $idActions . ' - ' . $uid,
                        'UF_SORT' => $sort,
                        'UF_ACTIONS' => $idActions,
                        'UF_LINK' => $link ? $link : $uid,
                        'UF_DESCRIPTION' => $dt['name'],
                    ];
                    $sort += 20;
                }
            }
        }
        return $arData;
    }


    protected function SetUnionProduct()
    {
        $arXmlID = [];
        if ($arData = $this->arData) {
            foreach ($arData as $tr) {
                foreach ($tr['goods'] as $dt) {
                    $arXmlID[] = $dt;
                }
            }
            if ($arXmlID) {
                $ob = new IblockElement();
                $arElements = $ob->GetXmlIDs($arXmlID);
                foreach ($arElements as $el) {
                    $this->arUnionProduct[$el['XML_ID']] = $el['ID'];
                }
            }
        }
    }

}
