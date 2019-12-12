<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)) { ?>
    <?$menu=array_shift($arResult); ?>
        <div class="footer-nav">
            <div class="footer-nav__title"><a href="<?= $menu['LINK'] ?>"><?= $menu['TEXT'] ?></a> </div>
            <ul class="footer-nav__list">
                <?
                foreach ($arResult as $arItem) {
                    if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;
                    ?>
                    <? if ($arItem["SELECTED"]) { ?>
                        <li class="footer-nav__item">
                            <a  class="footer-nav__lnk"><?= $arItem["TEXT"] ?></a>
                        </li>
                    <? } else { ?>
                        <li class="footer-nav__item">
                            <a href="<?= $arItem["LINK"] ?>" class="footer-nav__lnk"><?= $arItem["TEXT"] ?></a>
                        </li>
                    <? } ?>
                <? } ?>
            </ul>
        </div>
<? } ?>

