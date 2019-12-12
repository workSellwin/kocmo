<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$HB = new highloadApi(3);
$Selectoptionprofile = $HB->getElementHighload(array(), array('UF_SORT' => 'ASC'));
$arSelectoptionprofile = array();
foreach ($Selectoptionprofile as $val) {
    //Возвраст
    if ($val['UF_TYPE'] == 1) {
        $arSelectoptionprofile['AGE'][$val['UF_XML_ID']] = $val;
    }
    //Цвет глаз
    if ($val['UF_TYPE'] == 2) {
        $arSelectoptionprofile['EYE_COLOR'][$val['UF_XML_ID']] = $val;
    }
    //Цвет волос
    if ($val['UF_TYPE'] == 3) {
        $arSelectoptionprofile['HAIR_COLOR'][$val['UF_XML_ID']] = $val;
    }
    //Тип кожи
    if ($val['UF_TYPE'] == 4) {
        $arSelectoptionprofile['SKIN_TYPE'][$val['UF_XML_ID']] = $val;
    }

}

$arResult['arSelectoptionprofile'] = $arSelectoptionprofile;