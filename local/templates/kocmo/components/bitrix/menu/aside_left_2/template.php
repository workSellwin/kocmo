<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)) { ?>
        <nav class="aside-nav js_aside-nav">
            <div class="aside-nav__title js_aside-nav__title"><?= $arParams['NAME'] ?></div>
            <ul class="aside-nav__inner js_aside-nav__inner">

                <?
                foreach ($arResult as $arItem) {
                    if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;
                    ?>
                    <? if ($arItem["SELECTED"]) { ?>
                        <li class="aside-nav__list">
                            <a  class="aside-nav__lnk aside-nav__lnk--active"><?= $arItem["TEXT"] ?></a>
                        </li>
                    <? } else { ?>
                        <li class="aside-nav__list">
                            <a href="<?= $arItem["LINK"] ?>" class="aside-nav__lnk"><?= $arItem["TEXT"] ?></a>
                        </li>
                    <? } ?>
                <? } ?>
            </ul>
        </nav>
<? } ?>

