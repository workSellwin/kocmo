<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true); ?>

<span>Мы в социальных сетях</span>

<? if (!empty($arResult["TV"])): ?>
    <a class="mobile-nav-soc__lnk" href="<?= $arResult["TV"] ?>">
        <img src="assets/images/mobile-soc/twit.png" alt="">
    </a>
<? endif; ?>


<? if (!empty($arResult["IN"])): ?>
    <a href="<?= $arResult["IN"] ?>" target="_blank"><img style="width: 40px; height: 40px" src="/local/templates/.default/images/Instagram.png" alt=""></a>
<? endif; ?>


<? if (!empty($arResult["VK"])): ?>

    <a class="mobile-nav-soc__lnk" href="<?= $arResult["VK"] ?>">
        <img src="assets/images/mobile-soc/vk.png" alt="">
    </a>
<? endif; ?>


<? if (!empty($arResult["FB"])): ?>
    <a class="mobile-nav-soc__lnk" href="<?=$arResult["FB"]?>">
        <img src="assets/images/mobile-soc/fb.png" alt="">
    </a>
<? endif; ?>


<? if (!empty($arResult["YT"])): ?>
    <a class="mobile-nav-soc__lnk" href="<?=$arResult["YT"]?>">
        <img src="assets/images/mobile-soc/ytb.png" alt="">
    </a>
<? endif; ?>


<? if (!empty($arResult["OK"])): ?>
    <a class="mobile-nav-soc__lnk" href="<?=$arResult["OK"]?>">
        <img src="assets/images/mobile-soc/ok.png" alt="">
    </a>
<? endif; ?>


<? if (!empty($arResult["GP"])): ?>
    <a href="<?= $arResult["GP"] ?>" target="_blank"><img src="/local/templates/.default/images/ico-g+.jpg" alt=""></a>
<? endif; ?>


<? if (!empty($arResult["TEL"])): ?>
    <a href="<?= $arResult["TEL"] ?>" target="_blank"><img style="width: 40px; height: 40px" src="/local/templates/.default/images/Telegram.png" alt=""></a>
<? endif; ?>