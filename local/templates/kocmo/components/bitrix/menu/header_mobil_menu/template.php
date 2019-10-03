<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)) { ?>
    <ul class="mobile-nav-content">
        <?
        foreach ($arResult as $arItem) {
            if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;
            ?>
            <? if ($arItem["SELECTED"]) { ?>
                <li>
                    <a href="<?= $arItem["LINK"] ?>" class="mobile-nav__lnk active"><?= $arItem["TEXT"] ?></a>
                </li>
            <? } else { ?>
                <li>
                    <a href="<?= $arItem["LINK"] ?>" class="mobile-nav__lnk"><?= $arItem["TEXT"] ?></a>
                </li>
            <? } ?>
        <? } ?>
    </ul>
<? } ?>