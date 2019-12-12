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
?>

    <? if ($file['src']): ?>
        <div class="news-inner__img">
            <img src="<?= $file['src'] ?>" alt="">
        </div>
    <? endif; ?>

    <? if ($date[0]): ?>
        <div class="news-inner__date">
            <?= $date[0] ?>
        </div>
    <? endif; ?>

    <h2 class="news-inner__title"><?=$arResult['NAME']?></h2>

    <div class="news-inner__article">

        <?=$arResult['DETAIL_TEXT']?>

    </div>

    <div class="news-inner__control">
        <div class="news-inner__control-item">
            <?if($arResult['navElement']['PREV']):?>
                <a href="<?=$arResult['navElement']['PREV']['DETAIL_PAGE_URL']?>" id="<?=$arResult['navElement']['PREV']['ID']?>" class="news-inner__control-prev">
                    <svg width="21" height="9">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-pagination-left"></use>
                    </svg>
                    <span>Предыдущая новость</span>
                </a>
                <div class="news-inner__control-date"><?=explode(' ', $arResult['navElement']['PREV']['DATE_ACTIVE_FROM'])[0]?></div>
                <div class="news-inner__control-item-description">
                    <?=$arResult['navElement']['PREV']['NAME']?>
                </div>
            <?endif;?>
        </div>

        <div class="news-inner__control-item">
            <?if($arResult['navElement']['NEXT']):?>
                <a href="<?=$arResult['navElement']['NEXT']['DETAIL_PAGE_URL']?>" id="<?=$arResult['navElement']['NEXT']['ID']?>" class="news-inner__control-next">
                    <span>Следующая новость</span>
                    <svg width="21" height="9">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-pagination-right"></use>
                    </svg>
                </a>
                <div class="news-inner__control-date"><?=explode(' ', $arResult['navElement']['NEXT']['DATE_ACTIVE_FROM'])[0]?></div>
                <div class="news-inner__control-item-description">
                    <?=$arResult['navElement']['NEXT']['NAME']?>
                </div>
            <?endif;?>
        </div>

    </div>
