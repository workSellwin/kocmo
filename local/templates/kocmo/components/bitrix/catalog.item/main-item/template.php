<?
use \Lui\Kocmo\Catalog;

$elemPrepara = new Catalog\ElementPrepara($arResult);

$file_img = CFile::ResizeImageGet($arResult['ITEM']['PREVIEW_PICTURE']['ID'], array('width'=>290, 'height'=>226), BX_RESIZE_IMAGE_PROPORTIONAL, true);
$minPriceOffer = $elemPrepara->getMinPriceOffers();
$PROP = $elemPrepara->getProp();
$countOffers = $elemPrepara->getCauntOffers();
?>

<div class="<?=$arResult['CLASS']?>">
    <a href="<?=$arResult['ITEM']['DETAIL_PAGE_URL']?>" class="products-item__img-wrap" style="width: 100%; height: 226px">
        <div class="products-item__labels">
            <?if($minPriceOffer['PERCENT']):?>
                <div class="products-item__label products-item__label--sale">-<?=$minPriceOffer['PERCENT']?>%</div>
            <?endif;?>
            <?if($PROP['NEWPRODUCT']):?>
                <div class="products-item__label products-item__label--new">new</div>
            <?endif;?>
            <div class="products-item__label--add" style="display: none">
                <svg width="44" height="25">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-label-add"></use>
                </svg>
            </div>
        </div>
        <!-- 290px x 226px -->
        <img src="<?=$file_img['src']?>" width="290" height="226" class="products-item__img"
             alt="">
    </a>
    <div class="products-item__title-wrap">
        <a href="<?=$arResult['ITEM']['DETAIL_PAGE_URL']?>" class="products-item__title"><?=$arResult['ITEM']['NAME']?></a>
        <a href="<?=$arResult['ITEM']['DETAIL_PAGE_URL']?>" class="products-item__options"><?=$countOffers?> вариантов</a>
    </div>
    <div class="products-item__description">
        <?=$arResult['ITEM']['PREVIEW_TEXT']?>
    </div>
    <div class="products-item__reviews">
        <div class="products-item__stars">
            <img src="assets/images/temp/stars.png" alt="">
        </div>
        <a href="#" class="products-item__reviews-lnk">2 отзыва</a>
    </div>
    <div class="products-item__price-wrap">
        <?if($minPriceOffer['DISCOUNT'] != 0):?>
            <div class="products-item__price"><?=number_format($minPriceOffer['BASE_PRICE'], 2, '.', ' ');?><span> руб</span></div>
            <div class="products-item__old-price"><?=number_format($minPriceOffer['PRICE'], 2, '.', ' ');?><span> руб</span></div>
        <?else:?>
            <div class="products-item__price"><?=number_format($minPriceOffer['PRICE'], 2, '.', ' ');?><span> руб</span></div>
        <?endif;?>

    </div>
    <div class="products-item__btns">
        <a href="#" class="btn btn--transparent products-item__add">
            <svg width="25" height="25">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-basket"></use>
            </svg>
            В корзину
        </a>
        <a href="#" class="btn btn--transparent products-item__wishlist">
            <svg width="25" height="25">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-heart"></use>
            </svg>
        </a>
    </div>
</div>