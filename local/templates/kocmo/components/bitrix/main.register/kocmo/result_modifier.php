<?php

$arResult['ITEMS'][] = [
    'type' => 'hidden',
    'code' => 'LOGIN',
    'name' => '',
    'value' => $arResult["VALUES"]['LOGIN'],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];

$arResult['ITEMS'][] = [
    'type' => 'text',
    'code' => 'NAME',
    'name' => 'Введите имя',
    'value' => $arResult["VALUES"]['NAME'],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];
$arResult['ITEMS'][] = [
    'type' => 'text',
    'code' => 'LAST_NAME',
    'name' => 'Введите вашу фамилию',
    'value' => $arResult["VALUES"]['LAST_NAME'],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];
$arResult['ITEMS'][] = [
    'type' => 'text',
    'code' => 'PHONE_NUMBER',
    'name' => 'Введите ваш телефон',
    'value' => $arResult["VALUES"]['PHONE_NUMBER'],
    'required' => 'Y',
    'class' => 'phone',
    'onfocus' => '',
];
$arResult['ITEMS'][] = [
    'type' => 'hidden',
    'code' => 'PERSONAL_PHONE',
    'name' => '',
    'value' => $arResult["VALUES"]['PERSONAL_PHONE'],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];

$arResult['ITEMS'][] = [
    'type' => 'email',
    'code' => 'EMAIL',
    'name' => 'Введите email',
    'value' => $arResult["VALUES"]['EMAIL'],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];
$arResult['ITEMS'][] = [
    'type' => 'password',
    'code' => 'PASSWORD',
    'name' => 'Придумайте пароль',
    'value' => $arResult["VALUES"]['PASSWORD'],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];
$arResult['ITEMS'][] = [
    'type' => 'password',
    'code' => 'CONFIRM_PASSWORD',
    'name' => 'Повторите пароль',
    'value' => $arResult["VALUES"]['CONFIRM_PASSWORD'],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];
$arResult['ITEMS'][] = [
    'type' => 'date',
    'code' => 'PERSONAL_BIRTHDAY',
    'name' => 'Дата рождения',
    'value' => $arResult["VALUES"]['PERSONAL_BIRTHDAY'],
    'required' => '',
    'class' => '',
    'onfocus' => '',
];

$arResult['ITEMS'][] = [
    'type' => 'radio',
    'code' => 'PERSONAL_GENDER',
    'name' => 'Выберите ваш пол',
    'value' => $arResult["VALUES"]['PERSONAL_GENDER'],
    'options' => [
        ['name' => 'Женский', 'code' => 'F', 'checked' => $arResult["VALUES"]['PERSONAL_GENDER'] == 'F' ? 'checked' : ''],
        ['name' => 'Мужской', 'code' => 'M', 'checked' => $arResult["VALUES"]['PERSONAL_GENDER'] == 'M' ? 'checked' : ''],
    ],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];
if (count($arResult["ERRORS"]) > 0) {
    foreach ($arResult["ERRORS"] as $key => $error) {
        if (intval($key) == 0 && $key !== 0) {
            $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;" . GetMessage("REGISTER_FIELD_" . $key) . "&quot;",
                $error);
        }
    }
}
