<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)) { ?>
    <div class="cabinet-tabs">
        <div class="container">
            <ul class="cabinet-tabs__inner">
                <?
                foreach ($arResult as $arItem) {
                    if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;
                    ?>
                    <? if ($arItem["SELECTED"]) { ?>
                        <li class="cabinet-tabs__item">
                            <span class="cabinet-tabs__lnk"><?= $arItem["TEXT"] ?></span>
                        </li>
                    <? } else { ?>
                        <li class="cabinet-tabs__item">
                            <a href="<?= $arItem["LINK"] ?>" class="cabinet-tabs__lnk"><span><?= $arItem["TEXT"] ?></span></a>
                        </li>
                    <? } ?>
                <? } ?>
            </ul>
        </div>
    </div>
<? } ?>