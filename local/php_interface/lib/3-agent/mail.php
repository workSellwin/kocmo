<?php

use Lui\Kocmo\Data\IblockElement;

//----------------------------------------------------------------------------------------------------------------------
function sendEmailSertifikat(){

    $data = new IblockElement();
    $arFilter = Array("IBLOCK_ID"=>12, "PROPERTY_DATE"=>date('d.m.Y'), 'PROPERTY_OPLACHEN_VALUE'=>'Да');
    $data = $data->GetData($arFilter);
    if(!empty($data)){
        foreach ($data as &$egift){
            if($egift['PROPERTY']['EMAIL_SENT'] != 'Да'){
                if($egift['PROPERTY']['SHTRIH_KOD']){
                    if($egift['PROPERTY']['EMAIL']){
                        SendEmailEGiftAgent($egift);
                    }
                }
            }
        }
    }
    return "sendEmailSertifikat();";
}

function SendEmailEGiftAgent($egift){
    $HtmlEmail = new EGift();
    $HtmlEmail = $HtmlEmail->GetHtmlEmailOb($egift);
    $arEventFields = [
        "HTML"   => $HtmlEmail,
        "EMAIL_TO"  => $egift['PROPERTY']['EMAIL'],
    ];
    $sent_email = CEvent::Send('E-GIFT', 's1', $arEventFields);
    if($sent_email){
        CIBlockElement::SetPropertyValuesEx($egift['ID'], false, ['EMAIL_SENT' => 1602]);
    }
}
//----------------------------------------------------------------------------------------------------------------------