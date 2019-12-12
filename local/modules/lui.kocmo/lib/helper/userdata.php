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
