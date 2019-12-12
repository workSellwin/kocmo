<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);
unset($arResult['JS_DATA']);
$ob = new \Lui\Kocmo\Request\BasketRequest();
$arResult['BLOCK_PRICE'] = $ob->GetResult();

// Самовывоз
if ($STORE =& $arResult['DELIVERY']['3']['STORE']) {
    foreach ($STORE as &$id) {
        $id = $arResult['STORE_LIST'][$id];
    }
}
// Формируцем свойства для шаблона
$arFormProps = [];

foreach ($arResult['ORDER_PROP']['USER_PROPS_N'] as $prop) {
    $arFormProps[$prop['PROPS_GROUP_ID']][$prop['CODE']] = \Lui\Kocmo\Helper\Data::Min($prop);
}
foreach ($arResult['ORDER_PROP']['USER_PROPS_Y'] as $prop) {
    $arFormProps[$prop['PROPS_GROUP_ID']][$prop['CODE']] = \Lui\Kocmo\Helper\Data::Min($prop);
}
$arResult['BLOCK_PROPS'] = $arFormProps;
global $USER;

$obU = \Lui\Kocmo\Helper\UserData::getInstance();
$arUser = $obU->GetData();

$BLOCK_PROPS_1 =& $arResult['BLOCK_PROPS'][1];

if (!$val =& $BLOCK_PROPS_1['NAME']['VALUE']) {
    $val = $arUser['NAME'];
}
if (!$val =& $BLOCK_PROPS_1['SURNAME']['VALUE']) {
    $val = $arUser['LAST_NAME'];
}
if (!$val =& $BLOCK_PROPS_1['EMAIL']['VALUE']) {
    $val = $arUser['EMAIL'];
}

if (!$val =& $BLOCK_PROPS_1['PHONE']['VALUE']) {
    $val = $arUser['PERSONAL_PHONE'];
}


$BLOCK_PROPS_6 =& $arResult['BLOCK_PROPS'][6];
$BLOCK_PRICE =& $arResult['BLOCK_PRICE'];

// условие на курьерскую доставку

$priceDelivery = 0;
foreach ($arResult['DELIVERY'] as $delivery) {
    if ($delivery['CHECKED'] == 'Y') {
        $priceDelivery = $delivery['PRICE'];
    }
}

if ($priceDelivery > 0) {
    $BLOCK_PRICE['Delivery'] = $priceDelivery;
    $BLOCK_PRICE['Total'] += $priceDelivery;
}

if ($cert = $arResult['BLOCK_PROPS'][6]['GIFT_CERTIFICATE']['VALUE']) {
    $ob = new \Lui\Kocmo\Request\Get\Certificate($cert);
    $data = $ob->Send();
    if ($data['status'] == 'ok') {
        $certificate = $data['certificate'][0];
        $BLOCK_PROPS_6['GIFT_CERTIFICATE']['INFO'] = 'Баланс ' . $certificate['balance'] . ' BYN';

        if ($cert_copy = &$BLOCK_PROPS_6['COPY']['VALUE']) {
            if ($cert_copy < 0) {
                $cert_copy = $certificate['balance'];
            }
            if ($cert_copy > $certificate['balance']) {
                $cert_copy = $certificate['balance'];
            }
            if ($cert_copy > $BLOCK_PRICE['Total']) {
                $cert_copy = $BLOCK_PRICE['Total'];
            }
            $BLOCK_PROPS_6['GIFT_CERTIFICATE']['READONLY'] = 'Y';
            $BLOCK_PRICE['Certificate'] = $cert_copy;
            $BLOCK_PRICE['Total'] -= $cert_copy;
            if ($BLOCK_PRICE['Total'] < 0) {
                $BLOCK_PRICE['Total'] = 0;
                $BLOCK_PROPS_6['COPY']['VALUE'] = $cert_copy;
            }
        }
    } else {
        $arResult['BLOCK_PROPS'][6]['GIFT_CERTIFICATE']['ERROR'] = 'Недействительный сертификат';
        unset($arResult['BLOCK_PROPS'][6]['COPY']);
    }
} else {
    unset($arResult['BLOCK_PROPS'][6]['COPY']);
}
//$ob = new
if ($card = $obU->GetCard()) {
    $BLOCK_PROPS_6['CARD_COSMO']['VALUE'] = $card;
}


