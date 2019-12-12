<?php

$UID_SUM = [];
foreach($arResult['PROPERTIES']['SUM_SERTIFIKATA']['VALUE'] as $k => $v){
    $UID_SUM[$v]=$arResult['PROPERTIES']['SUM_SERTIFIKATA']['VALUE_XML_ID'][$k];
}
$arResult['UID_SUM']=$UID_SUM;
$arResult['PROPERTIES']=array_column($arResult['PROPERTIES'],'~VALUE','CODE');
