<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


$arSelect = Array("ID", "NAME", "IBLOCK_ID", 'PROPERTY_LAT', 'PROPERTY_LNG', 'PROPERTY_ZOOM');
$arFilter = Array("IBLOCK_ID"=>9, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNext())
{
    $arResult['CITY'][$ob['ID']] = $ob;
}


foreach ($arResult['ITEMS'] as $val) {
    $PROP = array_column($val['PROPERTIES'], 'VALUE', 'CODE');
    $arResult['DATA'][$val['ID']] = $PROP;
    $arResult['DATA'][$val['ID']]['CITY'] = $arResult['CITY'][$PROP['CITY']]['NAME'];
    $arResult['DATA'][$val['ID']]['NAME'] = $val['NAME'];
    $arResult['DATA'][$val['ID']]['CITY_ID'] = $PROP['CITY'];
    $arResult['DATA'][$val['ID']]['PREVIEW_PICTURE'] = $val['PREVIEW_PICTURE']['SRC'];

    $arResult['THIS_CITY'][$PROP['CITY']]=$PROP['CITY'];

    $arResult['DATA_OBJ'][] = array(
        'position' => array($PROP['LAN'], $PROP['LNG']),
        'zoom' => 16,
        'name' => $val['NAME'],
        'schedule' => $PROP['WORK_HOUR']
    );


}


//PR($arResult['THIS_CITY']);