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

    <div class="two-columns-grid news">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	$date = explode(' ', $arItem['DATE_ACTIVE_FROM']);
    $file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width'=>505, 'height'=>255), BX_RESIZE_IMAGE_PROPORTIONAL, true);
    ?>
    <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="two-columns-grid__item news__item">
        <?if($file['src']):?>
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="news__item-img">
                <img src="<?=$file['src']?>" alt="">
            </a>
        <?endif;?>
        <?if($date[0]):?>
            <div class="news__item-date">
                <?=$date[0]?>
            </div>
        <?endif;?>
        <?if($arItem['PREVIEW_TEXT']):?>
        <div class="news__item-description">
            <?=$arItem['PREVIEW_TEXT']?>
        </div>
        <?endif;?>
        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="news__item-more">
            Узнать больше
            <svg width="21" height="9">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-right"></use>
            </svg>
        </a>
    </div>
<?endforeach;?>
    </div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
