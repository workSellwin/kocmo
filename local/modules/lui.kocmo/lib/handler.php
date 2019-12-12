<?php


namespace Lui\Kocmo;


class Handler
{
    public function OnBeforeUserRegister(&$arFields)
    {
//        global $APPLICATION;
//        $APPLICATION->throwException("Пользователь с именем входа dfg не может быть 4551." . implode('<br>', $arFields));
//        return false;
    }

    //Событие "OnAfterUserRegister" вызывается после попытки регистрации нового пользователя методом CUser::Register.
   public function OnAfterUserRegister(&$arFields)
    {

       /* if($login=$arFields['LOGIN']){
            $rsUser = \CUser::GetByLogin($login);
            if($arUser = $rsUser->Fetch()){

            }
        }
        // создаем обработчик события "OnAfterUserRegister"
       /* global $USER;
        if ($rsUser = $USER->GetByLogin($arFields['LOGIN'])) {
            //PR($rsUser, true);
            AddMessage2Log($rsUser);
        }*/
        //PR($arFields, true);die();

    }

    // вызывается до изменения элемента информационного блока
    public function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {

        if ($arFields['IBLOCK_ID'] == 6 && $arFields['ID']) {

            \CModule::IncludeModule('iblock');
            $arSelect = Array("ID", "NAME", "IBLOCK_ID", 'ACTIVE', 'PROPERTY_EMAIL_TO_VALUE', 'PROPERTY_USER_ID', 'PROPERTY_PROD_ID', 'PROPERTY_RATING');
            $arFilter = Array("IBLOCK_ID" => $arFields['IBLOCK_ID'], "ID" => $arFields['ID']);
            $res = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            while ($ob = $res->GetNext()) {
                if ($ob['ACTIVE'] == 'N' && $arFields['ACTIVE'] == 'Y') {
                    $znak = '+';
                    //обновляем свойства товара в каталоге
                    if ($ob['PROPERTY_PROD_ID_VALUE']) {
                        $arResult = self::setPropRating($arFields, $ob, $znak);
                    }

                    //отправка email пользователю
                    if ($ob['PROPERTY_EMAIL_TO_VALUE_VALUE'] != 'Y') {
                        if ($ob['PROPERTY_USER_ID_VALUE']) {
                            $rsUser = \CUser::GetByID($ob['PROPERTY_USER_ID_VALUE']);
                            $arUser = $rsUser->Fetch();
                            if ($arUser['EMAIL']) {
                                $url = $_SERVER['HTTP_ORIGIN'] . $arResult['DETAIL_PAGE_URL'];
                                $text = '<P>Здравствуйте, ' . $arUser['NAME'] . '! Ваш отзыв, размещенный о товаре ' . $arResult['NAME'] . ', был проверен модератором и доступен по <a target="_blank" href="' . $url . '">ссылке</a></P>';
                                //$text = htmlspecialchars($text);
                                $arEventFields = array(
                                    "TEXT_MESSAGE" => $text,
                                    "SECTION_EMAIL_TO" => $arUser['EMAIL'],
                                    "SITE_NAME" => 'Kocmo.by',
                                    'CATEGORY' => '',
                                    "EMAIL_FROM" => 'Kocmo'
                                );
                                $e = \CEvent::SendImmediate("ALX_FEEDBACK_FORM", 's1', $arEventFields);
                                //переопределяем свойство Email отправлен пользователю
                                if ($e) {
                                    $arFields['PROPERTY_VALUES'][85][0]['VALUE'] = 255;
                                }
                            }
                        }
                    }
                }
                if ($ob['ACTIVE'] == 'Y' && $arFields['ACTIVE'] == 'N') {
                    //обновляем свойства товара в каталоге
                    $znak = '-';
                    if ($ob['PROPERTY_PROD_ID_VALUE']) {
                        self::setPropRating($arFields, $ob, $znak);
                    }
                }
            }
            return true;
        }
    }


    public static function setPropRating(&$arFields, $ob, $znak)
    {
        if ($ob['PROPERTY_PROD_ID_VALUE']) {
            \CModule::IncludeModule('iblock');
            $arResult = array();
            $arSelect = Array("ID", "NAME", "IBLOCK_ID", 'ACTIVE', 'DETAIL_PAGE_URL');
            $arFilter = Array("IBLOCK_ID" => 2, "ID" => $ob['PROPERTY_PROD_ID_VALUE']);
            $ress = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            while ($obCatalog = $ress->GetNext()) {
                $arResult['ID'] = $obCatalog['ID'];
                $arResult['NAME'] = $obCatalog['NAME'];
                $arResult['DETAIL_PAGE_URL'] = $obCatalog['DETAIL_PAGE_URL'];
                if ($obCatalog['ID'] == $ob['PROPERTY_PROD_ID_VALUE']) {

                    $arSelect = Array("ID", "NAME", "IBLOCK_ID", 'ACTIVE', 'PROPERTY_RATING');
                    $arFilter = Array("IBLOCK_ID" => $arFields['IBLOCK_ID'], 'ACTIVE' => 'Y', "PROPERTY_PROD_ID" => $ob['PROPERTY_PROD_ID_VALUE']);
                    $res = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                    $count_reviews = 0;
                    $sredn_rating = 0;
                    $count_sredn_rating = 0;

                    while ($rg = $res->GetNext()) {
                        if ($rg['ACTIVE'] == 'Y') {
                            $count_reviews++;
                            if ($rg['PROPERTY_RATING_VALUE']) {
                                $count_sredn_rating++;
                                $sredn_rating += $rg['PROPERTY_RATING_VALUE'];
                            }
                        }
                    }
                    if ($znak == '+') {
                        if ($ob['PROPERTY_RATING_VALUE']) {
                            $count_sredn_rating++;
                            $sredn_rating += $ob['PROPERTY_RATING_VALUE'];
                        }
                        $count_reviews++;
                    }
                    if ($znak == '-') {
                        if ($ob['PROPERTY_RATING_VALUE']) {
                            $count_sredn_rating--;
                            $sredn_rating -= $ob['PROPERTY_RATING_VALUE'];
                        }
                        $count_reviews--;
                    }

                    if ($count_sredn_rating > 0) {
                        $sum = $sredn_rating / $count_sredn_rating;
                    } else {
                        $sum = 0;
                    }

                    // Установим новое значение для данного свойства данного элемента
                    \CIBlockElement::SetPropertyValuesEx($obCatalog['ID'], false, array('AVERAGE_RATING' => $sum, 'COUNT_REVIEWS' => $count_reviews));

                }
            }

            return $arResult;
        }
    }

    public function OnBeforeEventSend($arFields, $arTemplate){
        if($arTemplate['EVENT_NAME'] == 'NEW_USER' && $arTemplate['ID'] == 1){
            \CEvent::Send("USER_INFO", 's1', $arFields);
        }
    }


}
