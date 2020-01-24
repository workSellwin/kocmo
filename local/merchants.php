<?php
use ASDRUBAEL\TO_XML;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require $_SERVER['DOCUMENT_ROOT'] . '/local/arrayToXML.php';

$site = 'http://10.1.102.35';
CModule::IncludeModule('iblock');

$res = CIBlockElement::GetList([], ["IBLOCK_ID" => 2, "ACTIVE" => "Y"/*, "CATALOG_AVAILABLE" => "Y"*/], false, false, /*["nTopCount" => 100],*/ [
    'ID', 'DETAIL_PAGE_URL', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE',
    'CATALOG_GROUP_2', 'CATALOG_GROUP_3', 'PROPERTY_MARKA'
]);
$items = [];

while($fields = $res->GetNext()){
    //pr($fields, 14);

    if(!checkParam($fields)){
        continue;
    }
    $arPrice = CCatalogProduct::GetOptimalPrice($fields['ID']);
    $arPrice['DISCOUNT_PRICE'] = number_format($arPrice['DISCOUNT_PRICE'], 2);
    $fields['CATALOG_PRICE_2'] = number_format($fields['CATALOG_PRICE_2'], 2);
    $description = empty($fields['DETAIL_TEXT']) ? $fields['PREVIEW_TEXT'] : $fields['DETAIL_TEXT'];
    $description = "<![CDATA[\n" . $description . "\n]]>";
    $basePic = $fields['PREVIEW_PICTURE'];

    if( empty($basePic) ){
        $basePic = $fields['DETAIL_PICTURE'];
    }

    if(intval($basePic) > 0){
        $basePic = \CFile::GetPath($basePic);
    }

    $items[] = [
        "name" => "item",
        "child" => [
            [
                "name" => 'g:id',
                "text" => $fields['ID'],
            ],
            [
                "name" => 'g:title',
                "text" => $fields['NAME'],
            ],
            [
                "name" => 'g:description',
                "text" => $description,
            ],
            [
                "name" => 'g:link',
                "text" => $site . $fields['DETAIL_PAGE_URL'],
            ],
            [
                "name" => 'g:image_link',
                "text" => $site . $basePic,
            ],
            [
                "name" => 'g:condition',
                "text" => 'new',
            ],
            [
                "name" => 'g:availability',
                "text" => 'in_stock',
            ],
            [
                "name" => 'g:price',
                "text" => $fields['CATALOG_PRICE_2'] . ' BYN',
            ],
            [
                "name" => 'g:sale_price',
                "text" => $arPrice['DISCOUNT_PRICE'] . ' BYN',
            ],
            [
                "name" => 'g:brand',
                "text" => $fields['PROPERTY_MARKA_VALUE'],
            ],
            [
                "name" => 'g:google_product_category',
                "text" => '1',
            ],
//            [
//                "name" => 'g:product_type',
//                "text" => $fields['NAME'],
//            ],
            [
                "name" => 'g:shipping',
                "child" => [
                    [
                        "name" => 'g:country',
                        "text" => "BY",
                    ],
//                    [
//                        "name" => 'g:service',
//                        "text" => "BY",
//                    ],
                    [
                        "name" => 'g:price',
                        "text" => $arPrice['DISCOUNT_PRICE'] > 40 ? "0 BYN" : "5.00 BYN",
                    ],
                ]
            ],
        ]
    ];
}

$arr = [
    "name" => "rss",
    "attr" => [
        "version" => "2.0",
        "xmlns:g" => "http://base.google.com/ns/1.0",
    ],
    "text" => "",
    "child" => [
        [
            "name" => "channel",
            "attr" => [],
            "text" => "",
            "child" => [
                [
                    "name" => "title",
                    "text" => "bh.by",
                ],
                [
                    "name" => "link",
                    "text" => "https://bh.by/",
                ],
                [
                    "name" => "description",
                    "text" => "description",
                ],
            ]
        ]
    ]
];

$arr['child'][0]['child'] = array_merge($arr['child'][0]['child'], $items);
//pr($arr['child'][0]['child'], 14);
try {
    $toXml = new TO_XML\ArrayToXML($arr, $_SERVER['DOCUMENT_ROOT'] . '/local/google.xml');
}
catch( Exception $e){
    echo $e->getMessage();
}

function checkParam(array $fields){

    if( empty($fields['DETAIL_TEXT']) && empty($fields['PREVIEW_TEXT']) ){
        return false;
    }

    if( empty($fields['DETAIL_PAGE_URL']) ){
        return false;
    }

    if( empty($fields['PREVIEW_PICTURE']) && empty($fields['DETAIL_PICTURE']) ){
        //return false;
    }

    if( empty($fields['CATALOG_PRICE_2']) ){
        return false;
    }

    return true;
}

