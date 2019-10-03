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
    <div class="footer__soc">
        <? foreach ($arResult["ITEMS"] as $arItem) { ?>
            <?
            $prop = array_column($arItem['PROPERTIES'], '~VALUE', 'CODE');
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <a
                    href="<?= $prop['LINK'] ?>"
                    id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
                    class="footer__soc-item <?=$arItem["PREVIEW_TEXT"]?>"
            >
            </a>
        <? } ?>
    </div>
<? } ?>
