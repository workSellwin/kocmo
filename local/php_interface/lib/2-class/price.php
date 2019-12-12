<?php


class Price
{

    /**
     * Выборка всех типов цен
     * @throws \Bitrix\Main\ArgumentException
     */
    static function getListTypePrice()
    {
        $ListTypePrice = [];
        $rsGroup = \Bitrix\Catalog\GroupTable::getList();
        while ($arGroup = $rsGroup->fetch()) {
            $ListTypePrice[$arGroup['NAME']] = $arGroup;
        }
        return $ListTypePrice;
    }

    /**
     * Выбираем ID групп пользователей, которым доступна цена с XML_ID
     * @param string $XML_ID
     * @throws \Bitrix\Main\ArgumentException
     */
    static function getListGroupID($XML_ID = 'ROZNICHNAYA')
    {
        $rsGroup = \Bitrix\Catalog\GroupAccessTable::getList(array(
            'filter' => array('ACCESS' => 'Y', 'CATALOG_GROUP.XML_ID' => $XML_ID),
            'select' => array('GROUP_ID')
        ));
        $Group = [];
        while ($arGroup = $rsGroup->fetch()) {
            $Group[] = $arGroup;
        }
        return $Group;
    }

    /**
     * @param $PRODUCT_ID
     * @param string $XML_ID
     * @throws \Bitrix\Main\ArgumentException
     */
    static function getPriceProduct($PRODUCT_ID, $XML_ID = 'ROZNICHNAYA')
    {
        $rsPrice = \Bitrix\Catalog\PriceTable::getList(array(
            'filter' => array('CATALOG_GROUP.XML_ID' => $XML_ID, 'PRODUCT_ID' => $PRODUCT_ID)
        ));
        while ($arPrice = $rsPrice->fetch()) {
            return $arPrice;
        }
    }

    /**
     * @param $catalogGroupId   - Код (ID) типа цен.
     * @param $price            - Величина цены.
     * @param $productId        - Код товара или торгового предложения (ID элемента инфоблока).
     * @param string $currency  - Код валюты цены.
     * @return array|bool
     * @throws \Bitrix\Main\ArgumentException
     */
    static function savePrice($catalogGroupId, $price, $productId, $currency = 'BYN')
    {
        if (!$catalogGroupId || !$productId || !$currency)

            return array("Неверно заданы параметры");

        $rsP = \Bitrix\Catalog\PriceTable::getList(array(
            'filter' => array('CATALOG_GROUP_ID' => $catalogGroupId, 'PRODUCT_ID' => $productId),
        ));

        if ($arP = $rsP->fetch()) {
            if ($price) {
                $result = \Bitrix\Catalog\PriceTable::update($arP['ID'], array(
                    'PRICE' => $price,
                    'PRICE_SCALE' => $price,
                    'CURRENCY' => $currency,
                ));
            } else {
                $result = \Bitrix\Catalog\PriceTable::delete($arP['ID']);
            }
        } else {
            $result = \Bitrix\Catalog\PriceTable::add(array(
                'CATALOG_GROUP_ID' => $catalogGroupId,
                'PRODUCT_ID' => $productId,
                'PRICE' => $price,
                'PRICE_SCALE' => $price,
                'CURRENCY' => $currency,
            ));
        }

        if ($result->isSuccess()) {
            return true;
        } else {
            return $result->getErrorMessages();
        }

    }
}