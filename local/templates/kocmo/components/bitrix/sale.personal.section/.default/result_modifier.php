<?php
use Bitrix\Highloadblock as HL;

$id = $USER->GetId();
$rsUser = CUser::GetByID($id);
$arUser = $rsUser->Fetch();
$arResult['UF_NEWS_SUBSCRIBE'] = $arUser['UF_NEWS_SUBSCRIBE'];
$arResult['AR_USER'] = $arUser;

$arAges = [];
$arEyes = [];
$arSkin = [];
$arHair = [];

if( \Bitrix\Main\Loader::includeModule('highloadblock') ){

    $hlblock = HL\HighloadBlockTable::getById(3)->fetch();
    $entity = HL\HighloadBlockTable::compileEntity( $hlblock );
    $entityClass = $entity->getDataClass();
    $rows = $entityClass::getList([])->fetchAll();

    foreach( $rows as $row ){

        switch($row['UF_TYPE']){
            case 1:
                $arAges[] = $row;
                break;
            case 2:
                $arEyes[] = $row;
                break;
            case 3:
                $arSkin[] = $row;
                break;
            case 4:
                $arHair[] = $row;
                break;
        }
    }

    $arResult['HL_AGES'] = $arAges;
    $arResult['HL_EYES'] = $arEyes;
    $arResult['HL_SKINS'] = $arSkin;
    $arResult['HL_HAIRS'] = $arHair;
}

if( mb_strlen($arUser['PERSONAL_STREET']) > 10 && strpos($arUser['PERSONAL_STREET'], '%%') === 0 ) {

    $adress = substr($arUser['PERSONAL_STREET'], 2);

    preg_match_all("#(([\s\S]{0,})%;%)#uU", $adress, $matches);

    if(count($matches) && count($matches[2]) == 4) {
        list($arResult['USER_STREET'], $arResult['USER_HOUSE'], $arResult['USER_BUILDING'], $arResult['USER_APARTMENT']) = $matches[2];
    }
}

if( isset($_POST) && isset($_POST['submit']) && count($_POST)){

    $userFields = [];
    $matches = [
        'user-name' => 'NAME',
        'user-lastname' => 'LAST_NAME',
        'user-email' => 'EMAIL',
        'user-phone' => 'PERSONAL_PHONE',
        'user-city' => 'PERSONAL_CITY',
        'user-street' => 'PERSONAL_STREET_1',
        'user-house' => 'PERSONAL_HOUSE',
        'user-building' => 'PERSONAL_BUILDING',
        'user-apartment' => 'PERSONAL_APARTMENT',
        'user-news-subscribe' => 'UF_NEWS_SUBSCRIBE',
        'user-age' => 'UF_AGE',
        'user-eye' => 'UF_EYE_COLOR',
        'user-skin' => 'UF_SKIN',
        'user-hair' => 'UF_HAIR',
//        'user-old-pass' => 'NAME',
//        'user-new-pass' => 'NAME',
//        'user-confirm-pass' => 'NAME',
    ];

    foreach($_POST as $key => $value){

        if( !empty($value) && isset($matches[$key]) ){

            if($value == 'default'){
                $value = "";
            }
            $userFields[$matches[$key]] = $value;
        }
    }
    if(!empty($userFields['PERSONAL_PHONE'])) {
        $userFields['PERSONAL_PHONE'] = getPreparePhone($userFields['PERSONAL_PHONE']);
    }

    $street = $userFields['PERSONAL_STREET_1'] ? $userFields['PERSONAL_STREET_1'] : "";
    $house = $userFields['PERSONAL_HOUSE'] ? $userFields['PERSONAL_HOUSE'] : "";
    $building = $userFields['PERSONAL_BUILDING'] ? $userFields['PERSONAL_BUILDING'] : "";
    $apartment = $userFields['PERSONAL_APARTMENT'] ? $userFields['PERSONAL_APARTMENT'] : "";

    $userFields['PERSONAL_STREET'] = '%%' . $street . '%;%' . $house . '%;%' . $building . '%;%' . $apartment . '%;%';


    if( strlen($_POST['user-new-pass']) >= 6 && $_POST['user-new-pass'] === $_POST['user-confirm-pass']){

        $authorizedFlag = $USER->Login($arUser['LOGIN'], $_POST['user-old-pass'], "N", "Y");

        if( $authorizedFlag == true ){

            $userFields["PASSWORD"] = $_POST['user-new-pass'];
            $userFields["CONFIRM_PASSWORD"] = $_POST['user-confirm-pass'];
        }
    }


    unset($key, $value);
    $user = new CUser;
    $user->Update($arUser['ID'], $userFields);

    header("Location: " . $APPLICATION->GetCurPage() );
    exit();
}
if( strpos($arResult['AR_USER']['PERSONAL_PHONE'], '+' !== 0) ) {
    $arResult['AR_USER']['PERSONAL_PHONE'] = '+' . $arResult['AR_USER']['PERSONAL_PHONE'];//чтобы не конфликтовало с placeholder'ом
}
