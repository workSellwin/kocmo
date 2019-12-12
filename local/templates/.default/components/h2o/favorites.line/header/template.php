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
$this->createFrame()->begin("");
?>
<a href="<?=$arParams['URL'];?>" class="personality-state__item favor-list-wrap">
    <div class="personality-state__count count-favor-item"><?= $arResult['COUNT'] ? $arResult['COUNT'] : '0' ?></div>
    <svg width="25" height="25">
        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-wishlist"></use>
    </svg>
    Избранное
</a>


