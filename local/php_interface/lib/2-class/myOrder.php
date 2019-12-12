<?php

use Bitrix\Main\Context,
    Bitrix\Currency\CurrencyManager,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem,
    InitMainTrait;
use Bitrix\Sale;


class MyOrder
{
    use InitMainTrait;
    public $SITE_ID;
    public $CURRENCY_CODE = 'BYN';
    public $ANONYMOUS_USER = 3;
    public $PERSON_TYPE_ID = 1;
    public $PAYMENT_ID = 8;
    public $SHIPMENT = 3;
    protected $ORDER;
    protected $ITEM;

    public function __construct()
    {
        $this->SITE_ID = Context::getCurrent()->getSite();
    }

    public function process($PRODUCT_ID, $QUANTITY = 1, $SUM){
        global $USER;
        if(!$USER->isAuthorized())$USER->Authorize($this->ANONYMOUS_USER);

        $this->createOrder();
        $this->createBasket($PRODUCT_ID, $QUANTITY);
        $this->createShipment();
        $this->createPayment($SUM);
        $this->setProperty();
        return $this->save();
    }


    /**
     * Создаёт новый заказ
     * @throws \Bitrix\Main\ArgumentException
     */
    protected function createOrder(){
        global $USER;

        $order = Order::create($this->SITE_ID, $USER->isAuthorized() ? $USER->GetID() : $this->ANONYMOUS_USER);
        $order->setPersonTypeId($this->PERSON_TYPE_ID);
        $order->setField('CURRENCY', $this->CURRENCY_CODE);
        $this->ORDER = $order;
    }

    /**
     * Создаём корзину с одним товаром
     * @param $PRODUCT_ID
     * @param $QUANTITY
     */
    protected function createBasket($PRODUCT_ID, $QUANTITY){
        $basket = Basket::create($this->SITE_ID);
        $item = $basket->createItem('catalog', $PRODUCT_ID);
        $item->setFields(array(
            'QUANTITY' => $QUANTITY,
            'CURRENCY' => $this->CURRENCY_CODE,
            'LID' => $this->SITE_ID,
            'PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProvider',
        ));
        $basket->save();
        $this->ORDER->setBasket($basket);
        $this->ITEM = $item;
    }

    /**
     * Создаём одну отгрузку
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    protected function createShipment(){
        $shipmentCollection = $this->ORDER->getShipmentCollection();
        $shipment = $shipmentCollection->createItem();
        $service = Delivery\Services\Manager::getById($this->SHIPMENT);
        $shipment->setFields(array(
            'DELIVERY_ID' => $service['ID'],
            'DELIVERY_NAME' => $service['NAME'],
            'ALLOW_DELIVERY' => 'Y',
        ));
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        $shipmentItem = $shipmentItemCollection->createItem($this->ITEM);
        $shipmentItem->setQuantity($this->ITEM->getQuantity());

    }

    /**
     * Создаём оплату
     */
    protected function createPayment ($SUM){
        $paymentCollection = $this->ORDER->getPaymentCollection();
        $payment = $paymentCollection->createItem();
        $paySystemService = PaySystem\Manager::getObjectById($this->PAYMENT_ID);

        $payment->setFields(array(
            'PAY_SYSTEM_ID' => $paySystemService->getField("PAY_SYSTEM_ID"),
            'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
            'SUM' => $SUM,
        ));
    }

    /**
     * Устанавливаем свойства
     */
    protected function setProperty(){
       /* $propertyCollection = $this->ORDER->getPropertyCollection();
        //PR($propertyCollection);
        $egift = $propertyCollection->getArray();
        PR($egift);*/
        //$phoneProp->setValue('453453345');
        //$nameProp = $propertyCollection->getPayerName();
        //$nameProp->setValue('dgfsdfgsdgf');
    }

    /**Сохраняем
     * @return mixed
     */
    protected function save(){
        $this->ORDER->doFinalAction(true);
        $result = $this->ORDER->save();
        $orderId = $this->ORDER->getId();
        AddOrderProperty(35, 'Y' ,$orderId);
        return $orderId;
    }

    public function GetOrder($orderId){
        return Sale\Order::load($orderId);
    }

}