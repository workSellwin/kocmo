<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="aside">
    <? if (!empty($arResult)) : ?>
        <? $arResult = getChilds($arResult); ?>
        <div class="aside-nav-wrap js_aside-nav-wrap">
            <? foreach ($arResult as $arItem): ?>
                <nav class="aside-nav js_aside-nav">
                    <div class="aside-nav__title js_aside-nav__title"><?= $arItem["TEXT"] ?></div>
                    <? if ($arItem['CHILD']): ?>
                        <ul class="aside-nav__inner js_aside-nav__inner">
                            <? foreach ($arItem['CHILD'] as $Item): ?>
                                <? if ($Item["SELECTED"] ):?>
                                    <li class="aside-nav__list">
                                        <a class="aside-nav__lnk aside-nav__lnk--active"><?= $Item["TEXT"] ?></a>
                                    </li>
                                <? else : ?>
                                    <li class="aside-nav__list">
                                        <a href="<?= $Item["LINK"] ?>" class="aside-nav__lnk"><?= $Item["TEXT"] ?></a>
                                    </li>
                                <? endif; ?>

                            <? endforeach; ?>
                        </ul>
                    <? endif; ?>
                </nav>
            <? endforeach; ?>
        </div>
    <? endif; ?>
</div>
