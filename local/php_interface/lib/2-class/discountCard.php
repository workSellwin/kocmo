<?php

namespace Kocmo\Card\Discount;

use Action;

class CardEntity
{
    protected $userId = null;
    protected $card = null;

    public function __construct()
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            throw new \Exception("user not authorized");
        }

        $this->userId = $USER->GetID();

        if (!empty($_SESSION['BIND_CARD'])) {
            $this->card = $_SESSION['BIND_CARD'];
        }
    }

    public function setSmsCode()
    {
        $_SESSION['BIND_CARD_SMS_CODE'] = random_int(100000, 999999);
    }

    private function getSmsCode()
    {
        return empty($_SESSION['BIND_CARD_SMS_CODE']) ? false : $_SESSION['BIND_CARD_SMS_CODE'];
    }

    public function checkSmsCode($smsCode)
    {
        return $_SESSION['BIND_CARD_SMS_CODE'] == $smsCode;
    }

    public function bindCard($card)
    {
        $card=
        $_SESSION['BIND_CARD'] = $card;
        $this->card = $card;
    }

    public function updUserBindCard()
    {

        if (!empty($this->card)) {
            $oUser = new \CUser;
            return $oUser->Update($this->userId, ['UF_CARD_KOCMO' => $this->card]);
        }
        return false;
    }

    public function getCardData($card)
    {

        $arResult = [];
        include_once $_SERVER['DOCUMENT_ROOT'] . '/discount/lib.php';

        $ob = new Action();
        $arResult = $ob->Run(['ACTION' => 'Discount', 'code' => str_pad($card, 6, '0', STR_PAD_LEFT)]);
        return $arResult;
    }

    public function sendCode($phone)
    {

        $smsCode = $this->getSmsCode();

        if (empty($smsCode)) {
            throw new \Error("empty sms code");
        }
        if (!\CModule::IncludeModule('mlife.smsservices')) {
            throw new \Error("empty module 'mlife.smsservices'");
        }

        $obSmsService = new \CMlifeSmsServices();

        $arSend = $obSmsService->sendSms($phone, $smsCode);

        if ($arSend->error) {//ЛОГИРОВАНИЕ

        } else {

        }
    }
}
