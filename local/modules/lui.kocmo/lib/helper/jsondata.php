<?php

namespace Lui\Kocmo\Helper;

class JsonData
{
    protected $json = '';

    public function __construct(string $json)
    {
        $this->json = $json;
    }

    public function GetArray()
    {
        return json_decode($this->json, true);
    }

    public function ConvertKeys()
    {
        $arData = $this->GetArray();
        $arDataNew = [];
        foreach ($arData as $key => $data) {
            if (is_array($data)) {
                $keyN = self::Convert($key);
                foreach ($data as $k => $v) {
                    $kN = self::Convert($k);
                    if (is_array($v)) {
                        foreach ($v as $k2 => $v2) {
                            $kN2 = self::Convert($k2);
                            $arDataNew[$keyN][$kN][$kN2] = $v2;
                        }
                    } else {
                        $arDataNew[$keyN][$kN] = $v;
                    }
                }
            } else {
                $keyN = self::Convert($key);
                $arDataNew[$keyN] = $data;
            }
        }
        unset($arData);
        $this->json = json_encode($arDataNew);
        return $this;
    }

    protected static function Convert($key)
    {
        return self::isKeyG($key) ? self::Replace($key) : $key;
    }


    protected static function isKeyG($key)
    {
        return strpos($key, 'g_') !== false ? true : false;
    }

    protected static function Replace($key)
    {
        return str_replace(['g_', '_'], ['', '-'], $key);
    }

}
