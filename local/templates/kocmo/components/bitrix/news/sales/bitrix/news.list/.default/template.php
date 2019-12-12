<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
<div class="main-content">
    <div class="two-columns-grid sales">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	$date = explode(' ', $arItem['DATE_ACTIVE_FROM']);
    $file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width'=>505, 'height'=>255), BX_RESIZE_IMAGE_PROPORTIONAL, true);
    $PROP = array_column($arItem['PROPERTIES'], 'VALUE', 'CODE');
    ?>

    <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="two-columns-grid__item sales__item">
        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="sales__item-img bordered-inner">
            <img src="<?=$file['src']?>" alt="">
        </a>
        <div class="sales__item-date">
            <?=$PROP['TEXT']?>
        </div>
        <div class="sales__item-description">
            <?=$arItem['PREVIEW_TEXT']?>
        </div>
    </div>

<?endforeach;?>
    </div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>

<?$this->SetViewTarget('DOP_CLASS_CONTAINER');?>
    inner-with-aside
<?$this->EndViewTarget();?>

