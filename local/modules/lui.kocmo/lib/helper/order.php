<?php


namespace Lui\Kocmo\Helper;


use Lui\Kocmo\Helper\EmailBasket;
use Lui\Kocmo\Request\Basket;

class Order
{
    public function __construct()
    {
        Bitrix\Main\Loader::includeModule("sale");
        Bitrix\Main\Loader::includeModule("catalog");
    }

    public static function GetDeliveryName(int $id): string
    {
        $res = \Bitrix\Sale\Delivery\Services\Table::getList(array('filter' => array('ID' => $id)));
        $name = '';
        if ($dev = $res->Fetch()) {
            $name = $dev['NAME'];
        }
        return $name;
    }

    public static function GePaymentName(int $id): string
    {
        $res = \Bitrix\Sale\PaySystem\Manager::getList(array('filter' => array('ID' => $id)));
        $name = '';
        if ($dev = $res->Fetch()) {
            $name = $dev['NAME'];
        }
        return $name;
    }

    public static function GetJson(int $id): string
    {
        $sJson = '';
        $arJson = DataOrderJson::Order();
        $arJson['OrderId'] = $id;

        $order = \Bitrix\Sale\Order::load($id);
        if (is_object($order)) {
            $basket = $order->getBasket();
            $propertyCollection = $order->getPropertyCollection();
            $ar = $propertyCollection->getArray();
            $arOrderProp = [];
            foreach ($ar['properties'] as $p) {
                $arOrderProp[$p['CODE']] = [
                    'name' => $p['NAME'],
                    'type' => $p['TYPE'],
                    'value' => $p['VALUE'],
                ];
            }

            $deliveryIds = $order->getDeliverySystemId(); // массив id способов доставки
            $arDelivery = \Bitrix\Sale\Delivery\Services\Manager::getById(reset($deliveryIds));

            if ($arDelivery) {
                $arJson['delivery']['name'] = $arDelivery['NAME'];
                $arJson['delivery']['uid'] = $arDelivery['XML_ID'];
                $arJson['delivery']['price'] = $order->getDeliveryPrice();

                if ($arOrderProp['TIME_OF_DELIVERY']['value']) {
                    $arJson['delivery']['interval'] = reset($arOrderProp['TIME_OF_DELIVERY']['value']);;
                }

                if (true) {
                    $arParamsDelivery = [
                        'date_time' => date('d.m.Y h:i:s'),
                        'adress' => "Независимости 6"
                    ];
                    $arJson['delivery']['params'] = $arParamsDelivery;
                }
            }


            $paymentIds = $order->getPaymentSystemId(); // массив id способов оплат
            $arPaySys = \CSalePaySystem::GetByID(reset($paymentIds));

            if ($arPaySys) {
                $arJson['payment'] = [
                    'name' => $arPaySys['NAME'],
                    'uid' => $arPaySys['XML_ID'],
                    'certificate' => [],
                ];

                if ($GIFT_CERTIFICATE = $arOrderProp['GIFT_CERTIFICATE']['value']) {
                    if ($value = $arOrderProp['COPY']['value']) {
                        $value = reset($value);
                        $GIFT_CERTIFICATE = reset($GIFT_CERTIFICATE);
                        $ob = new \Lui\Kocmo\Request\Get\Certificate($GIFT_CERTIFICATE);
                        $data = $ob->Send();
                        if ($data['status'] == 'ok') {
                            $data = $data['certificate'][0];
                            $arJson['payment']['certificate'] = [[
                                'uid' => $data['uid'],
                                'number' => $GIFT_CERTIFICATE,
                                'value' => $value,
                                'face_value' => $data['face_value'],
                                'balance' => $data['balance'],
                            ]];
                        }

                    }
                }


            }

            if ($arOrderProp) {
                //$arJson['personal'] = [];
                if ($arOrderProp['NAME']['value']) {
                    $arJson['personal'] ['name'] = reset($arOrderProp['NAME']['value']);
                }
                if ($arOrderProp['SURNAME']['value']) {
                    $arJson['personal'] ['lastname'] = reset($arOrderProp['SURNAME']['value']);
                }
                if ($arOrderProp['EMAIL']['value']) {
                    $arJson['personal'] ['email'] = reset($arOrderProp['EMAIL']['value']);
                }
                if ($arOrderProp['PHONE']['value']) {
                    $arJson['personal'] ['phone'] = reset($arOrderProp['PHONE']['value']);
                }
                // Адресс
                if ($arOrderProp['STREET']['value']) {
                    $arJson['personal']['adress']['street'] = reset($arOrderProp['STREET']['value']);
                }
                if ($arOrderProp['HOUSE']['value']) {
                    $arJson['personal']['adress']['house'] = reset($arOrderProp['HOUSE']['value']);
                }
                if ($arOrderProp['CORPS']['value']) {
                    $arJson['personal']['adress']['corps'] = reset($arOrderProp['CORPS']['value']);
                }
                if ($arOrderProp['APARTMENT']['value']) {
                    $arJson['personal']['adress']['flat'] = reset($arOrderProp['APARTMENT']['value']);
                }


            }

            if ($arOrderProp['CARD_COSMO']['value']) {
                $arJson['card'] = reset($arOrderProp['CARD_COSMO']['value']);;
            }

            if ($arOrderProp['PROMO_CODE']['value']) {
                $arJson['promo'] = reset($arOrderProp['PROMO_CODE']['value']);;
            }


            $basketItems = $basket->getBasketItems();
            $arJson['goods'] = [];
            $arJson['GIFT'] = [];

            $arGoods = Basket::Get1CRequest();
            $arGoods = array_column($arGoods['goods'], null, 'UID');

            foreach ($basketItems as $item) {
                $basketPropertyCollection = $item->getPropertyCollection();
                $arProperty = $basketPropertyCollection->getPropertyValues();
                $arProperty = array_values($arProperty);
                $arProperty = array_column($arProperty, 'VALUE', 'CODE');
                $uid = $arProperty['PRODUCT.XML_ID'];
                if ($goods = $arGoods[$uid]) {
                    $arJson['goods'][] = $goods;
                } else {
                    $arJson['goods'][] = [
                        'UID' => $arProperty['PRODUCT.XML_ID'] ?? $item->getField('PRODUCT_XML_ID'),
                        'COUNT' => $item->getQuantity(),
                        'PRICE' => round($item->getPrice(), 2),
                        'SUMM' => round($item->getFinalPrice(), 2),
                        'DISCOUNT' => [],
                    ];
                }

            }
        }
        $sJson = json_encode($arJson);
        return $sJson;
    }

    public static function GetProperty(\Bitrix\Sale\Order $order, $array = false)
    {
        $propertyCollection = $order->getPropertyCollection();
        $ar = $propertyCollection->getArray();
        $arProp = array_column($ar['properties'], 'VALUE', 'CODE');
        if (!$array) {
            foreach ($arProp as $code => &$prop) {
                $prop = $prop[0];
            }

        }
        return $arProp;
    }


    public static function GetPayNameOrder(\Bitrix\Sale\Order $order)
    {
        $PAY_SYSTEM_ID = $order->GetField('PAY_SYSTEM_ID');
        return self::GePaymentName($PAY_SYSTEM_ID);
    }

    public static function GetDeliveryNameOrder(\Bitrix\Sale\Order $order)
    {
        $DELIVERY_ID = $order->GetField('DELIVERY_ID');
        return self::GetDeliveryName($DELIVERY_ID);
    }

    public static function GetOrderListEmail(\Bitrix\Sale\Order $order)
    {
        $basket = $order->getBasket();
        return EmailBasket::GetHtml($basket);
    }
}

/*
 *
 *




 *
 */
