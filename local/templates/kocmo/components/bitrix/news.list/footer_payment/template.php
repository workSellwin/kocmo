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
<div class="footer-bottom">
    <div class="container footer-bottom__inner">
        <span class="footer__bottom-payment-txt">способы оплаты:</span>
        <div class="footer-bottom__img-wrap">
        <? foreach ($arResult["ITEMS"] as $arItem) { ?>
            <?
            $prop = array_column($arItem['PROPERTIES'], '~VALUE', 'CODE');
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <span
                    id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
                    class="footer-bottom__payment-img">
                <img
                        src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                        alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                        title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>">
            </span>
        <? } ?>
        </div>
    </div>
</div>
<? } ?>
