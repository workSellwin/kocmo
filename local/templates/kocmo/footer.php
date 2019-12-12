<? use Lui\Kocmo\IncludeComponent as Component;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if ($obPage && $obPage->isView('SHOW_SIDEBAR')) { ?>
    </div>
<? } ?>
</div>

</main>

<footer class="footer">
    <div class="footer-top">
        <div class="container footer-top__inner">
            <a <?= URL() == '/' ? '' : 'href="/"' ?> class="footer__logo">
                <img src="/assets/images/footer-logo.png" alt="">
            </a>

            <div class="footer__contacts">
                <a href="tel:+375296665544" class="footer__contacts-phone">626-14-14</a>
                <div class="footer__contacts-schedule">
                    <span class="footer__contacts-schedule-highlighted">Горячая линия</span> <span>Ежедневно с 9:00 до 21:00</span>
                </div>
            </div>
            <? Component::NewsList(['template' => 'footer_soc', 'PARENT_SECTION' => '23']) ?>
        </div>
    </div>
    <div class="footer-middle">
        <div class="container footer-middle__inner">
            <div class="footer-nav-column">
                <? Component::Menu(['template' => 'footer', 'ROOT_MENU_TYPE' => 'footer1',]); ?>
            </div>
            <div class="footer-nav-column">
                <? Component::Menu(['template' => 'footer', 'ROOT_MENU_TYPE' => 'footer2',]); ?>
            </div>
            <div class="footer-nav-column">
                <? Component::Menu(['template' => 'footer-loyalty', 'ROOT_MENU_TYPE' => 'footer3',]); ?>
            </div>

            <? $APPLICATION->IncludeComponent(
                "bh:footer_subscribe",
                "",
                array(), false
            ); ?>

        </div>
    </div>
    <? Component::NewsList(['template' => 'footer_payment', 'PARENT_SECTION' => '22']) ?>
    <div class="container text">
        ООО «Космо-М» <br>
        220014, Республика Беларусь, г. Минск, пер. С. Ковалевской, 60, офис 818
        УНП 191060401 <br>
        Регистрационный номер в Торговом реестре Республики Беларусь 465316 от 12 ноября 2019 года
    </div>


</footer>

<? Component::ShowSvg(); ?>

<div class="up-btn"></div>


<? global $OBJ_ITEMS; ?>

<script type="text/javascript">
    var OBJ_ITEMS = <?echo CUtil::PhpToJSObject($OBJ_ITEMS['OBJ_ITEM'])?>;
</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(47438272, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/47438272" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<?
if (intval($_GET['PAGEN_1']) > 1) {

    $addedText = ' - страница ' . intval($_GET['PAGEN_1']);
    $t = $APPLICATION->GetPageProperty('title');

    if (strpos($t, $addedText) === false) {
        $APPLICATION->SetPageProperty('title', $t . $addedText);
    }

    $d = $APPLICATION->GetPageProperty('description');
    if (strpos($d, $addedText) === false) {
        $APPLICATION->SetPageProperty('description', $d . $addedText);
    }
}
?>
</body>
</html>
