<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$frame = $this->createFrame()->begin("");
if ($arResult['BANNERS']) {
    ?>
    <div class="triple-banner triple-banner--home">
        <div class="container">
            <div class="triple-banner__inner">
                <?
                foreach ($arResult['BANNERS_PROPERTIES'] as $arBanner) { ?>
                    <?
                    $img = $arBanner['IMAGE_ID'];
                    $src = CFile::GetPath($img);
                    ?>
                   <a href="<?=$arBanner['URL']?>" target="<?=$arBanner['URL_TARGET']?>" class="triple-banner__item bordered-inner <?=$arBanner['IMAGE_ALT']?>">
                        <img src="<?= $src ?>">
                    </a>
                    <?
                } ?>
            </div>
        </div>
    </div>
    <?
}
$frame->end();

