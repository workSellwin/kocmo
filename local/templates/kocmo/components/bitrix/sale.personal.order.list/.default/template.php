<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Bitrix\Sale;
//pr($arResult['ORDERS'][0]['ORDER']['URL_TO_COPY']);
//$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
//pr( $basket->getOrderableItems() );

//pr($arResult);
?>
<div class="cabinet-orders container">

    <div class="cabinet-orders__header">
        <div class="cabinet-orders__header-number">Номер заказа</div>
        <div class="cabinet-orders__header-date">Дата</div>
        <div class="cabinet-orders__header-date-sum">Сумма</div>
    </div>

    <div class="cabinet-orders__inner">
        <?foreach ($arResult['ORDERS'] as $arOrder):?>

        <div class="cabinet-orders__item">
            <div class="cabinet-orders__item-header js_cabinet-orders__item-header">
                <div class="cabinet-orders__item-header-left">
                    <div class="cabinet-orders__item-number">№ <?=$arOrder['ORDER']['ID'];?></div>
                    <div class="cabinet-orders__item-date"><?= $arOrder['ORDER']['DATE_INSERT']->format("d.m.y"); ?></div>
                </div>

                <div class="cabinet-orders__item-sum"><?= round($arOrder['ORDER']['PRICE'], 2);?> <span>руб</span></div>
            </div>
            <div class="cabinet-orders__item-content" style="display: none;">
                <?foreach($arOrder['BASKET_ITEMS'] as $basketItem):?>
                <?
                    $arItem = $arResult['ITEMS'][$basketItem['PRODUCT_ID']];
                 ?>
                <div class="cabinet-orders__item-product">
                    <div class="cabinet-orders__item-img">
                        <!-- 183x183 -->
                        <img src="<?= CFile::GetPath( $arItem['PREVIEW_PICTURE']); ?>" alt="">
                    </div>
                    <div class="cabinet-orders__item-info">
                        <div class="cabinet-orders__item-brand"><?= $basketItem['NAME'] ?></div>

                        <div class="cabinet-orders__item-description">
                            <?= !empty($arItem['PREVIEW_TEXT']) ? $arItem['PREVIEW_TEXT'] : $arItem['DETAIL_TEXT']; ?>
                        </div>

                        <div class="cabinet-orders__item-sku">Артикул: <?=$arItem['PROPERTY_ARTIKUL_VALUE'];?></div>

<!--                        <div class="cabinet-orders__item-color">светло-бежевый 102</div>-->
                    </div>
                    <div class="cabinet-orders__item-total">
                        <div class="cabinet-orders__item-quantity"><?= $basketItem['QUANTITY'] ?> шт.</div>
                        <div class="cabinet-orders__item-price"><?= round($basketItem['PRICE'], 2) ?> <span>руб</span></div>
                    </div>
                </div>
                <?endforeach;?>

                <div class="cabinet-orders__item-footer">
                    <a href="<?= $arOrder['ORDER']['URL_TO_COPY']?>" class="cabinet-orders__item-btn btn btn--transparent">повторить заказ</a>
                </div>
            </div>
        </div>
        <?endforeach;?>
    </div>
</div>