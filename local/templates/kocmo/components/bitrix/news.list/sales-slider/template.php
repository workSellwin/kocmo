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
?>
<? if ($arResult["ITEMS"]): ?>
<div class="sales-slider">
    <div class="container sales-slider__container">
        <div class="swiper-container sales-slider__swiper-container js_sales-slider__swiper-container">
            <div class="swiper-wrapper">
                <? foreach ($arResult["ITEMS"] as $arItem):?>
                    <?
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <div class="swiper-slide" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                        <img width="1047" height="444"
                             src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                             alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                             title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>">

                        <!-- возьмет html для боковой панели из содержимого этого блока -->
                        <div class="sales-slider__hide-info" style="display: none;">
                            <?=$arItem["PREVIEW_TEXT"]?>
                            <div class="sales-slider__footer">
                                <a href="<?=$arItem['PROPERTIES']['LINK']['VALUE']?>" class="sales-slider__footer-lnk">
                                    Узнать больше
                                    <svg width="21" height="8">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-right"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                <?endforeach;?>
            </div>
        </div>

        <div class="sales-slider__info">
            <div class="sales-slider__info-header">
                <div class="sales-slider__btns">
                    <div class="sales-slider__prev">
                        <svg width="22" height="22">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-angle-left"></use>
                        </svg>
                    </div>
                    <div class="sales-slider__next">
                        <svg width="22" height="22">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-angle-right"></use>
                        </svg>
                    </div>
                </div>

                <a href="#" class="sales-slider__info-header-lnk">Смотреть все акции</a>
            </div>

            <div class="sales-slider__info-content"></div>

        </div>
    </div>
</div>
<? endif ?>