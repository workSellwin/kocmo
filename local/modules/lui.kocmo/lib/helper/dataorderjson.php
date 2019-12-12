<?php


namespace Lui\Kocmo\Helper;


class DataOrderJson
{
    public static function Order()
    {
        return [
            'OrderId' => '',
            'card' => '',
            'promo' => '',
            'shop' => '554e5046-aa97-11e8-a216-00505601048d',
            'documentid' => '',
            'payment' => [
                'name' => '',
                'uid' => '',
            ],
            'delivery' => [
                'name' => "",
                "uid" => "",
                'price' => "",
                "interval" => "",
                'params' => [
                    'date_time' => "04.11.2019 09:00:00",
                    'adress' => "Независимости 6"
                ],
            ],
            "personal" => [
                "name" => "",
                "lastname" => "",
                "email" => "",
                "phone" => "",
                "adress" => [
                    "city" => "Минск",
                    "street" => "",
                    "house" => "",
                    "corps" => "",
                    "flat" => ""
                ],
                "call_back" => "Y",
            ],
            'goods' => [
                [
                    "UID" => "8e1e5ee4-aaac-11e8-a216-00505601048d",
                    "COUNT" => "10.00",
                    "PRICE" => "12.50",
                    "SUMM" => "125.00",
                    "DISCOUNT" => [
                        [
                            "NAME" => "солнце 08.10-31.10",
                            "VALUE" => "37.00"
                        ],
                    ]
                ],
            ],
            "GIFT" => [
                [
                    "UID" => "eaf8f28b-c4df-11e9-a247-00505601048d",
                    "COUNT" => "1.00",
                    "PRICE" => "7.10"
                ]
            ]
        ];
    }
}
