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
$maxLIne = 10;
if(!file_exists('StartSubColumn')){
    function StartSubColumn($maxLIne, $thisLine)
    {
        if ($thisLine % $maxLIne == 0) echo "<div class=\"nav-dropdown__sub-column\">";
    }

    function EndSubColumn($maxLIne, $thisLine)
    {
        if ($thisLine % $maxLIne == 0) echo "</div>";
    }

    function EndFinishSubColumn($maxLIne, $thisLine)
    {
        if ($thisLine % $maxLIne != 0) echo "</div>";
    }
}


?>
<div class="header__bottom">
    <div class="container header__bottom-inner">
        <ul class="nav">
            <? foreach ($arResult as $arMenuL1) { ?>
                <? $active = $arMenuL1['SELECTED'] ? 'active' : ''; ?>
                <li class="nav__item <?= $active ?>">
                    <a href="<?= $arMenuL1['LINK'] ?>" class="nav__lnk"><?= $arMenuL1['TEXT'] ?></a>
                    <? if ($arMenuL1['CHILD']) { ?>
                        <? $thisLine = 0; ?>
                        <div class="nav-dropdown">
                            <div class="nav-dropdown__sub">
                                <? StartSubColumn($maxLIne, $thisLine);
                                $thisLine++; ?>
                                <? foreach ($arMenuL1['CHILD'] as $arMenuL2) { ?>
                                    <? StartSubColumn($maxLIne, $thisLine); ?>
                                    <div class="nav-dropdown__title"
                                         onclick="location.href='<?= $arMenuL2['LINK'] ?>'"><?= $arMenuL2['TEXT'] ?></div>
                                    <? $thisLine++; ?>
                                    <? EndSubColumn($maxLIne, $thisLine); ?>
                                    <? foreach ($arMenuL2['CHILD'] as $arMenuL3) { ?>
                                        <? StartSubColumn($maxLIne, $thisLine); ?>
                                        <a href="<?= $arMenuL3['LINK'] ?>"
                                           class="nav-dropdown__lnk"><?= $arMenuL3['TEXT'] ?></a>
                                        <? $thisLine++; ?>
                                        <? EndSubColumn($maxLIne, $thisLine); ?>
                                    <? } ?>
                                <? } ?>
                                <? EndFinishSubColumn($maxLIne, $thisLine); ?>
                            </div>
                            <? if ($arMenuL1['INFO']['PICTURE']) { ?>
                                <div class="nav-dropdown__img">
                                    <img src="<?= $arMenuL1['INFO']['PICTURE'] ?>" alt="<?= $arMenuL1['TEXT'] ?>">
                                </div>
                            <? } ?>
                            <? if ($arMenuL1['INFO']['BRAND']) { ?>
                                <div class="nav-dropdown__brands">
                                    <div class="nav-dropdown__title">Бренды <?= $arMenuL1['TEXT'] ?></div>
                                    <div class="nav-dropdown__brands-inner">
                                        <? foreach ($arMenuL1['INFO']['BRAND'] as $l => $brands) { ?>
                                            <div class="nav-dropdown__brands-letter"><?= $l ?></div>
                                            <div class="nav-dropdown__brands-group">
                                                <? foreach ($brands as $brand) { ?>
                                                    <a href="#"
                                                       class="nav-dropdown__brands-lnk"><?= $brand['NAME'] ?></a>
                                                <? } ?>
                                            </div>
                                        <? } ?>
                                    </div>
                                </div>
                            <? } ?>
                        </div>
                    <? } ?>
                </li>
            <? } ?>
        </ul>
    </div>
</div>
