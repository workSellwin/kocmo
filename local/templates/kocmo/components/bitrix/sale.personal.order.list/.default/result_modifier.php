<?php
if(\Bitrix\Main\Loader::includeModule('iblock') ) {

    $arResult['ITEMS'] = [];

    foreach ($arResult['ORDERS'] as $order) {
        foreach ($order['BASKET_ITEMS'] as $basketItem) {
            $arResult['ITEMS'][$basketItem['PRODUCT_ID']] = false;
        }
    }

    if(count($arResult['ITEMS'])) {

        $res = CIblockElement::GetList(
            [],
            ["IBLOCK" => 2, "ID" => array_keys($arResult['ITEMS'])],
            false,
            false,
            [
                "ID",
                "NAME",
                "PREVIEW_TEXT",
                "DETAIL_TEXT",
                "PREVIEW_PICTURE",
                "DETAIL_PICTURE",
                "PROPERTY_ARTIKUL",
            ]
        );

        while ($fields = $res->fetch()) {
            $arResult['ITEMS'][$fields["ID"]] = $fields;
        }
       // pr($arResult['ITEMS']);
    }
}


