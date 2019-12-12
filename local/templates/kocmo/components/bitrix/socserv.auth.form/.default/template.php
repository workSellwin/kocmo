<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="form-login__soc">
    <div class="form-login__soc-footer-lnks">
        Войти через:
        <div class="bx-auth-serv-icons footer__soc">
            <? foreach ($arParams["~AUTH_SERVICES"] as $service): ?>
                <?
                $class = '';

                switch ($service['ID']) {
                    case 'Odnoklassniki':
                        $class = 'form-login__soc-item footer__soc-item--ok';
                        break;
                }
                ?>
                <? if (!empty($class)): ?>
                    <a class="<?= $class ?>" title="<?= htmlspecialcharsbx($service["NAME"]) ?>"
                       href="javascript:void(0)"
                       onclick="BxShowAuthFloat('<?= $service["ID"] ?>//', '<?= $arParams["SUFFIX"] ?>//')">
                        <i class="bx-ss-icon <?= htmlspecialcharsbx($service["ICON"]) ?>"></i>
                    </a>
                <? endif; ?>
            <? endforeach ?>
        </div>
    </div>

    <a href="#" class="form-login__soc-footer-lnk link">Как это работает?</a>
</div>

<!--            <a href="#" class="form-login__soc-item footer__soc-item--tw"></a>-->
<!--            <a href="#" class="form-login__soc-item footer__soc-item--vk"></a>-->
<!--            <a href="#" class="form-login__soc-item footer__soc-item--fb"></a>-->
<!--            <a href="#" class="form-login__soc-item footer__soc-item--yt"></a>-->
<!--                <a href="#" class="form-login__soc-item footer__soc-item--ok"></a>-->