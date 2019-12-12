<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
$this->setFrameMode(true);
$file = CFile::ResizeImageGet($arResult['DETAIL_PICTURE']['ID'], array('width' => 1041, 'height' => 360), BX_RESIZE_IMAGE_PROPORTIONAL, true);
$date = explode(' ', $arResult['DATE_ACTIVE_FROM']);
$PROP = array_column($arResult['PROPERTIES'], 'VALUE', 'CODE');
?>


<div class="main-content">
    <div class="sale__img bordered-inner">
        <img src="<?= $file['src'] ?>" alt="">
    </div>

    <div class="sale__date">
        <?= $PROP['TEXT'] ?>
    </div>

    <div class="sale__article">
        <?= $arResult['DETAIL_TEXT'] ?>
    </div>

    <h2 class="sale__title">товары на акции</h2>

    <? global $filter_sales_prod;
    $APPLICATION->IncludeComponent(
        "bh:smart_filter_sale",
        ".default",
        array(
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => "2",
            "FILTER_NAME" => "filter_sales_prod",
            "PRICE_CODE" => array(
                0 => "BASE",
            ),
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_GROUPS" => "N",
            "SAVE_IN_SESSION" => "N",
            "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
            "XML_EXPORT" => "N",
            "SECTION_TITLE" => "NAME",
            "SECTION_DESCRIPTION" => "DESCRIPTION",
            "HIDE_NOT_AVAILABLE" => "N",
            "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
            "CONVERT_CURRENCY" => "N",
            "CURRENCY_ID" => $arParams["CURRENCY_ID"],
            "SEF_MODE" => "N",
            "SEF_RULE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["smart_filter"],
            "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
            "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
            "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
            "PROD_SALE_ID" => $PROP["PROD"],
            "COMPONENT_TEMPLATE" => ".default",
            "SECTION_ID" => $_REQUEST["SECTION_ID"],
            "SECTION_CODE" => "",
            "DISPLAY_ELEMENT_COUNT" => "Y",
            "SHOW_ALL_WO_SECTION" => "Y",
        ),
        false,
        array(
            "HIDE_ICONS" => "N"
        )
    ); ?>




    <div id="AJAX_FILTER_CONTAINER">
        <?
        if (isset($_REQUEST['ajax_filter']) && $_REQUEST['ajax_filter'] == 'Y') {
            $GLOBALS['APPLICATION']->RestartBuffer();
        }



        if ($PROP['PROD']) {
            $ob = new \Lui\Kocmo\BD\HB\ActionItems();
            $ob2 = new \Lui\Kocmo\BD\HB\ActionsList();
            $arId = $ob2->GetXml($PROP['PROD']);
            $link = false;
            if ($arId) {
                $link = reset($arId);
                $link = $link['ID'];
            }
            if ($link) {
                $arData = $ob->GetLink($link);
                $arIdEl=array_column($arData,'UF_LINK');
                $filter_sales_prod = array_merge($filter_sales_prod, array('ID' => $arIdEl));
            }

        }

        if(isset($_REQUEST['available_not']) && $_REQUEST['available_not'] == 'y'){
            $filter_sales_prod['CATALOG_AVAILABLE'] = 'N';
        }

        if(isset($_REQUEST['available_yes']) && $_REQUEST['available_yes'] == 'y'){
            $filter_sales_prod['CATALOG_AVAILABLE'] = 'Y';
        }

        if((isset($_REQUEST['available_not']) && $_REQUEST['available_not'] == 'y') && (isset($_REQUEST['available_yes']) && $_REQUEST['available_yes'] == 'y')){
            unset($filter_sales_prod['CATALOG_AVAILABLE']);
        }

        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "sales_prod",
            array(
                "ACTION_VARIABLE" => "action",
                "ADD_PICT_PROP" => "",
                "ADD_PROPERTIES_TO_BASKET" => "Y",
                "ADD_SECTIONS_CHAIN" => "N",
                "ADD_TO_BASKET_ACTION" => "ADD",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "N",
                "BACKGROUND_IMAGE" => "-",
                "BASKET_URL" => "/personal/basket.php",
                "BROWSER_TITLE" => "-",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "N",
                "CACHE_TIME" => "36000000",
                "CACHE_TYPE" => "A",
                "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
                "COMPARE_PATH" => "",
                "COMPATIBLE_MODE" => "N",
                "CONVERT_CURRENCY" => "Y",
                "CURRENCY_ID" => "BYN",
                "CUSTOM_FILTER" => "",
                "DETAIL_URL" => "",
                "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                "DISCOUNT_PERCENT_POSITION" => "bottom-right",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "DISPLAY_COMPARE" => "Y",
                "DISPLAY_TOP_PAGER" => "N",
                "ELEMENT_SORT_FIELD" => "name",
                "ELEMENT_SORT_FIELD2" => "name",
                "ELEMENT_SORT_ORDER" => isset($_REQUEST['filter_sort']) && $_REQUEST['filter_sort'] == 'sort_increase' ? "asc" : "desc",
                "ELEMENT_SORT_ORDER2" => isset($_REQUEST['filter_sort']) && $_REQUEST['filter_sort'] == 'sort_increase' ? "asc" : "desc",
                "ENLARGE_PRODUCT" => "STRICT",
                "FILTER_NAME" => "filter_sales_prod",
                "HIDE_NOT_AVAILABLE" => "L",
                "HIDE_NOT_AVAILABLE_OFFERS" => "Y",
                "IBLOCK_ID" => "2",
                "IBLOCK_TYPE" => "catalog",
                "INCLUDE_SUBSECTIONS" => "Y",
                "LABEL_PROP" => array(),
                "LAZY_LOAD" => "N",
                "LINE_ELEMENT_COUNT" => "3",
                "LOAD_ON_SCROLL" => "N",
                "MESSAGE_404" => "",
                "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                "MESS_BTN_BUY" => "Купить",
                "MESS_BTN_COMPARE" => "Сравнить",
                "MESS_BTN_DETAIL" => "Подробнее",
                "MESS_BTN_SUBSCRIBE" => "Подписаться",
                "MESS_NOT_AVAILABLE" => "Нет в наличии",
                "META_DESCRIPTION" => "-",
                "META_KEYWORDS" => "-",
                "OFFERS_FIELD_CODE" => array(
                    0 => "NAME",
                    1 => "",
                ),
                "OFFERS_LIMIT" => "5",
                "OFFERS_SORT_FIELD" => "sort",
                "OFFERS_SORT_FIELD2" => "id",
                "OFFERS_SORT_ORDER" => "asc",
                "OFFERS_SORT_ORDER2" => "desc",
                "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_TEMPLATE" => "main",
                "PAGER_TITLE" => "Товары",
                "PAGE_ELEMENT_COUNT" => "6",
                "PARTIAL_PRODUCT_PROPERTIES" => "Y",
                "PRICE_CODE" => array(
                    0 => "BASE",
                ),
                "PRICE_VAT_INCLUDE" => "Y",
                "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
                "PRODUCT_DISPLAY_MODE" => "Y",
                "PRODUCT_ID_VARIABLE" => "id",
                "PRODUCT_PROPS_VARIABLE" => "prop",
                "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
                "PRODUCT_SUBSCRIPTION" => "Y",
                "PROPERTY_CODE_MOBILE" => "",
                "RCM_PROD_ID" => "",
                "RCM_TYPE" => "personal",
                "SECTION_CODE" => "",
                "SECTION_ID" => "",
                "SECTION_ID_VARIABLE" => "SECTION_ID",
                "SECTION_URL" => "",
                "SECTION_USER_FIELDS" => array(
                    0 => "",
                    1 => "",
                ),
                "SEF_MODE" => "N",
                "SET_BROWSER_TITLE" => "N",
                "SET_LAST_MODIFIED" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_META_KEYWORDS" => "N",
                "SET_STATUS_404" => "N",
                "SET_TITLE" => "N",
                "SHOW_404" => "N",
                "SHOW_ALL_WO_SECTION" => "Y",
                "SHOW_CLOSE_POPUP" => "N",
                "SHOW_DISCOUNT_PERCENT" => "Y",
                "SHOW_FROM_SECTION" => "Y",
                "SHOW_MAX_QUANTITY" => "N",
                "SHOW_OLD_PRICE" => "Y",
                "SHOW_PRICE_COUNT" => "1",
                "SHOW_SLIDER" => "N",
                "SLIDER_INTERVAL" => "3000",
                "SLIDER_PROGRESS" => "N",
                "TEMPLATE_THEME" => "blue",
                "USE_ENHANCED_ECOMMERCE" => "N",
                "USE_MAIN_ELEMENT_SECTION" => "N",
                "USE_PRICE_COUNT" => "N",
                "USE_PRODUCT_QUANTITY" => "N",
                "COMPONENT_TEMPLATE" => "sales_prod"
            ),
            false
        ); ?>

        <? if (isset($_REQUEST['ajax_filter']) && $_REQUEST['ajax_filter'] == 'Y') {
            die();
        } ?>
    </div>

</div>


<script>
    $('body').on('click', '.js_my_btn_suggestions', function () {
        var count_page = +$('#AJAX_CONTAINER_SUGGESTIONS').attr('data-NavPageCount');
        var page = +$('#AJAX_CONTAINER_SUGGESTIONS').attr('data-Page');
        if (page <= count_page) {

            let url = "<?=$APPLICATION->GetCurPageParam(); ?>";

            if (/\/\?/.test(url)) {
                url = '<?=$APPLICATION->GetCurPageParam(); ?>&PAGEN_2=' + (page + 1);
            } else {
                url = '<?=$APPLICATION->GetCurPageParam(); ?>?PAGEN_2=' + (page + 1);
            }

            $.post(
                url,
                {
                    ajax_suggestions: "Y",
                },
                onAjaxSuccess
            );

            function onAjaxSuccess(data) {
                var $preloader = $('#AJAX_FILTER_CONTAINER');
                $preloader.addClass('preloader--active');
                $('#AJAX_CONTAINER_SUGGESTIONS .products__container').append(data);
                $('#AJAX_CONTAINER_SUGGESTIONS').attr('data-Page', (page + 1));

                $preloader.removeClass('preloader--active');

                if ($('#AJAX_CONTAINER_SUGGESTIONS').attr('data-Page') >= count_page) {
                    $('.js_my_btn_suggestions').remove();
                }
            }
        }
    });
</script>

<? $this->SetViewTarget('DOP_CLASS_CONTAINER'); ?>
inner-with-aside
<? $this->EndViewTarget(); ?>
