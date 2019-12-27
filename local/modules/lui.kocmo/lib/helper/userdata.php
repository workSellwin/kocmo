<?php

namespace Lui\Kocmo\Helper;

class UserData
{
    use DataCacheSession;
    private static $instance;
    protected $arData = [];

    /**
     * @return UserData
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {
        $this->SetDataInit();
    }

    protected function SetDataInit()
    {
        global $USER;
        if (!$data = $this->GetDataCache()) {
            if (is_object($USER) and $id = $USER->GetID()) {
                $rsUser = \CUser::GetByID($id);
                $data = $rsUser->Fetch();
                $this->SetDataCache($data);
            }
        }
        if (!$data) $data = [];

        if (strlen($data['PERSONAL_STREET'])>10) {
            $a=substr(substr($data['PERSONAL_STREET'],2),0,-3);
            $a=explode('%;%',$a);
            $data['ADRESS']['STREET']=$a[0];
            $data['ADRESS']['HOUSE']=$a[1];
            $data['ADRESS']['CORPS']=$a[2];
            $data['ADRESS']['APARTMENT']=$a[3];
        }

        $this->arData = $data;
    }

    public function GetData()
    {
        return $this->arData;
    }

    /**
     * @return string
     */
    public function GetSessionCode()
    {
        return 'sellwin_bitrix_UserData';
    }


    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public function GetCard()
    {
        return $this->arData['UF_CARD_KOCMO'];
    }

}
