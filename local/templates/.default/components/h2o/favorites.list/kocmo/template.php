<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->createFrame()->begin("");
?>
<div class="products ajax-h2ofavorites-list">
    <div class="container">
        <?if($arResult["FAVORITES"]){?>
            <?$arId=array_column($arResult["FAVORITES"],'ELEMENT_ID')?>
            <? global $arrFilterTab;
            $arrFilterTab['ID'] = $arId;
            $APPLICATION->IncludeComponent(
                "bitrix:catalog.section",
                "section",
                array(
                    "ACTION_VARIABLE" => "action",
                    "ADD_PICT_PROP" => "MORE_PHOTO",
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
                    "ELEMENT_SORT_FIELD" => "sort",
                    "ELEMENT_SORT_FIELD2" => "id",
                    "ELEMENT_SORT_ORDER" => "asc",
                    "ELEMENT_SORT_ORDER2" => "desc",
                    "ENLARGE_PRODUCT" => "STRICT",
                    "FILTER_NAME" => "arrFilterTab",
                    "HIDE_NOT_AVAILABLE" => "N",
                    "HIDE_NOT_AVAILABLE_OFFERS" => "N",
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
                    "PAGE_ELEMENT_COUNT" => "18",
                    "PARTIAL_PRODUCT_PROPERTIES" => "Y",
                    "PRICE_CODE" => array(),
                    "PRICE_VAT_INCLUDE" => "Y",
                    "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
                    "PRODUCT_DISPLAY_MODE" => "Y",
                    "PRODUCT_ID_VARIABLE" => "id",
                    "PRODUCT_PROPS_VARIABLE" => "prop",
                    "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                    "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
                    "PRODUCT_SUBSCRIPTION" => "Y",
                    "PROPERTY_CODE_MOBILE" => array(),
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
                    "COMPONENT_TEMPLATE" => "main-tabs-sliders"
                ),
                false
            ); ?>
            <?/*PR($arId);?>
            <div class="preloader-wrap">
                <div class="products__container">
                    <!-- отличается от продуктов в каталоге наличием кнопки удаления в мобилке -->
                    <div class="products-item">
                        <a href="#" class="products-item__img-wrap">
                            <!-- MainJs.productsItemRemoveInit --> <span class="products-item__remove js_products-item__remove"></span>
                            <div class="products-item__labels">
                                <div class="products-item__label products-item__label--sale">
                                    -20%
                                </div>
                                <div class="products-item__label products-item__label--new">
                                    new
                                </div>
                                <div class="products-item__label products-item__label--bestseller">
                                    БЕСТСЕЛЛЕР
                                </div>
                                <div class="products-item__label--add">
                                </div>
                            </div>
                            <!-- 290px x 226px --> <img width="290" src="assets/images/temp/product.jpg" height="226" class="products-item__img" alt=""> </a>
                        <div class="products-item__title-wrap">
                            <a href="#" class="products-item__title">Real Skin</a> <a href="#" class="products-item__options">15 вариантов</a>
                        </div>
                        <div class="products-item__description">
                            Бальзам Sun Care Family для приема после душа загара, можете нанести сразу три этапа. 250 мл.
                        </div>
                        <div class="products-item__reviews">
                            <div class="products-item__stars">
                                <img src="assets/images/temp/stars.png" alt="">
                            </div>
                            <a href="#" class="products-item__reviews-lnk">2 отзыва</a>
                        </div>
                        <div class="products-item__price-wrap">
                            <div class="products-item__price">
                                23,90 руб
                            </div>
                            <div class="products-item__old-price">
                                23,90 руб
                            </div>
                        </div>
                        <div class="products-item__btns">
                            <a href="#" class="btn btn--transparent products-item__add">
                                В корзину </a> <a href="#" class="btn btn--transparent products-item__wishlist products-item__wishlist--active"> </a>
                        </div>
                    </div>
                    <div class="products-item">
                        <a href="#" class="products-item__img-wrap">
                            <!-- MainJs.productsItemRemoveInit --> <span class="products-item__remove js_products-item__remove"></span>
                            <div class="products-item__labels">
                                <div class="products-item__label products-item__label--sale">
                                    -50%
                                </div>
                            </div>
                            <img width="290" src="assets/images/temp/product-2.jpg" height="226" class="products-item__img" alt=""> </a>
                        <div class="products-item__title-wrap">
                            <a href="#" class="products-item__title">Krygina</a> <a href="#" class="products-item__options">2 варианта</a>
                        </div>
                        <div class="products-item__description">
                            Маска карбонатная, глиняная для очистки от черных точек, 500 мл.
                        </div>
                        <div class="products-item__reviews">
                            <div class="products-item__stars">
                                <img src="assets/images/temp/stars.png" alt="">
                            </div>
                            <a href="#" class="products-item__reviews-lnk">0 отзывов</a>
                        </div>
                        <div class="products-item__price-wrap">
                            <div class="products-item__price">
                                4,90 руб
                            </div>
                        </div>
                        <div class="products-item__btns">
                            <a href="#" class="btn btn--transparent products-item__add">
                                В корзину </a> <a href="#" class="btn btn--transparent products-item__wishlist products-item__wishlist--active"> </a>
                        </div>
                    </div>
                    <div class="products-item">
                        <a href="#" class="products-item__img-wrap">
                            <!-- MainJs.productsItemRemoveInit --> <span class="products-item__remove js_products-item__remove"></span>
                            <div class="products-item__labels">
                                <div class="products-item__label products-item__label--new">
                                    new
                                </div>
                            </div>
                            <img width="290" src="assets/images/temp/product-3.jpg" height="226" class="products-item__img" alt=""> </a>
                        <div class="products-item__title-wrap">
                            <a href="#" class="products-item__title">Elizavecca</a> <a href="#" class="products-item__options">22 варианта</a>
                        </div>
                        <div class="products-item__description">
                            Бальзам Sun Care Family после душа загара, можете нанести сразу три этапа. 250 мл. 250 мл.
                        </div>
                        <div class="products-item__reviews">
                            <div class="products-item__stars">
                                <img src="assets/images/temp/stars.png" alt="">
                            </div>
                            <a href="#" class="products-item__reviews-lnk">3 отзыва</a>
                        </div>
                        <div class="products-item__price-wrap">
                            <div class="products-item__price">
                                44,00 руб
                            </div>
                            <div class="products-item__old-price">
                                52,80 руб
                            </div>
                        </div>
                        <div class="products-item__btns">
                            <a href="#" class="btn btn--transparent products-item__add">
                                В корзину </a> <a href="#" class="btn btn--transparent products-item__wishlist products-item__wishlist--active"> </a>
                        </div>
                    </div>
                    <div class="products-item">
                        <a href="#" class="products-item__img-wrap">
                            <!-- MainJs.productsItemRemoveInit --> <span class="products-item__remove js_products-item__remove"></span>
                            <div class="products-item__labels">
                            </div>
                            <img width="290" src="assets/images/temp/product.jpg" height="226" class="products-item__img" alt=""> </a>
                        <div class="products-item__title-wrap">
                            <a href="#" class="products-item__title">Очень длинное название</a> <a href="#" class="products-item__options">33 варианта</a>
                        </div>
                        <div class="products-item__description">
                            Маска карбонатная, глиняная для очистки от черных точек, 500 мл.
                        </div>
                        <div class="products-item__reviews">
                            <div class="products-item__stars">
                                <img src="assets/images/temp/stars.png" alt="">
                            </div>
                            <a href="#" class="products-item__reviews-lnk">75 отзывов</a>
                        </div>
                        <div class="products-item__price-wrap">
                            <div class="products-item__price">
                                54,90 руб
                            </div>
                        </div>
                        <div class="products-item__btns">
                            <a href="#" class="btn btn--transparent products-item__add">
                                В корзину </a> <a href="#" class="btn btn--transparent products-item__wishlist products-item__wishlist--active"> </a>
                        </div>
                    </div>
                </div>
                <div class="preloader" style="display: none;">
                </div>
            </div>*/?>
        <?}else{?>
            <?=GetMessage("H2O_FAVORITES_EMPTY_LIST");?>
        <?}?>
    </div>
</div>
