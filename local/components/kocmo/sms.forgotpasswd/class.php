<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

use \Bitrix\Main\Application;

\CBitrixComponent::includeComponentClass('bitrix:main.auth.forgotpasswd');

class SmsForgotPasswdComponent extends MainForgotPasswdComponent
{
    protected $formFields = [
        'phone' => 'USER_PHONE',
        'login' => 'USER_LOGIN',
        'password' => 'USER_PASSWORD',
        'confirm_password' => 'USER_CONFIRM_PASSWORD',
        'remember' => 'USER_REMEMBER',
        'checkword' => 'USER_CHECKWORD',
        'otp' => 'USER_OTP',
        'otp_remember' => 'OTP_REMEMBER',
        'action' => 'AUTH_ACTION'
    ];
    private $author = "KOCMO";
    private $smsCode = 0;

    protected function actionRequest()
    {
        if (!defined('ADMIN_SECTION') || ADMIN_SECTION !== true)
        {
            $lid = LANG;
        }
        else
        {
            $lid = false;
        }
        if(empty($this->arParams['CHANGE_PASS_URL'])){
            $this->arParams['CHANGE_PASS_URL'] = "/auth/sms-change.php";
        }

        if (!empty($_REQUEST['USER_PHONE'])) {

            if (!\CModule::IncludeModule('mlife.smsservices')) {
                throw new \Error("empty module 'mlife.smsservices'");
            }
            $phone = $this->requestField('phone');
            $phone = getPreparePhone($phone);
            $this->smsCode = random_int(100000, 999999);

            if (!empty($phone) && $this->updateSmsCode($phone)) {

                $_SESSION["system.auth.changepasswd"]["USER_PHONE_NUMBER"] = $_REQUEST['USER_PHONE'];
                $row = \Bitrix\Main\UserPhoneAuthTable::getList(["filter" => ["=PHONE_NUMBER" => $_REQUEST['USER_PHONE']]])->fetch();
                //echo '<pre>', var_dump($row), '</pre>';
                //echo '<pre>', print_r($this->smsCode, true), '</pre>';

                $messages = $this->getSmsMessages($this->smsCode);

                foreach($messages as $message ) {

                    $obSmsService = new \CMlifeSmsServices();
                    $arSend = $obSmsService->sendSms($phone, $message->getText(), 0, $this->author);
//                    echo '<pre>', print_r($message->getText(), true), '</pre>';
//                    echo '<pre>', print_r($arSend, true), '</pre>';
//                    die();
                    if ($arSend->error) {
                        $this->processingErrors([
                            'TYPE' => 'ERROR',
                            'MESSAGE' => 'При отправке sms произошла ошибка'
                        ]);
                    } else {
                        header('Location: ' . $this->arParams['CHANGE_PASS_URL']);
                        exit();
                    }
                    break;//пока хватит 1 смс
                }
            } else {
                $this->processingErrors([
                    'TYPE' => 'ERROR',
                    'MESSAGE' => 'Введённый номер телефона не действителен в системе'
                ]);
            }
        }
    }

    protected function getSmsMessages($code, $eventName = 'SMS_USER_RESTORE_PASSWORD'){

        $templates = Bitrix\Main\Sms\TemplateTable::getList([
                'select' => ['*', 'SITES.SITE_NAME', 'SITES.SERVER_NAME', 'SITES.LID'],
                'filter' => ['EVENT_NAME' => $eventName]]
        )->fetchCollection();

        $messages = [];

        foreach($templates as $template)
        {
            $messages[] = Bitrix\Main\Sms\Message::createFromTemplate($template, ['CODE' => $code]);
        }
        return $messages;
    }
//    public function executeComponent($applyTemplate = true){
//           parent::executeComponent($applyTemplate);
//    }

    private function getUserIdFromPhone($phone){

        if(empty($phone)){
            return 0;
        }

        $filter = ['PERSONAL_PHONE' => $phone];
        $by = "id";
        $order = "desc";
        $rsUsers = CUser::GetList($by, $order, $filter);

        if ($arUser = $rsUsers->fetch()) {

            return $arUser['ID'];
        }
        return 0;
    }

    private function updateSmsCode($phone){

        $userId = $this->getUserIdFromPhone($phone);

        if(intval($userId) < 1){
            return false;
        }
        $row = \Bitrix\Main\UserPhoneAuthTable::getList(['filter' => ['USER_ID' => $userId]])->fetch();

        if(is_array($row) && intval($row['USER_ID']) > 0 ) {

            $q = \Bitrix\Main\UserPhoneAuthTable::update($row['USER_ID'], [
                'USER_ID' => $userId,
                'PHONE_NUMBER' => $phone,
                'OTP_SECRET' => $this->smsCode,
            ]);
           // echo '<pre>', print_r($q, true) , '</pre>';
        }
        else{
            \Bitrix\Main\UserPhoneAuthTable::add([
                'USER_ID' => $userId,
                'PHONE_NUMBER' => $phone,
                'OTP_SECRET' => $this->smsCode,
            ]);
        }
        return true;
    }
}