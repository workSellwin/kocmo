<?php

namespace Lui\Kocmo;

class IncludeComponent
{
    /**
     * @param array $arData
     */
    public static function NewsList(array $arData)
    {
        $template = $arData['template'];
        unset($arData['template']);
        $arProp = [
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "ADD_SECTIONS_CHAIN" => "N",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "N",
            "CACHE_FILTER" => "Y",
            "CACHE_GROUPS" => "N",
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "CHECK_DATES" => "Y",
            "DETAIL_URL" => "",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "DISPLAY_DATE" => "N",
            "DISPLAY_NAME" => "N",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "FIELD_CODE" => array("PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_TEXT", "DETAIL_PICTURE", "DATE_ACTIVE_FROM", "ACTIVE_FROM", "DATE_ACTIVE_TO", "ACTIVE_TO", "SHOW_COUNTER", ""),
            "FILTER_NAME" => "",
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "IBLOCK_ID" => "4",
            "IBLOCK_TYPE" => "references",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "INCLUDE_SUBSECTIONS" => "Y",
            "MESSAGE_404" => "",
            "NEWS_COUNT" => "20",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_TEMPLATE" => ".default",
            "PAGER_TITLE" => "Новости",
            "PARENT_SECTION_CODE" => "",
            "PREVIEW_TRUNCATE_LEN" => "",
            "PROPERTY_CODE" => array("LINK", ""),
            "SET_BROWSER_TITLE" => "N",
            "SET_LAST_MODIFIED" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_META_KEYWORDS" => "N",
            "SET_STATUS_404" => "N",
            "SET_TITLE" => "N",
            "SHOW_404" => "N",
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_BY2" => "SORT",
            "SORT_ORDER1" => "DESC",
            "SORT_ORDER2" => "ASC",
            "STRICT_SECTION_CHECK" => "N"
        ];

        self::IncludeComponent("bitrix:news.list", $template, array_merge($arProp, $arData));
    }


    public static function NewsDetail(array $arData)
    {
        $template = $arData['template'];
        unset($arData['template']);
        $arProp = [
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "ADD_ELEMENT_CHAIN" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "N",
            "BROWSER_TITLE" => "-",
            "CACHE_GROUPS" => "N",
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "CHECK_DATES" => "Y",
            "COMPONENT_TEMPLATE" => ".default",
            "DETAIL_URL" => "",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "DISPLAY_DATE" => "N",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "ELEMENT_CODE" => "",
            "ELEMENT_ID" => "",
            "FIELD_CODE" => array(0 => "NAME", 1 => "DETAIL_PICTURE", 2 => 'PREVIEW_PICTURE'),
            "IBLOCK_ID" => "4",
            "IBLOCK_TYPE" => "references",
            "IBLOCK_URL" => "",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "MESSAGE_404" => "",
            "META_DESCRIPTION" => "-",
            "META_KEYWORDS" => "-",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "PAGER_SHOW_ALL" => "N",
            "PAGER_TEMPLATE" => ".default",
            "PAGER_TITLE" => "Страница",
            "PROPERTY_CODE" => array(0 => "LINK", 1 => "",),
            "SET_BROWSER_TITLE" => "N",
            "SET_CANONICAL_URL" => "N",
            "SET_LAST_MODIFIED" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_META_KEYWORDS" => "N",
            "SET_STATUS_404" => "N",
            "SET_TITLE" => "N",
            "SHOW_404" => "N",
            "STRICT_SECTION_CHECK" => "N",
            "USE_PERMISSIONS" => "N",
            "USE_SHARE" => "N"
        ];
        self::IncludeComponent("bitrix:news.detail", $template, array_merge($arProp, $arData));
    }

    public static function AdvertisingBanner(array $arData)
    {
        $template = $arData['template'];
        unset($arData['template']);
        $arProp = [
            "BS_ARROW_NAV" => "Y",
            "BS_BULLET_NAV" => "Y",
            "BS_CYCLING" => "N",
            "BS_EFFECT" => "fade",
            "BS_HIDE_FOR_PHONES" => "N",
            "BS_HIDE_FOR_TABLETS" => "N",
            "BS_KEYBOARD" => "Y",
            "BS_WRAP" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "DEFAULT_TEMPLATE" => "-",
            "NOINDEX" => "N",
            "QUANTITY" => "1",
            "TYPE" => "INDEX",
            "COMPONENT_TEMPLATE" => ".default",
        ];
        self::IncludeComponent("bitrix:advertising.banner", $template, array_merge($arProp, $arData));
    }


    public static function Menu(array $arData)
    {
        $template = $arData['template'];
        unset($arData['template']);
        $arProp = [
            "ALLOW_MULTI_SELECT" => "N",
            "CHILD_MENU_TYPE" => "left",
            "DELAY" => "N",
            "MAX_LEVEL" => "1",
            "MENU_CACHE_GET_VARS" => array(0 => "",),
            "MENU_CACHE_TIME" => "86400",
            "MENU_CACHE_TYPE" => "A",
            "MENU_CACHE_USE_GROUPS" => "N",
            "ROOT_MENU_TYPE" => "top",
            "USE_EXT" => "N"
        ];
        self::IncludeComponent("bitrix:menu", $template, array_merge($arProp, $arData));
    }

    /**
     * @param $component
     * @param $template
     * @param $arParams
     */
    protected static function IncludeComponent($component, $template, $arParams)
    {
        global $APPLICATION;
        $APPLICATION->IncludeComponent(
            $component,
            $template,
            $arParams
        /*  false,
          ["HIDE_ICONS"=>"Y"]*/
        );
    }

}
