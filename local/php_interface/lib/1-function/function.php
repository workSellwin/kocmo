<?php
/*AddEventHandler('main', 'OnEpilog', '_Check404Error', 1);
function _Check404Error()
{
    if (defined('ERROR_404') && ERROR_404 == 'Y') {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/header.php';
        include $_SERVER['DOCUMENT_ROOT'] . '/404.php';
        include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/footer.php';
    }
}*/

function number_format_kocmo($float)
{
    return number_format($float, 2, '.', ' ');
}

if (!function_exists("getChilds")) {
    function getChilds($input, &$start = 0, $level = 0)
    {
        if (!$level) {
            $lastDepthLevel = 1;
            if (is_array($input)) {
                foreach ($input as $i => $arItem) {
                    if ($arItem["DEPTH_LEVEL"] > $lastDepthLevel) {
                        if ($i > 0) {
                            $input[$i - 1]["IS_PARENT"] = 1;
                        }
                    }
                    $lastDepthLevel = $arItem["DEPTH_LEVEL"];
                }
            }
        }
        $childs = array();
        $count = count($input);
        for ($i = $start; $i < $count; $i++) {
            $item = $input[$i];
            if ($level > $item['DEPTH_LEVEL'] - 1) {
                break;
            } elseif (!empty($item['IS_PARENT'])) {
                $i++;
                $item['CHILD'] = getChilds($input, $i, $level + 1);
                $i--;
            }
            $childs[] = $item;
        }
        $start = $i;
        return $childs;
    }
}
if (!function_exists("PR")) {
    /**
     * @param $o
     * @param bool $show
     * @param bool $die
     * @return bool
     */
    function PR($o, $UserId = false, $die = false)
    {
        global $USER, $APPLICATION;

        if (isset($_REQUEST['DEBUG']) and $_REQUEST['DEBUG'] == 'Y') {
            $show = true;
        }

        if ($die) {
            $APPLICATION->RestartBuffer();
        }

        if ((is_object($USER) and $USER->GetID() == $UserId) || $show) {
            $bt = debug_backtrace();
            $bt = $bt[0];
            $dRoot = $_SERVER["DOCUMENT_ROOT"];
            $dRoot = str_replace("/", "\\", $dRoot);
            $bt["file"] = str_replace($dRoot, "", $bt["file"]);
            $dRoot = str_replace("\\", "/", $dRoot);
            $bt["file"] = str_replace($dRoot, "", $bt["file"]);
            ?>
            <div style='font-size: 12px;font-family: monospace;width: 100%;color: #181819;background: #EDEEF8;border: 1px solid #006AC5;'>
                <div style='padding: 5px 10px;font-size: 10px;font-family: monospace;background: #006AC5;font-weight:bold;color: #fff;'>
                    File: <?= $bt["file"] ?> [<?= $bt["line"] ?>]
                </div>
                <pre style='padding:10px;text-align: left'><? print_r($o) ?></pre>
            </div>
            <?
        } else {
            return false;
        }
        if ($die) {
            die();
        }
    }
}

if (!function_exists("array_diff_assoc_recursive")) {
    function array_diff_assoc_recursive($array1, $array2)
    {
        $difference = array();
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key]) || !is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = array_diff_assoc_recursive($value, $array2[$key]);
                    if (!empty($new_diff))
                        $difference[$key] = $new_diff;
                }
            } else if (!array_key_exists($key, $array2) || $array2[$key] !== $value) {
                $difference[$key] = $value;
            }
        }
        return $difference;
    }
}

if (!function_exists('AddOrderProperty')) {
    /**
     * @param $prop_id
     * @param $value
     * @param $order
     * @return bool
     */
    function AddOrderProperty($prop_id, $value, $order)
    {
        if (!strlen($prop_id)) {
            return false;
        }
        if (CModule::IncludeModule('sale')) {
            if ($arOrderProps = CSaleOrderProps::GetByID($prop_id)) {
                $db_vals = CSaleOrderPropsValue::GetList(array(), array('ORDER_ID' => $order, 'ORDER_PROPS_ID' => $arOrderProps['ID']));
                if ($arVals = $db_vals->Fetch()) {
                    return CSaleOrderPropsValue::Update($arVals['ID'], array(
                        'NAME' => $arVals['NAME'],
                        'CODE' => $arVals['CODE'],
                        'ORDER_PROPS_ID' => $arVals['ORDER_PROPS_ID'],
                        'ORDER_ID' => $arVals['ORDER_ID'],
                        'VALUE' => $value,
                    ));
                } else {
                    return CSaleOrderPropsValue::Add(array(
                        'NAME' => $arOrderProps['NAME'],
                        'CODE' => $arOrderProps['CODE'],
                        'ORDER_PROPS_ID' => $arOrderProps['ID'],
                        'ORDER_ID' => $order,
                        'VALUE' => $value,
                    ));
                }
            }
        }
    }
}


if (!function_exists("getDataMainTabsSlider")) {
    function getDataMainTabsSlider()
    {
        return $arDataTabsSlider = [
            'news' => [
                'ID' => 'news',
                'NAME' => 'Новинки',
                'FILTER' => [
                    'PROPERTY_STATUS_VALUE' => 'Новинка',
                    'CATALOG_AVAILABLE' => 'Y'
                ],
                'ACTIVE' => 'active',
            ],
            'bestsellers' => [
                'ID' => 'bestsellers',
                'NAME' => 'Бестселлеры',
                'FILTER' => [
                    'PROPERTY_STATUS_VALUE' => 'Хит продаж',
                    'CATALOG_AVAILABLE' => 'Y'
                ],
                'ACTIVE' => ''
            ],
            'only-cosmo' => [
                'ID' => 'only-cosmo',
                'NAME' => 'Только в космо',
                'FILTER' => [
                    'PROPERTY_STATUS_VALUE' => 'Только в Космо',
                    'CATALOG_AVAILABLE' => 'Y'
                ],
                'ACTIVE' => ''
            ],
        ];
    }
}

if (!function_exists("getDataProductTabsSlider")) {
    function getDataProductTabsSlider()
    {
        return $arDataTabsSlider = [
            'news' => [
                'ID' => 'related',
                'NAME' => 'С этим товаром покупают',
                'FILTER' => [
                    'PROPERTY_NEWPRODUCT_VALUE' => 'Да'
                ],
                'ACTIVE' => 'active',
            ],
            'bestsellers' => [
                'ID' => 'recently',
                'NAME' => 'ВЫ ПРОСМАТРИВАЛИ',
                'FILTER' => [
                    'PROPERTY_SPECIALOFFER_VALUE' => 'Да'
                ],
                'ACTIVE' => ''
            ],
            'only-cosmo' => [
                'ID' => 'recommendations',
                'NAME' => 'РЕКОМЕНДАЦИИ ДЛЯ ВАС',
                'FILTER' => [
                    'PROPERTY_SALELEADER_VALUE' => 'Да'
                ],
                'ACTIVE' => ''
            ],
        ];
    }
}

if (!function_exists("URL")) {

    function URL($isArray = false)
    {
        global $APPLICATION;
        $url = $APPLICATION->GetCurPage();
        return $isArray ? explode('/', $url) : $url;
    }

}

if (!function_exists("Hide_H1")) {
    function Hide_H1()
    {
        global $APPLICATION;
        if ($APPLICATION->GetProperty("HIDE_H1") != "Y") {
            return "<h1 class='page-title'>" . $APPLICATION->GetTitle() . "</h1>";
        }
    }
}

if (!function_exists("CONFIG_SMART_FILTER")) {
    function CONFIG_SMART_FILTER()
    {
        return array();
    }
}

if (!function_exists("getPreparePhone")) {
    //единый формат телефона на сайте
    function getPreparePhone($rawPhone)
    {
        $phone = preg_replace('/[^0-9]/', '', $rawPhone);

        if (strlen($phone) === 12) {
            return $phone;
        } else {
            return false;
        }
    }
}
