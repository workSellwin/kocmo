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
    <div class="mobile-nav__soc">
        <span>Мы в социальных сетях</span>
        <? foreach ($arResult["ITEMS"] as $arItem) { ?>
            <?
            $prop = array_column($arItem['PROPERTIES'], '~VALUE', 'CODE');
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <?if($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']):?>
                <a class="mobile-nav-soc__lnk"
                   id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
                   href="<?= $prop['LINK'] ?>">
                    <img src="<?=$arItem['FIELDS']['PREVIEW_PICTURE']['SRC']?>" alt="">
                </a>
            <?endif;?>
        <? } ?>
    </div>
<? } ?>