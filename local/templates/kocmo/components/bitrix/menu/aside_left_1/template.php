<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)) { ?>
    <nav class="aside-nav js_aside-nav">
        <div class="aside-nav__title js_aside-nav__title"><?= $arParams['NAME'] ?></div>
        <ul class="aside-nav__inner js_aside-nav__inner">
            <?
            foreach ($arResult as $arItem) {
                if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;
                $class = $arItem["SELECTED"] ? 'aside-nav__lnk--active' : '';
                ?>
                <li class="aside-nav__list">
                    <a href="<?= $arItem["LINK"] ?>" class="aside-nav__lnk <?= $class ?>"><?= $arItem["TEXT"] ?></a>
                    <? if ($arItem['SUB']) { ?>
                        <ul class="pl30 aside-nav__inner js_aside-nav__inner">
                            <? foreach ($arItem['SUB'] as $sub) { ?>
                                <li class="aside-nav__list">
                                    <a href="<?= $sub["SECTION_PAGE_URL"] ?>" class="aside-nav__lnk "><?= $sub["NAME"] ?></a>
                                </li>
                            <? } ?>
                        </ul>
                    <? } ?>
                </li>
            <? } ?>
        </ul>
    </nav>
<? } ?>

