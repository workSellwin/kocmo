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

$link = $arResult['PROPERTIES']['LINK'];
$arName = explode(' ', $arResult['NAME']);
$endName = implode(' ', array_slice($arName, -1));
$startName = implode(' ', array_slice($arName, 0,-1));
?>
<div class="about-us about-us__home">
    <div class="container">
        <div class="about-us__inner">
            <? if ($picture = $arResult['PREVIEW_PICTURE']) { ?>
                <div class="about-us__img">
                    <img src="<?= $picture['SRC'] ?>" width="720" height="486" alt="<?= $picture['ALT'] ?>"
                         title="<?= $picture['TITLE'] ?>">
                </div>
            <? } ?>
            <div class="about-us__info">
                <div class="about-us__title"><?= $startName ?> <span><?= $endName ?></span></div>
                <p>
                    <? echo $arResult['PREVIEW_TEXT'] ?>
                </p>
                <div class="about-us__footer">
                    <a href="<?= $link; ?>" class="about-us__footer-lnk">
                        Узнать больше
                        <svg width="21" height="8">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-right"></use>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
