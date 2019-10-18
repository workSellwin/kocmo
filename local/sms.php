<?php
namespace Bh\Sms;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

class SmsNotification
{
    private $startOrderDate = false;
    private $restricts = [];

    public function __construct($day)
    {
        global $DB;

        if(!\CModule::IncludeModule('mlife.smsservices')){
            throw new \Error("empty module 'mlife.smsservices'");
        }
        if(!\CModule::IncludeModule('sale')){
            throw new \Error("empty module 'sale'");
        }
        if(!\CModule::IncludeModule('iblock')){
            throw new \Error("empty module 'iblock'");
        }
        $day = intval($day);

        if( $day == 0 ){
            $day = 100;
        }
        elseif( $day > 360 ){
            $day = 360;
        }

        $this->startOrderDate = date($DB->DateFormatToPHP(\CSite::GetDateFormat("SHORT")), time() - 86400 * $day);
        $this->setRestricts(46);
    }

    private function setRestricts($section = false, $days = false, $value = false, $productType = false){
        $this->restricts[] = new SmsRestricts($section, $days, $value, $productType);
    }

    public function smsSending(){//начать рассылку

        $subscribers = $this->getSubscribers();
pr($subscribers);
//        foreach($subscribers as $subscriber){
//            $this->send($subscriber);
//        }
    }

    private function send(Subscriber $subscriber){//отправка смс абоненту

        $obSmsServ = new \CMlifeSmsServices();
        $arSend = $obSmsServ->sendSms($subscriber->getPhone(), $subscriber->getMessage(), 0, 'BH.BY');

        if ($arSend->error) {
            //AddMessage2Log('\n\n\n Заказ №' . $ID . ' Ошибка отправки смс: ' . $arSend->error . ', код ошибки: ' . $arSend->error_code);
        } else {
            //AddMessage2Log('\n\n\n Заказ №' . $ID . ' Сообщение успешно отправлено, Стоимость рассылки:' . $arSend->cost . ' руб.');
        }
    }

    private function getConfig(){//получить ограничения, шаблоны и т.д.

    }

    private function getSubscribers(){//получить абонентов

        $res = \Bitrix\Sale\Order::getList([
            'filter' => [
                "!ID" => $this->getExceptionOrderId(),
                '>DATE_INSERT' => $this->startOrderDate,
                "STATUS_ID" => "ok",
                "!USER_ID" => $this->getExceptionFUser()
            ],
            //'limit' => 1,
            'order' => 'DATE_INSERT',
            'select' => ['ID', 'DATE_INSERT', 'USER_ID']
        ]);
        $orders = [];
        $orderIds = [];

        while( $orderFields = $res->fetch() ){
            $orders[$orderFields['ID']] = $orderFields;
            $orderIds[] = $orderFields['ID'];
        }
        unset($res);

        $res = \Bitrix\Sale\Basket::getList([
            'filter' => ['ORDER_ID' => $orderIds, 'QUANTITY' => 1],
            //'limit' => 10,
            'order' => 'ORDER_ID',
            'select' => ['ID', 'ORDER_ID', 'FUSER_ID', 'PRICE', 'DATE_INSERT', 'PRODUCT_ID'],
        ]);

        $basketItems = [];
        //$fUsers = [];
        $lastOrder = false;
        $maxPrice = 0;
        $productIds = [];

        while( $basketFields = $res->fetch() ){//сохраняем только самый дорогой

            $fUsers[$basketFields['FUSER_ID']] = $orders[$basketFields['ORDER_ID']]['USER_ID'];

//            if($lastOrder == $basketFields['ORDER_ID'] && $basketFields['PRICE'] > $maxPrice){
//                $basketItems[$basketFields['FUSER_ID']][$basketFields['ORDER_ID']] = $basketFields;
//                $maxPrice = $basketFields['PRICE'];
//            }
//            elseif( $lastOrder != $basketFields['ORDER_ID'] ){
//                $basketItems[$basketFields['FUSER_ID']][$basketFields['ORDER_ID']] = $basketFields;
//                $maxPrice = $basketFields['PRICE'];
//            }

            $productIds[$basketFields['PRODUCT_ID']] = false;
            $basketItems[$basketFields['FUSER_ID']][$basketFields['ORDER_ID']][] = $basketFields;
            $lastOrder = $basketFields['ORDER_ID'];
        }

        $productIds = $this->filterProducts($productIds);
        $gen = $this->basketItemsGen($basketItems);
        $subscribers = [];

//        foreach( $gen as $arBasketItem ){
//            $subscriber = $arBasketItem;
//        }

        return $productIds;
    }

    /**
     * @param $basketItems
     * @return \Generator
     */
    private function basketItemsGen($basketItems ){

        foreach( $basketItems as $item ){
            yield current($item);//тут будет логика, пока отдаёт первый
        }
    }

    private function getPriorityOrderForSendSms(){


    }

    private function filterProducts($ids){

        if( !is_array($this->restricts) && !count($this->restricts) ){
            return $ids;
        }

        $res = \CIBlockElement::GetList([], ["ID" => array_keys($ids)], false, false, []);

        while( $elFields = $res->fetch() ){
            foreach($this->restricts as $restrict){
                if($restrict->check($elFields)){
                    $ids[$elFields['ID']] = true;
                    break;
                }
            }
        }

        return $ids;
    }

    private function getExceptionFUser(){
        return [];//1618 вернуть массив FUSER, которые получали sms менее недели назад
    }

    private function getExceptionOrderId(){
        return [];//1618 вернуть массив ID заказов, на которые получали sms
    }
}

class Subscriber{

    private $userId = false;
    private $fUserId = false;
    private $phone = false;
    private $smsTemplate = false;
    private $good = false;
    //private $restricts = false;

    function __construct($phone, $smsTemplate, $good)
    {
        $this->phone = $phone;
        $this->smsTemplate = $smsTemplate;
        $this->good = $good;
    }

    public function getMessage(){
        $message = "";

        return $message;
    }

    public function getPhone(){
        return $this->phone;
    }

}

class SmsRestricts{

    private $section = false;
    private $days = false;
    private $value = false;
    private $productType = false;

    function __construct($section = false, $days = false, $value = false, $productType = false)
    {
        $this->section = intval($section);
        $this->$days = intval($section);
        $this->$value = intval($section);
        $this->$productType = intval($section);
    }

    public function check($arFields){

        if(intval($this->section) > 0 && $arFields['IBLOCK_SECTION_ID'] != $this->section){
            return false;
        }
        return true;
    }
}

$objNotification = new SmsNotification(10);
$objNotification->smsSending();