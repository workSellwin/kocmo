<?php

namespace Lui\Kocmo\Handler;

use Bitrix\Sale\Order;

class SaleHandler extends BaseHandler
{
    public $module = 'sale';

    /**
     * ENTITY order  Объект заказа.
     * IS_NEW boolean Принимает одно из двух значений: true - если заказ новый, false - если нет.
     * VALUES array Старые значения полей заказа.
     *
     * @param \Bitrix\Main\Event $event
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function OnSaleOrderSaved(\Bitrix\Main\Event $event)
    {
        /** @var Order $order */
        $order = $event->getParameter("ENTITY");
        $oldValues = $event->getParameter("VALUES");
        $isNew = $event->getParameter("IS_NEW");

        if ($isNew) {
            $id = $order->getId();
            if ($id) {
                $ob = new \Lui\Kocmo\Request\Order($id);
                $data = $ob->Run();
                $uid = $data['UID'];
                $order->setField('XML_ID', $uid);
                $propertyCollection = $order->getPropertyCollection();
                $propertyValue = \Bitrix\Sale\PropertyValue::create($propertyCollection, [
                    'ID' => 20,
                    'NAME' => 'ID 1C',
                    'TYPE' => 'STRING',
                    'CODE' => 'UID',
                ]);
                $propertyValue->setField('VALUE', $uid);
                $propertyCollection->addItem($propertyValue);
                $order->save();
            }
        }
    }

    /**
     * @param $ID
     * @param $eventName
     * @param $arFields
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\NotImplementedException
     */
    function OnOrderNewSendEmail($ID, &$eventName, &$arFields)
    {
        $arFieldsNew = [];
        $order = \Bitrix\Sale\Order::load($ID);
        $prop = $ar = \Lui\Kocmo\Helper\Order::GetProperty($order);
        $arFieldsNew['DELIVERY_NAME'] = \Lui\Kocmo\Helper\Order::GetPayNameOrder($order);
        $arFieldsNew['DELIVERY_DATE'] = $prop['DATE_OF_DELIVERY'];
        $arFieldsNew['ORDER_SUM'] = $order->getPrice();
        $arFieldsNew['PAY_NAME']=\Lui\Kocmo\Helper\Order::GetDeliveryNameOrder($order);
        $arFieldsNew['ORDER_LIST']=\Lui\Kocmo\Helper\Order::GetOrderListEmail($order);
        $arFields = array_merge($arFields, $arFieldsNew);
    }
}
