<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */

/** @var CBitrixComponent $component */

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true); ?>

</div>

<? $elementId = $APPLICATION->IncludeComponent(
    'bitrix:catalog.element',
    'main',
    array(
        //  'SESI' => $_SESSION,
        'USER_GROUP' => $USER->GetUserGroupArray(),
        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'PROPERTY_CODE' => $arParams['DETAIL_PROPERTY_CODE'],
        'META_KEYWORDS' => $arParams['DETAIL_META_KEYWORDS'],
        'META_DESCRIPTION' => $arParams['DETAIL_META_DESCRIPTION'],
        'BROWSER_TITLE' => $arParams['DETAIL_BROWSER_TITLE'],
        'SET_CANONICAL_URL' => $arParams['DETAIL_SET_CANONICAL_URL'],
        'BASKET_URL' => $arParams['BASKET_URL'],
        'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
        'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
        'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],
        'CHECK_SECTION_ID_VARIABLE' => (isset($arParams['DETAIL_CHECK_SECTION_ID_VARIABLE']) ? $arParams['DETAIL_CHECK_SECTION_ID_VARIABLE'] : ''),
        'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
        'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
        'CACHE_TYPE' => "N",//$arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
        'SET_TITLE' => 'N',//$arParams['SET_TITLE'],
        'SET_LAST_MODIFIED' => $arParams['SET_LAST_MODIFIED'],
        'MESSAGE_404' => $arParams['~MESSAGE_404'],
        'SET_STATUS_404' => $arParams['SET_STATUS_404'],
        'SHOW_404' => $arParams['SHOW_404'],
        'FILE_404' => $arParams['FILE_404'],
        'PRICE_CODE' => $arParams['PRICE_CODE'],
        'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
        'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
        'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
        'PRICE_VAT_SHOW_VALUE' => $arParams['PRICE_VAT_SHOW_VALUE'],
        'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
        'PRODUCT_PROPERTIES' => $arParams['PRODUCT_PROPERTIES'],
        'ADD_PROPERTIES_TO_BASKET' => (isset($arParams['ADD_PROPERTIES_TO_BASKET']) ? $arParams['ADD_PROPERTIES_TO_BASKET'] : ''),
        'PARTIAL_PRODUCT_PROPERTIES' => (isset($arParams['PARTIAL_PRODUCT_PROPERTIES']) ? $arParams['PARTIAL_PRODUCT_PROPERTIES'] : ''),
        'LINK_IBLOCK_TYPE' => $arParams['LINK_IBLOCK_TYPE'],
        'LINK_IBLOCK_ID' => $arParams['LINK_IBLOCK_ID'],
        'LINK_PROPERTY_SID' => $arParams['LINK_PROPERTY_SID'],
        'LINK_ELEMENTS_URL' => $arParams['LINK_ELEMENTS_URL'],

        'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
        'OFFERS_FIELD_CODE' => $arParams['DETAIL_OFFERS_FIELD_CODE'],
        'OFFERS_PROPERTY_CODE' => $arParams['DETAIL_OFFERS_PROPERTY_CODE'],
        'OFFERS_SORT_FIELD' => $arParams['OFFERS_SORT_FIELD'],
        'OFFERS_SORT_ORDER' => $arParams['OFFERS_SORT_ORDER'],
        'OFFERS_SORT_FIELD2' => $arParams['OFFERS_SORT_FIELD2'],
        'OFFERS_SORT_ORDER2' => $arParams['OFFERS_SORT_ORDER2'],

        'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
        'ELEMENT_CODE' => $arResult['VARIABLES']['ELEMENT_CODE'],
        'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'],
        'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
        'SECTION_URL' => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['section'],
        'DETAIL_URL' => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['element'],
        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
        'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
        'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],
        'USE_ELEMENT_COUNTER' => 'Y', //$arParams['USE_ELEMENT_COUNTER'],
        'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
        'USE_MAIN_ELEMENT_SECTION' => $arParams['USE_MAIN_ELEMENT_SECTION'],
        'STRICT_SECTION_CHECK' => (isset($arParams['DETAIL_STRICT_SECTION_CHECK']) ? $arParams['DETAIL_STRICT_SECTION_CHECK'] : ''),
        'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
        'LABEL_PROP' => $arParams['LABEL_PROP'],
        'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
        'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
        'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
        'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
        'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
        'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
        'DISCOUNT_PERCENT_POSITION' => (isset($arParams['DISCOUNT_PERCENT_POSITION']) ? $arParams['DISCOUNT_PERCENT_POSITION'] : ''),
        'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
        'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
        'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
        'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
        'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
        'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
        'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
        'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
        'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
        'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
        'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
        'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),
        'MESS_PRICE_RANGES_TITLE' => (isset($arParams['~MESS_PRICE_RANGES_TITLE']) ? $arParams['~MESS_PRICE_RANGES_TITLE'] : ''),
        'MESS_DESCRIPTION_TAB' => (isset($arParams['~MESS_DESCRIPTION_TAB']) ? $arParams['~MESS_DESCRIPTION_TAB'] : ''),
        'MESS_PROPERTIES_TAB' => (isset($arParams['~MESS_PROPERTIES_TAB']) ? $arParams['~MESS_PROPERTIES_TAB'] : ''),
        'MESS_COMMENTS_TAB' => (isset($arParams['~MESS_COMMENTS_TAB']) ? $arParams['~MESS_COMMENTS_TAB'] : ''),
        'MAIN_BLOCK_PROPERTY_CODE' => (isset($arParams['DETAIL_MAIN_BLOCK_PROPERTY_CODE']) ? $arParams['DETAIL_MAIN_BLOCK_PROPERTY_CODE'] : ''),
        'MAIN_BLOCK_OFFERS_PROPERTY_CODE' => (isset($arParams['DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE']) ? $arParams['DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE'] : ''),
        'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
        'VOTE_DISPLAY_AS_RATING' => (isset($arParams['DETAIL_VOTE_DISPLAY_AS_RATING']) ? $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] : ''),
        'USE_COMMENTS' => $arParams['DETAIL_USE_COMMENTS'],
        'BLOG_USE' => (isset($arParams['DETAIL_BLOG_USE']) ? $arParams['DETAIL_BLOG_USE'] : ''),
        'BLOG_URL' => (isset($arParams['DETAIL_BLOG_URL']) ? $arParams['DETAIL_BLOG_URL'] : ''),
        'BLOG_EMAIL_NOTIFY' => (isset($arParams['DETAIL_BLOG_EMAIL_NOTIFY']) ? $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] : ''),
        'VK_USE' => (isset($arParams['DETAIL_VK_USE']) ? $arParams['DETAIL_VK_USE'] : ''),
        'VK_API_ID' => (isset($arParams['DETAIL_VK_API_ID']) ? $arParams['DETAIL_VK_API_ID'] : 'API_ID'),
        'FB_USE' => (isset($arParams['DETAIL_FB_USE']) ? $arParams['DETAIL_FB_USE'] : ''),
        'FB_APP_ID' => (isset($arParams['DETAIL_FB_APP_ID']) ? $arParams['DETAIL_FB_APP_ID'] : ''),
        'BRAND_USE' => (isset($arParams['DETAIL_BRAND_USE']) ? $arParams['DETAIL_BRAND_USE'] : 'N'),
        'BRAND_PROP_CODE' => (isset($arParams['DETAIL_BRAND_PROP_CODE']) ? $arParams['DETAIL_BRAND_PROP_CODE'] : ''),
        'DISPLAY_NAME' => (isset($arParams['DETAIL_DISPLAY_NAME']) ? $arParams['DETAIL_DISPLAY_NAME'] : ''),
        'IMAGE_RESOLUTION' => (isset($arParams['DETAIL_IMAGE_RESOLUTION']) ? $arParams['DETAIL_IMAGE_RESOLUTION'] : ''),
        'PRODUCT_INFO_BLOCK_ORDER' => (isset($arParams['DETAIL_PRODUCT_INFO_BLOCK_ORDER']) ? $arParams['DETAIL_PRODUCT_INFO_BLOCK_ORDER'] : ''),
        'PRODUCT_PAY_BLOCK_ORDER' => (isset($arParams['DETAIL_PRODUCT_PAY_BLOCK_ORDER']) ? $arParams['DETAIL_PRODUCT_PAY_BLOCK_ORDER'] : ''),
        'ADD_DETAIL_TO_SLIDER' => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
        'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
        'ADD_SECTIONS_CHAIN' => (isset($arParams['ADD_SECTIONS_CHAIN']) ? $arParams['ADD_SECTIONS_CHAIN'] : ''),
        'ADD_ELEMENT_CHAIN' => (isset($arParams['ADD_ELEMENT_CHAIN']) ? $arParams['ADD_ELEMENT_CHAIN'] : ''),
        'DISPLAY_PREVIEW_TEXT_MODE' => (isset($arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE']) ? $arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] : ''),
        'DETAIL_PICTURE_MODE' => (isset($arParams['DETAIL_DETAIL_PICTURE_MODE']) ? $arParams['DETAIL_DETAIL_PICTURE_MODE'] : array()),
        'ADD_TO_BASKET_ACTION' => $basketAction,
        'ADD_TO_BASKET_ACTION_PRIMARY' => (isset($arParams['DETAIL_ADD_TO_BASKET_ACTION_PRIMARY']) ? $arParams['DETAIL_ADD_TO_BASKET_ACTION_PRIMARY'] : null),
        'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
        'DISPLAY_COMPARE' => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
        'COMPARE_PATH' => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['compare'],
        'BACKGROUND_IMAGE' => (isset($arParams['DETAIL_BACKGROUND_IMAGE']) ? $arParams['DETAIL_BACKGROUND_IMAGE'] : ''),
        'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
        'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
        'SET_VIEWED_IN_COMPONENT' => (isset($arParams['DETAIL_SET_VIEWED_IN_COMPONENT']) ? $arParams['DETAIL_SET_VIEWED_IN_COMPONENT'] : ''),
        'SHOW_SLIDER' => (isset($arParams['DETAIL_SHOW_SLIDER']) ? $arParams['DETAIL_SHOW_SLIDER'] : ''),
        'SLIDER_INTERVAL' => (isset($arParams['DETAIL_SLIDER_INTERVAL']) ? $arParams['DETAIL_SLIDER_INTERVAL'] : ''),
        'SLIDER_PROGRESS' => (isset($arParams['DETAIL_SLIDER_PROGRESS']) ? $arParams['DETAIL_SLIDER_PROGRESS'] : ''),
        'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
        'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
        'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

        'USE_GIFTS_DETAIL' => $arParams['USE_GIFTS_DETAIL'] ?: 'Y',
        'USE_GIFTS_MAIN_PR_SECTION_LIST' => $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] ?: 'Y',
        'GIFTS_SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
        'GIFTS_SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
        'GIFTS_DETAIL_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
        'GIFTS_DETAIL_HIDE_BLOCK_TITLE' => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
        'GIFTS_DETAIL_TEXT_LABEL_GIFT' => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
        'GIFTS_DETAIL_BLOCK_TITLE' => $arParams['GIFTS_DETAIL_BLOCK_TITLE'],
        'GIFTS_SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
        'GIFTS_SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
        'GIFTS_MESS_BTN_BUY' => $arParams['~GIFTS_MESS_BTN_BUY'],
        'GIFTS_PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
        'GIFTS_SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
        'GIFTS_SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
        'GIFTS_SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

        'GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
        'GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],
        'GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE'],
        "DETAIL_FIELD_CODE" => array(
            0 => "SHOW_COUNTER",
            1 => "MORE_PHOTO",
        ),

    ),
    $component
);



$cookie = Cookies::getCookies('YOU_WATCHED');
if ($cookie) {
    $cookie = explode('|', $cookie);
    if (!in_array($elementId, $cookie)) {
        if (count($cookie) < 10) {
            array_unshift($cookie, $elementId);
        } else {
            array_unshift($cookie, $elementId);
            array_pop($cookie);
        }
        $cookie = implode('|', $cookie);
        Cookies::setCookies('YOU_WATCHED', $cookie);
    }
} else {
    $cookie = $elementId;
    Cookies::setCookies('YOU_WATCHED', $cookie);
}

$cookie = Cookies::getCookies('YOU_WATCHED');
$cookie = explode('|', $cookie);
if (in_array($elementId, $cookie)) {
    $cookie = array_flip($cookie);
    unset($cookie[$elementId]);
    $cookie = array_flip($cookie);
}
?>

<?

$WITH_PRODUCT_BUY = array();
$RECOMMEND = array();
$res = CIBlockElement::GetProperty(2, $elementId, "sort", "asc", array("CODE" => ['RECOMMEND', "WITH_PRODUCT_BUY"]));
while ($ob = $res->GetNext()) {
    if ($ob['CODE'] == 'RECOMMEND' || $ob['CODE'] == 'WITH_PRODUCT_BUY') {
        if ($ob['VALUE']) {
            $arResult[$ob['CODE']][] = $ob['VALUE'];
        }
    }
}
?>


<div class="tabs-sliders tabs-wrap product__tabs-sliders js_tabs-wrap">

    <div class="tabs">
        <? if (!empty($arResult['WITH_PRODUCT_BUY'])): ?>
            <span data-id="related" class="tab tab--dotted js_tab ">С этим товаром покупают</span>
        <? endif; ?>
        <? if (!empty($cookie)): ?>
            <span data-id="recently" class="tab tab--dotted js_tab active">ВЫ ПРОСМАТРИВАЛИ</span>
        <? endif; ?>
        <? if (!empty($arResult['RECOMMEND'])): ?>
            <span data-id="recommendations" class="tab tab--dotted js_tab">РЕКОМЕНДУЕМЫЕ</span>
        <? endif; ?>
    </div>

    <div class="panels">

        <? if (!empty($arResult['WITH_PRODUCT_BUY'])): ?>
            <div data-id="related" class="panel js_panel ">
                <? global $arrFilterRelated;
                $arrFilterRelated['ID'] = $arResult['WITH_PRODUCT_BUY'];
                $APPLICATION->IncludeComponent("bitrix:catalog.section", "prod-tabs-sliders", Array(
                    "MOBILE_TITLE" => 'С этим товаром покупают',
                    "TabsSliderKey" => 'related',
                    "ACTION_VARIABLE" => "action",    // Название переменной, в которой передается действие
                    "ADD_PICT_PROP" => "MORE_PHOTO",    // Дополнительная картинка основного товара
                    "ADD_PROPERTIES_TO_BASKET" => "Y",    // Добавлять в корзину свойства товаров и предложений
                    "ADD_SECTIONS_CHAIN" => "N",    // Включать раздел в цепочку навигации
                    "ADD_TO_BASKET_ACTION" => "ADD",    // Показывать кнопку добавления в корзину или покупки
                    "AJAX_MODE" => "N",    // Включить режим AJAX
                    "AJAX_OPTION_ADDITIONAL" => "",    // Дополнительный идентификатор
                    "AJAX_OPTION_HISTORY" => "N",    // Включить эмуляцию навигации браузера
                    "AJAX_OPTION_JUMP" => "N",    // Включить прокрутку к началу компонента
                    "AJAX_OPTION_STYLE" => "N",    // Включить подгрузку стилей
                    "BACKGROUND_IMAGE" => "-",    // Установить фоновую картинку для шаблона из свойства
                    "BASKET_URL" => "/personal/basket.php",    // URL, ведущий на страницу с корзиной покупателя
                    "BROWSER_TITLE" => "-",    // Установить заголовок окна браузера из свойства
                    "CACHE_FILTER" => "N",    // Кешировать при установленном фильтре
                    "CACHE_GROUPS" => "N",    // Учитывать права доступа
                    "CACHE_TIME" => "36000000",    // Время кеширования (сек.)
                    "CACHE_TYPE" => "A",    // Тип кеширования
                    "COMPARE_NAME" => "CATALOG_COMPARE_LIST",    // Уникальное имя для списка сравнения
                    "COMPARE_PATH" => "",    // Путь к странице сравнения
                    "COMPATIBLE_MODE" => "N",    // Включить режим совместимости
                    "CONVERT_CURRENCY" => "Y",    // Показывать цены в одной валюте
                    "CURRENCY_ID" => "BYN",    // Валюта, в которую будут сконвертированы цены
                    "CUSTOM_FILTER" => "",
                    "DETAIL_URL" => "",    // URL, ведущий на страницу с содержимым элемента раздела
                    "DISABLE_INIT_JS_IN_COMPONENT" => "N",    // Не подключать js-библиотеки в компоненте
                    "DISCOUNT_PERCENT_POSITION" => "bottom-right",    // Расположение процента скидки
                    "DISPLAY_BOTTOM_PAGER" => "Y",    // Выводить под списком
                    "DISPLAY_COMPARE" => "Y",    // Разрешить сравнение товаров
                    "DISPLAY_TOP_PAGER" => "N",    // Выводить над списком
                    "ELEMENT_SORT_FIELD" => "sort",    // По какому полю сортируем элементы
                    "ELEMENT_SORT_FIELD2" => "id",    // Поле для второй сортировки элементов
                    "ELEMENT_SORT_ORDER" => "asc",    // Порядок сортировки элементов
                    "ELEMENT_SORT_ORDER2" => "desc",    // Порядок второй сортировки элементов
                    "ENLARGE_PRODUCT" => "STRICT",    // Выделять товары в списке
                    "FILTER_NAME" => "arrFilterRelated",    // Имя массива со значениями фильтра для фильтрации элементов
                    "HIDE_NOT_AVAILABLE" => "L",    // Недоступные товары
                    "HIDE_NOT_AVAILABLE_OFFERS" => "N",    // Недоступные торговые предложения
                    "IBLOCK_ID" => "2",    // Инфоблок
                    "IBLOCK_TYPE" => "catalog",    // Тип инфоблока
                    "INCLUDE_SUBSECTIONS" => "Y",    // Показывать элементы подразделов раздела
                    "LABEL_PROP" => "",    // Свойства меток товара
                    "LAZY_LOAD" => "N",    // Показать кнопку ленивой загрузки Lazy Load
                    "LINE_ELEMENT_COUNT" => "3",    // Количество элементов выводимых в одной строке таблицы
                    "LOAD_ON_SCROLL" => "N",    // Подгружать товары при прокрутке до конца
                    "MESSAGE_404" => "",    // Сообщение для показа (по умолчанию из компонента)
                    "MESS_BTN_ADD_TO_BASKET" => "В корзину",    // Текст кнопки "Добавить в корзину"
                    "MESS_BTN_BUY" => "Купить",    // Текст кнопки "Купить"
                    "MESS_BTN_COMPARE" => "Сравнить",    // Текст кнопки "Сравнить"
                    "MESS_BTN_DETAIL" => "Подробнее",    // Текст кнопки "Подробнее"
                    "MESS_BTN_SUBSCRIBE" => "Подписаться",    // Текст кнопки "Уведомить о поступлении"
                    "MESS_NOT_AVAILABLE" => "Нет в наличии",    // Сообщение об отсутствии товара
                    "META_DESCRIPTION" => "-",    // Установить описание страницы из свойства
                    "META_KEYWORDS" => "-",    // Установить ключевые слова страницы из свойства
                    "OFFERS_FIELD_CODE" => array(    // Поля предложений
                        0 => "NAME",
                        1 => "",
                    ),
                    "OFFERS_LIMIT" => "5",
                    "OFFERS_SORT_FIELD" => "sort",    // По какому полю сортируем предложения товара
                    "OFFERS_SORT_FIELD2" => "id",    // Поле для второй сортировки предложений товара
                    "OFFERS_SORT_ORDER" => "asc",    // Порядок сортировки предложений товара
                    "OFFERS_SORT_ORDER2" => "desc",    // Порядок второй сортировки предложений товара
                    "OFFER_ADD_PICT_PROP" => "-",    // Дополнительные картинки предложения
                    "PAGER_BASE_LINK_ENABLE" => "N",    // Включить обработку ссылок
                    "PAGER_DESC_NUMBERING" => "N",    // Использовать обратную навигацию
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",    // Время кеширования страниц для обратной навигации
                    "PAGER_SHOW_ALL" => "N",    // Показывать ссылку "Все"
                    "PAGER_SHOW_ALWAYS" => "N",    // Выводить всегда
                    "PAGER_TEMPLATE" => ".default",    // Шаблон постраничной навигации
                    "PAGER_TITLE" => "Товары",    // Название категорий
                    "PAGE_ELEMENT_COUNT" => "30",    // Количество элементов на странице
                    "PARTIAL_PRODUCT_PROPERTIES" => "Y",    // Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
                    "PRICE_CODE" => array(    // Тип цены
                        0 => "BASE",
                    ),
                    "PRICE_VAT_INCLUDE" => "Y",    // Включать НДС в цену
                    "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",    // Порядок отображения блоков товара
                    "PRODUCT_DISPLAY_MODE" => "Y",    // Схема отображения
                    "PRODUCT_ID_VARIABLE" => "id",    // Название переменной, в которой передается код товара для покупки
                    "PRODUCT_PROPS_VARIABLE" => "prop",    // Название переменной, в которой передаются характеристики товара
                    "PRODUCT_QUANTITY_VARIABLE" => "quantity",    // Название переменной, в которой передается количество товара
                    "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",    // Вариант отображения товаров
                    "PRODUCT_SUBSCRIPTION" => "Y",    // Разрешить оповещения для отсутствующих товаров
                    "PROPERTY_CODE_MOBILE" => "",    // Свойства товаров, отображаемые на мобильных устройствах
                    "RCM_PROD_ID" => "",    // Параметр ID продукта (для товарных рекомендаций)
                    "RCM_TYPE" => "personal",    // Тип рекомендации
                    "SECTION_CODE" => "",    // Код раздела
                    "SECTION_ID" => "",    // ID раздела
                    "SECTION_ID_VARIABLE" => "SECTION_ID",    // Название переменной, в которой передается код группы
                    "SECTION_URL" => "",    // URL, ведущий на страницу с содержимым раздела
                    "SECTION_USER_FIELDS" => array(    // Свойства раздела
                        0 => "",
                        1 => "",
                    ),
                    "SEF_MODE" => "N",    // Включить поддержку ЧПУ
                    "SET_BROWSER_TITLE" => "N",    // Устанавливать заголовок окна браузера
                    "SET_LAST_MODIFIED" => "N",    // Устанавливать в заголовках ответа время модификации страницы
                    "SET_META_DESCRIPTION" => "N",    // Устанавливать описание страницы
                    "SET_META_KEYWORDS" => "N",    // Устанавливать ключевые слова страницы
                    "SET_STATUS_404" => "N",    // Устанавливать статус 404
                    "SET_TITLE" => "N",    // Устанавливать заголовок страницы
                    "SHOW_404" => "N",    // Показ специальной страницы
                    "SHOW_ALL_WO_SECTION" => "Y",    // Показывать все элементы, если не указан раздел
                    "SHOW_CLOSE_POPUP" => "N",    // Показывать кнопку продолжения покупок во всплывающих окнах
                    "SHOW_DISCOUNT_PERCENT" => "Y",    // Показывать процент скидки
                    "SHOW_FROM_SECTION" => "Y",    // Показывать товары из раздела
                    "SHOW_MAX_QUANTITY" => "N",    // Показывать остаток товара
                    "SHOW_OLD_PRICE" => "Y",    // Показывать старую цену
                    "SHOW_PRICE_COUNT" => "1",    // Выводить цены для количества
                    "SHOW_SLIDER" => "N",    // Показывать слайдер для товаров
                    "SLIDER_INTERVAL" => "3000",
                    "SLIDER_PROGRESS" => "N",
                    "TEMPLATE_THEME" => "blue",    // Цветовая тема
                    "USE_ENHANCED_ECOMMERCE" => "N",    // Отправлять данные электронной торговли в Google и Яндекс
                    "USE_MAIN_ELEMENT_SECTION" => "N",    // Использовать основной раздел для показа элемента
                    "USE_PRICE_COUNT" => "N",    // Использовать вывод цен с диапазонами
                    "USE_PRODUCT_QUANTITY" => "N",    // Разрешить указание количества товара
                ),
                    false
                ); ?>
            </div>
        <? endif; ?>


        <?
        if (!empty($cookie)): ?>
            <div data-id="recently" class="panel js_panel active">
                <? global $arrFilterRecently;
                $arrFilterRecently['ID'] = $cookie;
                $APPLICATION->IncludeComponent("bitrix:catalog.section", "prod-tabs-sliders", Array(
                    "MOBILE_TITLE" => "Вы просматривали",
                    "TabsSliderKey" => "recently",
                    "ACTION_VARIABLE" => "action",    // Название переменной, в которой передается действие
                    "ADD_PICT_PROP" => "MORE_PHOTO",    // Дополнительная картинка основного товара
                    "ADD_PROPERTIES_TO_BASKET" => "Y",    // Добавлять в корзину свойства товаров и предложений
                    "ADD_SECTIONS_CHAIN" => "N",    // Включать раздел в цепочку навигации
                    "ADD_TO_BASKET_ACTION" => "ADD",    // Показывать кнопку добавления в корзину или покупки
                    "AJAX_MODE" => "N",    // Включить режим AJAX
                    "AJAX_OPTION_ADDITIONAL" => "",    // Дополнительный идентификатор
                    "AJAX_OPTION_HISTORY" => "N",    // Включить эмуляцию навигации браузера
                    "AJAX_OPTION_JUMP" => "N",    // Включить прокрутку к началу компонента
                    "AJAX_OPTION_STYLE" => "N",    // Включить подгрузку стилей
                    "BACKGROUND_IMAGE" => "-",    // Установить фоновую картинку для шаблона из свойства
                    "BASKET_URL" => "/personal/basket.php",    // URL, ведущий на страницу с корзиной покупателя
                    "BROWSER_TITLE" => "-",    // Установить заголовок окна браузера из свойства
                    "CACHE_FILTER" => "N",    // Кешировать при установленном фильтре
                    "CACHE_GROUPS" => "N",    // Учитывать права доступа
                    "CACHE_TIME" => "36000000",    // Время кеширования (сек.)
                    "CACHE_TYPE" => "A",    // Тип кеширования
                    "COMPARE_NAME" => "CATALOG_COMPARE_LIST",    // Уникальное имя для списка сравнения
                    "COMPARE_PATH" => "",    // Путь к странице сравнения
                    "COMPATIBLE_MODE" => "N",    // Включить режим совместимости
                    "CONVERT_CURRENCY" => "Y",    // Показывать цены в одной валюте
                    "CURRENCY_ID" => "BYN",    // Валюта, в которую будут сконвертированы цены
                    "CUSTOM_FILTER" => "",
                    "DETAIL_URL" => "",    // URL, ведущий на страницу с содержимым элемента раздела
                    "DISABLE_INIT_JS_IN_COMPONENT" => "N",    // Не подключать js-библиотеки в компоненте
                    "DISCOUNT_PERCENT_POSITION" => "bottom-right",    // Расположение процента скидки
                    "DISPLAY_BOTTOM_PAGER" => "Y",    // Выводить под списком
                    "DISPLAY_COMPARE" => "Y",    // Разрешить сравнение товаров
                    "DISPLAY_TOP_PAGER" => "N",    // Выводить над списком
                    "ELEMENT_SORT_FIELD" => "sort",    // По какому полю сортируем элементы
                    "ELEMENT_SORT_FIELD2" => "id",    // Поле для второй сортировки элементов
                    "ELEMENT_SORT_ORDER" => "asc",    // Порядок сортировки элементов
                    "ELEMENT_SORT_ORDER2" => "desc",    // Порядок второй сортировки элементов
                    "ENLARGE_PRODUCT" => "STRICT",    // Выделять товары в списке
                    "FILTER_NAME" => "arrFilterRecently",    // Имя массива со значениями фильтра для фильтрации элементов
                    "HIDE_NOT_AVAILABLE" => "L",    // Недоступные товары
                    "HIDE_NOT_AVAILABLE_OFFERS" => "N",    // Недоступные торговые предложения
                    "IBLOCK_ID" => "2",    // Инфоблок
                    "IBLOCK_TYPE" => "catalog",    // Тип инфоблока
                    "INCLUDE_SUBSECTIONS" => "Y",    // Показывать элементы подразделов раздела
                    "LABEL_PROP" => "",    // Свойства меток товара
                    "LAZY_LOAD" => "N",    // Показать кнопку ленивой загрузки Lazy Load
                    "LINE_ELEMENT_COUNT" => "3",    // Количество элементов выводимых в одной строке таблицы
                    "LOAD_ON_SCROLL" => "N",    // Подгружать товары при прокрутке до конца
                    "MESSAGE_404" => "",    // Сообщение для показа (по умолчанию из компонента)
                    "MESS_BTN_ADD_TO_BASKET" => "В корзину",    // Текст кнопки "Добавить в корзину"
                    "MESS_BTN_BUY" => "Купить",    // Текст кнопки "Купить"
                    "MESS_BTN_COMPARE" => "Сравнить",    // Текст кнопки "Сравнить"
                    "MESS_BTN_DETAIL" => "Подробнее",    // Текст кнопки "Подробнее"
                    "MESS_BTN_SUBSCRIBE" => "Подписаться",    // Текст кнопки "Уведомить о поступлении"
                    "MESS_NOT_AVAILABLE" => "Нет в наличии",    // Сообщение об отсутствии товара
                    "META_DESCRIPTION" => "-",    // Установить описание страницы из свойства
                    "META_KEYWORDS" => "-",    // Установить ключевые слова страницы из свойства
                    "OFFERS_FIELD_CODE" => array(    // Поля предложений
                        0 => "NAME",
                        1 => "",
                    ),
                    "OFFERS_LIMIT" => "5",
                    "OFFERS_SORT_FIELD" => "sort",    // По какому полю сортируем предложения товара
                    "OFFERS_SORT_FIELD2" => "id",    // Поле для второй сортировки предложений товара
                    "OFFERS_SORT_ORDER" => "asc",    // Порядок сортировки предложений товара
                    "OFFERS_SORT_ORDER2" => "desc",    // Порядок второй сортировки предложений товара
                    "OFFER_ADD_PICT_PROP" => "-",    // Дополнительные картинки предложения
                    "PAGER_BASE_LINK_ENABLE" => "N",    // Включить обработку ссылок
                    "PAGER_DESC_NUMBERING" => "N",    // Использовать обратную навигацию
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",    // Время кеширования страниц для обратной навигации
                    "PAGER_SHOW_ALL" => "N",    // Показывать ссылку "Все"
                    "PAGER_SHOW_ALWAYS" => "N",    // Выводить всегда
                    "PAGER_TEMPLATE" => ".default",    // Шаблон постраничной навигации
                    "PAGER_TITLE" => "Товары",    // Название категорий
                    "PAGE_ELEMENT_COUNT" => "30",    // Количество элементов на странице
                    "PARTIAL_PRODUCT_PROPERTIES" => "Y",    // Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
                    "PRICE_CODE" => array(    // Тип цены
                        0 => "BASE",
                    ),
                    "PRICE_VAT_INCLUDE" => "Y",    // Включать НДС в цену
                    "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",    // Порядок отображения блоков товара
                    "PRODUCT_DISPLAY_MODE" => "Y",    // Схема отображения
                    "PRODUCT_ID_VARIABLE" => "id",    // Название переменной, в которой передается код товара для покупки
                    "PRODUCT_PROPS_VARIABLE" => "prop",    // Название переменной, в которой передаются характеристики товара
                    "PRODUCT_QUANTITY_VARIABLE" => "quantity",    // Название переменной, в которой передается количество товара
                    "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",    // Вариант отображения товаров
                    "PRODUCT_SUBSCRIPTION" => "Y",    // Разрешить оповещения для отсутствующих товаров
                    "PROPERTY_CODE_MOBILE" => "",    // Свойства товаров, отображаемые на мобильных устройствах
                    "RCM_PROD_ID" => "",    // Параметр ID продукта (для товарных рекомендаций)
                    "RCM_TYPE" => "personal",    // Тип рекомендации
                    "SECTION_CODE" => "",    // Код раздела
                    "SECTION_ID" => "",    // ID раздела
                    "SECTION_ID_VARIABLE" => "SECTION_ID",    // Название переменной, в которой передается код группы
                    "SECTION_URL" => "",    // URL, ведущий на страницу с содержимым раздела
                    "SECTION_USER_FIELDS" => array(    // Свойства раздела
                        0 => "",
                        1 => "",
                    ),
                    "SEF_MODE" => "N",    // Включить поддержку ЧПУ
                    "SET_BROWSER_TITLE" => "N",    // Устанавливать заголовок окна браузера
                    "SET_LAST_MODIFIED" => "N",    // Устанавливать в заголовках ответа время модификации страницы
                    "SET_META_DESCRIPTION" => "N",    // Устанавливать описание страницы
                    "SET_META_KEYWORDS" => "N",    // Устанавливать ключевые слова страницы
                    "SET_STATUS_404" => "N",    // Устанавливать статус 404
                    "SET_TITLE" => "N",    // Устанавливать заголовок страницы
                    "SHOW_404" => "N",    // Показ специальной страницы
                    "SHOW_ALL_WO_SECTION" => "Y",    // Показывать все элементы, если не указан раздел
                    "SHOW_CLOSE_POPUP" => "N",    // Показывать кнопку продолжения покупок во всплывающих окнах
                    "SHOW_DISCOUNT_PERCENT" => "Y",    // Показывать процент скидки
                    "SHOW_FROM_SECTION" => "Y",    // Показывать товары из раздела
                    "SHOW_MAX_QUANTITY" => "N",    // Показывать остаток товара
                    "SHOW_OLD_PRICE" => "Y",    // Показывать старую цену
                    "SHOW_PRICE_COUNT" => "1",    // Выводить цены для количества
                    "SHOW_SLIDER" => "N",    // Показывать слайдер для товаров
                    "SLIDER_INTERVAL" => "3000",
                    "SLIDER_PROGRESS" => "N",
                    "TEMPLATE_THEME" => "blue",    // Цветовая тема
                    "USE_ENHANCED_ECOMMERCE" => "N",    // Отправлять данные электронной торговли в Google и Яндекс
                    "USE_MAIN_ELEMENT_SECTION" => "N",    // Использовать основной раздел для показа элемента
                    "USE_PRICE_COUNT" => "N",    // Использовать вывод цен с диапазонами
                    "USE_PRODUCT_QUANTITY" => "N",    // Разрешить указание количества товара
                ),
                    false
                );

                ?>
            </div>
        <? endif; ?>

        <? if (!empty($arResult['RECOMMEND'])): ?>
            <div data-id="recommendations" class="panel js_panel">


                <? global $arrFilterRelated2;
                $arrFilterRelated2['ID'] = $arResult['RECOMMEND'];
                $APPLICATION->IncludeComponent("bitrix:catalog.section", "prod-tabs-sliders", Array(
                    "MOBILE_TITLE" => "РЕКОМЕНДУЕМЫЕ",
                    "TabsSliderKey" => "recommendations",
                    "ACTION_VARIABLE" => "action",    // Название переменной, в которой передается действие
                    "ADD_PICT_PROP" => "MORE_PHOTO",    // Дополнительная картинка основного товара
                    "ADD_PROPERTIES_TO_BASKET" => "Y",    // Добавлять в корзину свойства товаров и предложений
                    "ADD_SECTIONS_CHAIN" => "N",    // Включать раздел в цепочку навигации
                    "ADD_TO_BASKET_ACTION" => "ADD",    // Показывать кнопку добавления в корзину или покупки
                    "AJAX_MODE" => "N",    // Включить режим AJAX
                    "AJAX_OPTION_ADDITIONAL" => "",    // Дополнительный идентификатор
                    "AJAX_OPTION_HISTORY" => "N",    // Включить эмуляцию навигации браузера
                    "AJAX_OPTION_JUMP" => "N",    // Включить прокрутку к началу компонента
                    "AJAX_OPTION_STYLE" => "N",    // Включить подгрузку стилей
                    "BACKGROUND_IMAGE" => "-",    // Установить фоновую картинку для шаблона из свойства
                    "BASKET_URL" => "/personal/basket.php",    // URL, ведущий на страницу с корзиной покупателя
                    "BROWSER_TITLE" => "-",    // Установить заголовок окна браузера из свойства
                    "CACHE_FILTER" => "N",    // Кешировать при установленном фильтре
                    "CACHE_GROUPS" => "N",    // Учитывать права доступа
                    "CACHE_TIME" => "36000000",    // Время кеширования (сек.)
                    "CACHE_TYPE" => "A",    // Тип кеширования
                    "COMPARE_NAME" => "CATALOG_COMPARE_LIST",    // Уникальное имя для списка сравнения
                    "COMPARE_PATH" => "",    // Путь к странице сравнения
                    "COMPATIBLE_MODE" => "N",    // Включить режим совместимости
                    "CONVERT_CURRENCY" => "Y",    // Показывать цены в одной валюте
                    "CURRENCY_ID" => "BYN",    // Валюта, в которую будут сконвертированы цены
                    "CUSTOM_FILTER" => "",
                    "DETAIL_URL" => "",    // URL, ведущий на страницу с содержимым элемента раздела
                    "DISABLE_INIT_JS_IN_COMPONENT" => "N",    // Не подключать js-библиотеки в компоненте
                    "DISCOUNT_PERCENT_POSITION" => "bottom-right",    // Расположение процента скидки
                    "DISPLAY_BOTTOM_PAGER" => "Y",    // Выводить под списком
                    "DISPLAY_COMPARE" => "Y",    // Разрешить сравнение товаров
                    "DISPLAY_TOP_PAGER" => "N",    // Выводить над списком
                    "ELEMENT_SORT_FIELD" => "sort",    // По какому полю сортируем элементы
                    "ELEMENT_SORT_FIELD2" => "id",    // Поле для второй сортировки элементов
                    "ELEMENT_SORT_ORDER" => "asc",    // Порядок сортировки элементов
                    "ELEMENT_SORT_ORDER2" => "desc",    // Порядок второй сортировки элементов
                    "ENLARGE_PRODUCT" => "STRICT",    // Выделять товары в списке
                    "FILTER_NAME" => "arrFilterRelated2",    // Имя массива со значениями фильтра для фильтрации элементов
                    "HIDE_NOT_AVAILABLE" => "L",    // Недоступные товары
                    "HIDE_NOT_AVAILABLE_OFFERS" => "N",    // Недоступные торговые предложения
                    "IBLOCK_ID" => "2",    // Инфоблок
                    "IBLOCK_TYPE" => "catalog",    // Тип инфоблока
                    "INCLUDE_SUBSECTIONS" => "Y",    // Показывать элементы подразделов раздела
                    "LABEL_PROP" => "",    // Свойства меток товара
                    "LAZY_LOAD" => "N",    // Показать кнопку ленивой загрузки Lazy Load
                    "LINE_ELEMENT_COUNT" => "3",    // Количество элементов выводимых в одной строке таблицы
                    "LOAD_ON_SCROLL" => "N",    // Подгружать товары при прокрутке до конца
                    "MESSAGE_404" => "",    // Сообщение для показа (по умолчанию из компонента)
                    "MESS_BTN_ADD_TO_BASKET" => "В корзину",    // Текст кнопки "Добавить в корзину"
                    "MESS_BTN_BUY" => "Купить",    // Текст кнопки "Купить"
                    "MESS_BTN_COMPARE" => "Сравнить",    // Текст кнопки "Сравнить"
                    "MESS_BTN_DETAIL" => "Подробнее",    // Текст кнопки "Подробнее"
                    "MESS_BTN_SUBSCRIBE" => "Подписаться",    // Текст кнопки "Уведомить о поступлении"
                    "MESS_NOT_AVAILABLE" => "Нет в наличии",    // Сообщение об отсутствии товара
                    "META_DESCRIPTION" => "-",    // Установить описание страницы из свойства
                    "META_KEYWORDS" => "-",    // Установить ключевые слова страницы из свойства
                    "OFFERS_FIELD_CODE" => array(    // Поля предложений
                        0 => "NAME",
                        1 => "",
                    ),
                    "OFFERS_LIMIT" => "5",
                    "OFFERS_SORT_FIELD" => "sort",    // По какому полю сортируем предложения товара
                    "OFFERS_SORT_FIELD2" => "id",    // Поле для второй сортировки предложений товара
                    "OFFERS_SORT_ORDER" => "asc",    // Порядок сортировки предложений товара
                    "OFFERS_SORT_ORDER2" => "desc",    // Порядок второй сортировки предложений товара
                    "OFFER_ADD_PICT_PROP" => "-",    // Дополнительные картинки предложения
                    "PAGER_BASE_LINK_ENABLE" => "N",    // Включить обработку ссылок
                    "PAGER_DESC_NUMBERING" => "N",    // Использовать обратную навигацию
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",    // Время кеширования страниц для обратной навигации
                    "PAGER_SHOW_ALL" => "N",    // Показывать ссылку "Все"
                    "PAGER_SHOW_ALWAYS" => "N",    // Выводить всегда
                    "PAGER_TEMPLATE" => ".default",    // Шаблон постраничной навигации
                    "PAGER_TITLE" => "Товары",    // Название категорий
                    "PAGE_ELEMENT_COUNT" => "30",    // Количество элементов на странице
                    "PARTIAL_PRODUCT_PROPERTIES" => "Y",    // Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
                    "PRICE_CODE" => array(    // Тип цены
                        0 => "BASE",
                    ),
                    "PRICE_VAT_INCLUDE" => "Y",    // Включать НДС в цену
                    "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",    // Порядок отображения блоков товара
                    "PRODUCT_DISPLAY_MODE" => "Y",    // Схема отображения
                    "PRODUCT_ID_VARIABLE" => "id",    // Название переменной, в которой передается код товара для покупки
                    "PRODUCT_PROPS_VARIABLE" => "prop",    // Название переменной, в которой передаются характеристики товара
                    "PRODUCT_QUANTITY_VARIABLE" => "quantity",    // Название переменной, в которой передается количество товара
                    "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",    // Вариант отображения товаров
                    "PRODUCT_SUBSCRIPTION" => "Y",    // Разрешить оповещения для отсутствующих товаров
                    "PROPERTY_CODE_MOBILE" => "",    // Свойства товаров, отображаемые на мобильных устройствах
                    "RCM_PROD_ID" => "",    // Параметр ID продукта (для товарных рекомендаций)
                    "RCM_TYPE" => "personal",    // Тип рекомендации
                    "SECTION_CODE" => "",    // Код раздела
                    "SECTION_ID" => "",    // ID раздела
                    "SECTION_ID_VARIABLE" => "SECTION_ID",    // Название переменной, в которой передается код группы
                    "SECTION_URL" => "",    // URL, ведущий на страницу с содержимым раздела
                    "SECTION_USER_FIELDS" => array(    // Свойства раздела
                        0 => "",
                        1 => "",
                    ),
                    "SEF_MODE" => "N",    // Включить поддержку ЧПУ
                    "SET_BROWSER_TITLE" => "N",    // Устанавливать заголовок окна браузера
                    "SET_LAST_MODIFIED" => "N",    // Устанавливать в заголовках ответа время модификации страницы
                    "SET_META_DESCRIPTION" => "N",    // Устанавливать описание страницы
                    "SET_META_KEYWORDS" => "N",    // Устанавливать ключевые слова страницы
                    "SET_STATUS_404" => "N",    // Устанавливать статус 404
                    "SET_TITLE" => "N",    // Устанавливать заголовок страницы
                    "SHOW_404" => "N",    // Показ специальной страницы
                    "SHOW_ALL_WO_SECTION" => "Y",    // Показывать все элементы, если не указан раздел
                    "SHOW_CLOSE_POPUP" => "N",    // Показывать кнопку продолжения покупок во всплывающих окнах
                    "SHOW_DISCOUNT_PERCENT" => "Y",    // Показывать процент скидки
                    "SHOW_FROM_SECTION" => "Y",    // Показывать товары из раздела
                    "SHOW_MAX_QUANTITY" => "N",    // Показывать остаток товара
                    "SHOW_OLD_PRICE" => "Y",    // Показывать старую цену
                    "SHOW_PRICE_COUNT" => "1",    // Выводить цены для количества
                    "SHOW_SLIDER" => "N",    // Показывать слайдер для товаров
                    "SLIDER_INTERVAL" => "3000",
                    "SLIDER_PROGRESS" => "N",
                    "TEMPLATE_THEME" => "blue",    // Цветовая тема
                    "USE_ENHANCED_ECOMMERCE" => "N",    // Отправлять данные электронной торговли в Google и Яндекс
                    "USE_MAIN_ELEMENT_SECTION" => "N",    // Использовать основной раздел для показа элемента
                    "USE_PRICE_COUNT" => "N",    // Использовать вывод цен с диапазонами
                    "USE_PRODUCT_QUANTITY" => "N",    // Разрешить указание количества товара
                ),
                    false
                ); ?>
            </div>
        <? endif; ?>

    </div>
</div>


<?
if ($USER->IsAuthorized()):

    global $AVERAGE_RATING;

    $AVERAGE_RATING = round($AVERAGE_RATING);
    $sort_comment = new sortConfig('COMMENT_SORT');
    $sort_name = $sort_comment->getSortConfig()['TEMPLATE_ID'];
    if (isset($_REQUEST[$sort_name]) && !empty($_REQUEST[$sort_name])) {
        $sort_comment->setSessionSortConfig($sort_name, 'ACTIVE_FROM', $_REQUEST[$sort_name]);
    }
    $sort_com = $sort_comment->getSortConfig()['SESSION'][$sort_name] ? $sort_comment->getSortConfig()['SESSION'][$sort_name] : $sort_comment->getSortConfig()['DEFAULT'];

    ?>
    <div class="reviews" id="reviews">
        <div class="container">
            <div class="reviews__title">Отзывы:</div>
            <div class="reviews__header">

                <div class="reviews__average-rate">
                    Средний рейтинг <?= $AVERAGE_RATING ?> из 5
                </div>

                <div class="reviews__views-wrap">
                    <div class="reviews__views-rate">
                        <div class="bx-rating">
                            <? for ($i = 1; $i <= 5; $i++):
                                if ($i <= $AVERAGE_RATING):
                                    $className = "fa fa-star reviews-prod";
                                else:
                                    $className = "fa fa-star-o reviews-prod";
                                endif; ?>
                                <i class="<? echo $className ?>"
                                   title="<? echo $name ?>">
                                </i>
                            <? endfor; ?>
                        </div>
                        <span>5 отзывов</span>
                    </div>

                    <div class="reviews__views-view">
                        <span class="product__view-watched-ico"></span>
                        <div style="display: inline-block">0</div>&nbsp;просмотров
                    </div>
                </div>

                <div class="custom-select-wrap reviews__select reviews__select--desktop">
                    <span class="category-filter__sort-title">Сортировать по:</span>
                    <select name="<?= $sort_name ?>" class="js_custom-filter-select">
                        <? foreach ($sort_comment->getSortConfig()['SORT'] as $key => $sort): ?>
                            <option <?= $sort_com['CODE'] == $sort['CODE'] && $sort_com['VALUE'] == $sort['VALUE'] ? 'selected' : '' ?>
                                    value="<?= $sort['VALUE'] ?>"><?= $sort['NAME'] ?></option>
                        <? endforeach; ?>
                    </select>
                </div>

                <button class="reviews__send-review js_reviews__send-review btn">НАПИСАТЬ свой отзыв</button>
            </div>


            <? $APPLICATION->IncludeComponent(
                "bh:feedback.form",
                "comment_form",
                array(
                    "PROD_ID" => $elementId,
                    "ACTIVE_ELEMENT" => "N",
                    "ADD_HREF_LINK" => "Y",
                    "ADD_LEAD" => "N",
                    "ALX_LINK_POPUP" => "N",
                    "BBC_MAIL" => "",
                    "CATEGORY_SELECT_NAME" => "Выберите категорию",
                    "CHECKBOX_TYPE" => "CHECKBOX",
                    "CHECK_ERROR" => "N",
                    "COLOR_SCHEME" => "BRIGHT",
                    "EVENT_TYPE" => "ALX_FEEDBACK_FORM",
                    "FB_TEXT_NAME" => "",
                    "FB_TEXT_SOURCE" => "PREVIEW_TEXT",
                    "FORM_ID" => "FORM_COMMENT",
                    "IBLOCK_ID" => "6",
                    "IBLOCK_TYPE" => "altasib_feedback",
                    "INPUT_APPEARENCE" => array(
                        0 => "DEFAULT",
                    ),
                    "JQUERY_EN" => "jquery",
                    "LINK_SEND_MORE_TEXT" => "Отправить ещё одно сообщение",
                    "LOCAL_REDIRECT_ENABLE" => "N",
                    "MASKED_INPUT_PHONE" => array(
                        0 => "PHONE",
                    ),
                    "MESSAGE_OK" => "Ваше сообщение было успешно отправлено",
                    "NAME_ELEMENT" => "ALX_DATE",
                    "PROPERTY_FIELDS" => array(
                        0 => "AGE",
                        1 => "SKIN_TYPE",
                        2 => "HAIR_COLOR",
                        3 => "EYE_COLOR",
                        4 => "FEEDBACK_TEXT",
                    ),
                    "PROPERTY_FIELDS_REQUIRED" => array(
                        0 => "SKIN_TYPE",
                        1 => "HAIR_COLOR",
                        2 => "EYE_COLOR",
                        3 => "FEEDBACK_TEXT",
                    ),
                    "PROPS_AUTOCOMPLETE_EMAIL" => array(
                        0 => "EMAIL",
                    ),
                    "PROPS_AUTOCOMPLETE_NAME" => array(
                        0 => "FIO",
                    ),
                    "PROPS_AUTOCOMPLETE_PERSONAL_PHONE" => array(
                        0 => "PHONE",
                    ),
                    "PROPS_AUTOCOMPLETE_VETO" => "N",
                    "REQUIRED_SECTION" => "N",
                    "SECTION_FIELDS_ENABLE" => "N",
                    "SECTION_MAIL_ALL" => "",
                    "SEND_IMMEDIATE" => "Y",
                    "SEND_MAIL" => "N",
                    "SHOW_LINK_TO_SEND_MORE" => "Y",
                    "SHOW_MESSAGE_LINK" => "Y",
                    "SPEC_CHAR" => "N",
                    "USERMAIL_FROM" => "N",
                    "USER_CONSENT" => "N",
                    "USER_CONSENT_ID" => "0",
                    "USER_CONSENT_INPUT_LABEL" => "",
                    "USER_CONSENT_IS_CHECKED" => "Y",
                    "USER_CONSENT_IS_LOADED" => "N",
                    "USE_CAPTCHA" => "N",
                    "WIDTH_FORM" => "50%",
                    "COMPONENT_TEMPLATE" => "comment_form",
                    "COLOR_THEME" => "",
                    "COLOR_OTHER" => "#009688"
                ),
                false
            ); ?>


            <div class="custom-select-wrap reviews__select reviews__select--mobile">
                <span class="category-filter__sort-title">Сортировать по:</span>
                <select name="<?= $sort_name ?>" class="js_custom-filter-select">
                    <? foreach ($sort_comment->getSortConfig()['SORT'] as $key => $sort): ?>
                        <option <?= $sort_com['CODE'] == $sort['CODE'] && $sort_com['VALUE'] == $sort['VALUE'] ? 'selected' : '' ?>
                                value="<?= $sort['VALUE'] ?>"><?= $sort['NAME'] ?></option>
                    <? endforeach; ?>
                </select>
            </div>


            <?
            global $filter_comment;
            $filter_comment = array(
                'PROPERTY_PROD_ID' => $elementId
            );


            $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "comment_prod",
                array(
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "ADD_SECTIONS_CHAIN" => "Y",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "N",
                    "CACHE_TIME" => "3600",
                    "CACHE_TYPE" => "A",
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "DISPLAY_BOTTOM_PAGER" => "Y",
                    "DISPLAY_DATE" => "Y",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "DISPLAY_TOP_PAGER" => "N",
                    "FIELD_CODE" => array(
                        0 => "DATE_ACTIVE_FROM",
                        1 => "",
                    ),
                    "FILTER_NAME" => "filter_comment",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "IBLOCK_ID" => "6",
                    "IBLOCK_TYPE" => "altasib_feedback",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "MESSAGE_404" => "",
                    "NEWS_COUNT" => "5",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "PAGER_TITLE" => "Новости",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "PROPERTY_CODE" => array(
                        0 => "AGE",
                        1 => "USER_ID",
                        2 => "SKIN_TYPE",
                        3 => "HAIR_COLOR",
                        4 => "EYE_COLOR",
                        5 => "",
                    ),
                    "SET_BROWSER_TITLE" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_STATUS_404" => "N",
                    "SET_TITLE" => "N",
                    "SHOW_404" => "N",
                    "SORT_BY1" => $sort_com['CODE'],
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER1" => $sort_com['VALUE'],
                    "SORT_ORDER2" => "DESC",
                    "STRICT_SECTION_CHECK" => "N",
                    "COMPONENT_TEMPLATE" => "comment_prod"
                ),
                false
            ); ?>

        </div>
    </div>
<? endif; ?>



<div data-retailrocket-markup-block="5dcc086097a528188c0bc1ee" ></div>


<div>


    <script type="text/javascript">
        $(document).ready(function () {
            var count_prosmotrov = $('.product__view-watched div').text();
            var count_rating = $('.js_scroll-to').text();
            $('.reviews__views-view div').text(count_prosmotrov);
            $('.reviews__views-rate span').text(count_rating);

            var i = 1;
            $('.tabs span').each(function () {
                if (i == 1) {
                    $(this).trigger("click");
                    i++;
                }
            });
        });
    </script>
