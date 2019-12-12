<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<? $data = $arResult; ?>
<div class="header-basket-wrap">

    <? AjaxContent::Start('header_basket_count') ?>
    <a href="/cart/"
       class="personality-state__item  personality-state__item--mobile-show">
        <? if ($data['COUNT']) { ?>
            <div class="personality-state__count"><?= $data['COUNT'] ?></div>
        <? } ?>
        <svg width="25" height="25">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-basket"></use>
        </svg>
        Корзина
    </a>
    <? AjaxContent::Finish('header_basket_count') ?>

    <div class="header-basket">
        <? $arItems = $data['ITEMS'] ?>
        <? AjaxContent::Start('header_basket_content') ?>
        <? if ($arItems && URL() != '/cart/') { ?>
            <div class="header-basket__inner">
                <div class="header-basket__header">
                    Товары <span>в вашей корзине</span>
                </div>
                <div class="header-basket__content-wrap">
                    <div class="header-basket__content js_header-basket__content">
                        <? foreach ($arItems as $arItem) { ?>
                            <?
                            $img= trim($arItem['DETAIL_PICTURE_SRC']) ? $arItem['DETAIL_PICTURE_SRC'] : $arItems['PREVIEW_PICTURE_SRC']
                            ?>
                            <div class="header-basket__item">
                                <div class="header-basket__item-img">
                                    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                        <!-- 135 x 135 -->
                                        <img src="<?= $img ?>" alt="<?= $arItem['NAME'] ?>">
                                    </a>
                                </div>
                                <div class="header-basket__item-details">
                                    <div class="header-basket__item-description">
                                        <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a>
                                    </div>
                                    <div class="header-basket__item-price">
                                        <?= number_format_kocmo($arItem['SUM_VALUE']) ?><span> руб</span>
                                    </div>
                                    <div class="header-basket__item-counter-wrap">
                                        Количество:
                                        <div class="header-basket__item-counter counter">
                                            <span class="counter__button counter__button--down"
                                                  onclick="clickPlusMinusCounterButton(this, <?= $arItem['PRODUCT_ID'] ?>)"></span>
                                            <input type="text"
                                                   onkeyup="keyupCounterButton(this, <?= $arItem['PRODUCT_ID'] ?>)"
                                                   class="counter__input js_counter__input count_product_id_<?= $arItem['PRODUCT_ID'] ?>"
                                                   value="<?= $arItem['QUANTITY'] ?>"
                                                   data-max-count="<?= $arItem['AVAILABLE_QUANTITY'] ?>"/>
                                            <span class="counter__button counter__button--up"
                                                  onclick="clickPlusMinusCounterButton(this, <?= $arItem['PRODUCT_ID'] ?>)"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="header-basket__item-close js_header-basket__item-close"
                                     onclick="productsItemDel('<?= $arItem['PRODUCT_ID'] ?>', '<?= $arItem['ID'] ?>')"></div>
                            </div>
                        <? } ?>
                    </div>
                </div>
                <? AjaxContent::Start('header_basket__footer') ?>
                <div class="header-basket__footer">
                    <div class="header-basket__total-wrap">
                        Заказ на сумму:&nbsp;
                        <div class="header-basket__total-price"><span><?= number_format_kocmo($data['SUM']) ?></span> руб</div>
                    </div>
                    <a href="/cart/" class="btn btn--transparent header-basket__lnk">перейти в корзину
                        <svg width="21" height="9">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                 xlink:href="#svg-pagination-right"></use>
                        </svg>
                    </a>
                </div>
                <? AjaxContent::Finish('header_basket__footer') ?>
            </div>
        <? } ?>
        <script>
            ReloadAjax();
        </script>
        <? AjaxContent::Finish('header_basket_content') ?>
    </div>
</div>
