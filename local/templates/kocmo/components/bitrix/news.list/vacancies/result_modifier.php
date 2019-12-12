<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


$arFilter = Array('IBLOCK_ID'=>10, 'GLOBAL_ACTIVE'=>'Y');
$db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, true);
$arSection = [];
while($ar_result = $db_list->GetNext())
{
    $arSection[$ar_result['ID']]['ID'] =  $ar_result['ID'];
    $arSection[$ar_result['ID']]['NAME'] =  $ar_result['NAME'];
}

$city_id = [];
foreach ($arResult['ITEMS'] as $arItems){
    if($arItems['PROPERTIES']['CITY']['VALUE']){
        foreach ($arItems['PROPERTIES']['CITY']['VALUE'] as $city){
            $arResult['CITY'][$city]['ID'] = $city;
            $city_name = CIBlockElement::GetByID($city);
            if($ar_res = $city_name->GetNext())$arResult['CITY'][$city]['NAME'] = $ar_res['NAME'];

            $arResult['CITY'][$city]['SECTION'][$arItems['IBLOCK_SECTION_ID']] = $arItems['IBLOCK_SECTION_ID'];
            $arResult['CITY'][$city]['ITEMS'][$arItems['ID']] = $arItems['ID'];
        }
    }


    if($arSection[$arItems['IBLOCK_SECTION_ID']]){
        $arSection[$arItems['IBLOCK_SECTION_ID']]['ITEMS'][$arItems['ID']] = $arItems;
    }
}

unset($arResult['ITEMS']);
$arResult['SECTION_LIST'] = $arSection;
