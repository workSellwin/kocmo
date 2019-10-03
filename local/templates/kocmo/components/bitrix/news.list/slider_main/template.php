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
<? if ($arResult["ITEMS"]) { ?>
    <div class="js_full-width-slider full-width-slider swiper-container swiper-container-no-flexbox">
        <div class="full-width-slider__wrapper swiper-wrapper">
            <? foreach ($arResult["ITEMS"] as $arItem) { ?>
                <?
                $prop=array_column($arItem['PROPERTIES'],'~VALUE','CODE');
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
               if(!$imgMobile=$arItem["DETAIL_PICTURE"]["SRC"]) {
                   $imgMobile=$arItem["PREVIEW_PICTURE"]["SRC"];
               }
                ?>
                <a href="<?=$prop['LINK']?>" id="<?= $this->GetEditAreaId($arItem['ID']); ?>" class="full-width-slider__lnk swiper-slide">
                    <img src="" alt=""
                         data-desktop-src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                         data-mobile-src="<?= $imgMobile ?>"
                         class="full-width-slider__img">
                </a>
            <? } ?>
        </div>
        <div class="full-width-slider__pagination swiper-pagination"></div>
        <div class="full-width-slider__prev swiper-button-prev"></div>
        <div class="full-width-slider__next swiper-button-next"></div>
    </div>
<? } ?>



