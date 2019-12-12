<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arFilter = Array(
    "IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y",
);
$arSelectFields = Array(
    "ID", 'IBLOCK_ID', 'NAME', 'CODE', 'DETAIL_PAGE_URL', 'DATE_ACTIVE_FROM'
);
$arOrder = Array(
    'date_active_from' => "ASC",
    'id'=> 'ID',
);
$res = CIBlockElement::GetList(
    $arOrder, $arFilter, false, array("nPageSize" => "1","nElementID" => $arResult["ID"]), $arSelectFields
);
while($ar_fields = $res->GetNext()){
    if($ar_fields['ID'] != $arResult['ID'] && !$arResult['navElement']['THIS']){
        $arResult['navElement']['PREV'] = $ar_fields;
    }
    if($ar_fields['ID'] == $arResult['ID'] ){
        $arResult['navElement']['THIS'] = $ar_fields;
    }
    if($ar_fields['ID'] != $arResult['ID'] && $arResult['navElement']['THIS']){
        $arResult['navElement']['NEXT'] = $ar_fields;
    }
}