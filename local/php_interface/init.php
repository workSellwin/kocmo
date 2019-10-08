<?php
use Bitrix\Main\Loader;
Loader::includeModule("lui.kocmo");
include_once __DIR__ . '/lib.php';

AddEventHandler("main", "OnEndBufferContent", "changeUrl");
function changeUrl(&$content)
{
//    $content=str_replace('/upload/iblock/', "http://host060220193.of.by/upload/iblock/", $content);
//    $content=str_replace('/upload/sale/', "http://host060220193.of.by/upload/sale/", $content);
}
