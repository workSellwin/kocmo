<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$res = CIBlockElement::GetList([], ["IBLOCK_ID" => 2], false, false, [
    'ID', 'DETAIL_PAGE_URL','NAME','PREVIEW_TEXT','DETAIL_TEXT','PREVIEW_PICTURE','DETAIL_PICTURE'//,''
]);

if($fields = $res->GetNext()){
    pr($fields, 14);
}