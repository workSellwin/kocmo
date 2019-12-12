<?php

use Lui\Kocmo\Data\IblockElement;

class EGift
{
    public $ID;
    public $IBLOCK_ID = 12;
    public $OB;

    public function __construct($elem_id = false)
    {

        if($elem_id){
            $this->ID = $elem_id;
            $arFilter = [
                'IBLOCK_ID' => $this->IBLOCK_ID,
                'ID' => $elem_id,
            ];
            $data = new IblockElement();
            $this->OB = $data->GetData($arFilter)[$elem_id];
        }

    }

    public function GetHtmlEmail(){
        $HTML = '';
        $HTML .='<div style="background-repeat: round; background-size: cover; display: inline-block; background-image: url(http://host060220193.of.by/'.$this->OB['PROPERTY']['COLOR_SERTIFIKAT'].')">';
        $HTML .='<p>ПОДАРОЧНЫЙ СЕРТИФИКАТ</p>';
        $HTML .='<p>Код: '.$this->OB['PROPERTY']['SHTRIH_KOD'].'</p>';
        $HTML .='<p>на сумму: '.$this->OB['PROPERTY']['SUM_SERTIFIKAT'].' BYN</p>';

        if($this->OB['PROPERTY']['FIO']){
            $HTML .='<p>Имя: '.$this->OB['PROPERTY']['FIO'].'</p>';
        }

        if($this->OB['PREVIEW_TEXT']){
            $HTML .='<p>Текст поздравления: '.$this->OB['PREVIEW_TEXT'].'</p>';
        }

        if($this->OB['PREVIEW_TEXT']){
            $HTML .='<p>Вам отправил: '.$this->OB['PROPERTY']['NAME_OTPRAVITEL'].'</p>';
        }
        $HTML .= '</div>';
        return $HTML;
    }

    public function GetHtmlEmailOb($data){
        $HTML = '';
        $HTML .='<div style="background-repeat: round; background-size: cover; display: inline-block; background-image: url(http://host060220193.of.by/'.$data['PROPERTY']['COLOR_SERTIFIKAT'].')">';
        $HTML .='<p>ПОДАРОЧНЫЙ СЕРТИФИКАТ</p>';
        $HTML .='<p>Код: '.$data['PROPERTY']['SHTRIH_KOD'].'</p>';
        $HTML .='<p>на сумму: '.$data['PROPERTY']['SUM_SERTIFIKAT'].' BYN</p>';

        if($data['PROPERTY']['FIO']){
            $HTML .='<p>Имя: '.$data['PROPERTY']['FIO'].'</p>';
        }

        if($data['PREVIEW_TEXT']){
            $HTML .='<p>Текст поздравления: '.$data['PREVIEW_TEXT'].'</p>';
        }

        if($data['PREVIEW_TEXT']){
            $HTML .='<p>Вам отправил: '.$data['PROPERTY']['NAME_OTPRAVITEL'].'</p>';
        }
        $HTML .= '</div>';
        return $HTML;
    }

    public static function TestNumberEGift(int $order_id, int $egift_id){
        $order = new MyOrder();

        $order = $order->GetOrder($order_id);
        $EGift = new EGift($egift_id);

        $paymentCollection = $order->getPaymentCollection();
        $data_payment = [];
        foreach ($paymentCollection as $payment) {
            $ps = $payment->getPaySystem();
            $data_payment['XML_ID'] = $ps->getField('XML_ID');
            $data_payment['VALUE'] = $payment->getSum();
        }

        $gui = $EGift->OB['PROPERTY']['GUI_SERTIFIKAT'];
        $uid_order = $order->getField('XML_ID');
        $uid_payment = $data_payment['XML_ID'];
        $value = $data_payment['VALUE'];


        $status_payment = new \Lui\Kocmo\Request\Post\SetPayment($uid_order, $uid_payment, $value);
        $status_payment = $status_payment->Send();

        $arJson = [
            'uid_order'=>$uid_order,
            'uid_EGifts'=>$gui,
        ];
        $sJson = json_encode($arJson);
        $ob = new \Lui\Kocmo\Request\Get\EGifts($sJson);
        $num_egift = $ob->Send();

        PR('UID ORDER - '.$uid_order, true);
        PR('UID PAYMENT - '.$uid_payment, true);
        PR('VALUE - '.$value, true);
        PR($status_payment, true);
        PR($num_egift, true);
    }

}