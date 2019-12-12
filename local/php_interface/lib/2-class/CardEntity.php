<?php
namespace Kocmo\User\Discount;

class UserDiscount
{
    protected $reqData = null;
    protected $arData = null;
    protected $serviceUrl = '';
    private $getCartDiscountUrl = 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetCardDiscount?id=';
    private $userData = null;
    private $actionObj = null;

    public function __construct()
    {
    }

    public function getUserDiscount($discountCartNum){

        //$this->request($discountCartNum);
        $this->discountRequest($discountCartNum);

        if( is_array($this->arData) ){
            return $this->arData;
        }
        else{
            return false;
        }
    }

    private function request($discountCartNum){//тут будет запрос к сервису

        $this->reqData = '{"CurrentSum":"581",
"CurrentName":"4% от суммы при достижении 500 р.",
"CurrentValue":"4",
"NextSum":"600",
"NextName":"5% от суммы при достижении 600 р.",
"NextValue":"5"} ';

        $this->arData = json_decode($this->reqData, true);

        if( is_array($this->arData) && intval($this->arData['NextSum']) > 0
            && intval($this->arData['CurrentSum']) > 0){
            $this->arData['DiffSum'] = $this->arData['NextSum'] - $this->arData['CurrentSum'];
        }
    }
//bellow new
    private function discountRequest($discountCartNum)
    {
        if( intval($discountCartNum) < 1 ){
            return false;
        }

       // $userData = $this->getUserData($discountCartNum);

        $this->reqData = file_get_contents($this->getCartDiscountUrl . $discountCartNum);

        $this->arData = json_decode($this->reqData, true);

        if (is_array($this->arData) && intval($this->arData['NextSum']) > 0
            && intval($this->arData['CurrentSum']) > 0) {
            $this->arData['DiffSum'] = $this->arData['NextSum'] - $this->arData['CurrentSum'];
        }
    }

    private function getUserData($code){

        if($this->userData){
            return $this->userData;
        }

        $arResult = $this->actionObj->Run(['ACTION' => 'Discount', 'code' => $code]);

        return is_array($arResult) ? $arResult : false;
    }

    static public function getCurrentUserCard(){

        global $USER;

        $rsUser = \CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        if( !empty( $arUser['UF_CARD_KOCMO'] ) ){
            return $arUser['UF_CARD_KOCMO'];
        }

        return false;
    }
}