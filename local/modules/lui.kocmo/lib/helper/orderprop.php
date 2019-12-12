<?php


namespace Lui\Kocmo\Helper;


class OrderProp
{
    /**
     * @param \Bitrix\Sale\Order $order
     * @param $code
     * @param $value
     * @return \Bitrix\Sale\Order
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\NotImplementedException
     */
    public static function Add(\Bitrix\Sale\Order $order, $code, $value)
    {
        /** @var \Bitrix\Sale\PropertyValueCollection $propertyCollection */
        $propertyCollection = $order->getPropertyCollection();
        $new = true;
        foreach ($propertyCollection as $propertyItem) {
            if ($propertyItem->getField("CODE") == $code) {
                $propertyItem->setField("VALUE", $value);
                $new = false;
                break;
            }
        }
        if ($new) {
            $db_props = \CSaleOrderProps::GetList(
                array("SORT" => "ASC"),
                array(
                    "CODE" => 'UID'
                ),
                false,
                false,
                array()
            );
            $props = $db_props->Fetch();
            if ($props) {
                $propertyValue = \Bitrix\Sale\PropertyValue::create($propertyCollection, [
                    'ID' => $props['ID'],
                    'NAME' => $props['NAME'],
                    'TYPE' => $props['TYPE'],
                    'CODE' => $props['CODE'],
                ]);
                $propertyValue->setField('VALUE', $value);
                $propertyCollection->addItem($propertyValue);
            }
        }
        return $order;
    }
}
