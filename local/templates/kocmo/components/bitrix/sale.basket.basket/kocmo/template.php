<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<? $data = $arResult; ?>
<? if ($data['COUNT']) { ?>
<form action="" method="post" id="bx-soa-order-form" enctype="multipart/form-data">
    <?= bitrix_sessid_post() ?>

    <? AjaxContent::Start('ajax_basket_item_container') ?>

    <div class="basket_item_container">
        <? if ($arItems = $data['ITEMS']) { ?>
            <? foreach ($arItems as $arItem) { ?>
                <div class="basket__item">
                    <div class="basket__item-img">
                        <? if ($arItem['PREVIEW_PICTURE_SRC']) { ?>
                            <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                <img src="<?= $arItem['PREVIEW_PICTURE_SRC'] ?>" alt="<?= $arItem['NAME'] ?>">
                            </a>
                        <? } ?>
                    </div>
                    <div class="basket__item-details-wrap">
                        <div class="basket__item-details">
                            <div class="basket__item-title">
                                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a>
                            </div>
                            <div class="basket__item-description"><?= $arItem['DESCRIPTION'] ?></div>
                            <div class="basket__item-sku-wrap">
                                <div class="basket__item-sku">Артикул: <?= $arItem['PROPERTY_ARTIKUL_VALUE'] ?></div>
                                <? if (false) { ?>
                                    <div class="basket__item-color">
                                        <div class="product__colors-item">
                                                <span class="product__colors-item-inner-border">
                                                    <img src="/assets/images/temp/product-colors/product-color-1.jpg"
                                                         alt="">
                                                </span>
                                        </div>
                                        светло-бежевый 102
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                        <div class="basket__item-counter counter js_click_span">
                            <span class="counter__button counter__button--down"></span>
                            <input
                                    type="text"
                                    class="counter__input js_change_input"
                                    value="<?= $arItem['QUANTITY'] ?>"
                                    data-max-count="<?= $arItem['AVAILABLE_QUANTITY'] ?>"
                                    data-id="<?= $arItem['PRODUCT_ID'] ?>"
                                    data-price="<?= $arItem['PRICE_OLD'] ?>"
                                    data-discount="<?= $arItem['PRICE_NEW'] ?>"
                            >
                            <span class="counter__button counter__button--up"></span>
                        </div>
                        <div class="basket__item-price-wrap">
                            <? if ($arItem['PRICE_OLD'] != $arItem['PRICE_NEW'] and $arItem['PRICE_OLD']) { ?>
                                <div class="basket__item-old-price">
                                    <?= number_format_kocmo($arItem['PRICE_OLD'] * $arItem['QUANTITY']); ?>
                                    <span>руб</span>
                                </div>
                            <? } ?>
                            <? if ($arItem['DISCOUNT']) { ?>
                                <div class="product__sale-wrap">
                                    <? foreach ($arItem['DISCOUNT'] as $arDiscount) { ?>
                                        <? if ($arDiscount['PERCENT']) { ?>
                                            <div class="product__sale-item">
                                                <div class="products-item__label products-item__label--sale">
                                                    -<?= $arDiscount['PERCENT'] ?>%
                                                </div>
                                                <?= $arDiscount['NAME'] ?>
                                            </div>
                                        <? } ?>
                                    <? } ?>
                                </div>
                            <? } ?>
                            <? if ($arItem['PRICE_NEW'] != $arItem['PRICE_OLD']) { ?>
                                <div class="basket__item-price"> <?= number_format_kocmo($arItem['PRICE_NEW'] * $arItem['QUANTITY']); ?>
                                    <span> руб</span></div>
                            <? } else { ?>
                                <div class="basket__item-price"><?= number_format_kocmo($arItem['BASE_PRICE'] * $arItem['QUANTITY']); ?>
                                    <span> руб</span>
                                </div>
                            <? } ?>
                        </div>
                        <!-- делай аякс в этом методе MainJs.basketItemCloseInit -->
                        <div class="basket__item-close js_basket__item-close"
                             onclick="BigBasketItemDel('<?= $arItem["PRODUCT_ID"] ?>', '<?= $arItem["ID"] ?>')"></div>
                    </div>
                </div>
            <? } ?>
        <? } ?>
    </div>

    <? AjaxContent::Finish('ajax_basket_item_container') ?>

    <? if ($arGift = $data['GIFT']) { ?>
        <div class="basket-gift container">
            <div class="basket-gift__title">
                <span class="basket-gift__title-logo">КОСМО</span> дарит <span
                        class="basket-gift__title-colored">Вам</span> подарок!
            </div>
            <div class="basket__item">
                <div class="basket__item-img">
                    <!-- 183 x 183 -->
                    <? if ($id = $arGift['DETAIL_PICTURE']) { ?>
                        <img src="<?= CFile::GetPath($id) ?>" alt="">
                    <? } ?>
                </div>
                <div class="basket__item-details-wrap">
                    <div class="basket__item-details">
                        <div class="basket__item-title"><?= $arGift['PROPERTY']['MARKA']; ?></div>
                        <div class="basket__item-description"><a
                                    href="<?= $arGift['DETAIL_PAGE_URL'] ?>"><?= $arGift['NAME']; ?></a></div>
                        <div class="basket__item-sku-wrap">
                            <div class="basket__item-sku">Артикул: <?= $arGift['PROPERTY']['ARTIKUL']; ?></div>
                            <? if (false) { ?>
                                <div class="basket__item-color">
                                    <div class="product__colors-item">
                                <span class="product__colors-item-inner-border">
                                    <img src="/assets/images/temp/product-colors/product-color-1.jpg" alt="">
                                </span>
                                    </div>
                                    светло-бежевый 102
                                </div>
                            <? } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>
    <? } ?>
    <? } ?>

    <script type="text/javascript">
        var obj_items = <?echo CUtil::PhpToJSObject($data['ITEMS'])?>;
        transactionTracker(obj_items);
    </script>
