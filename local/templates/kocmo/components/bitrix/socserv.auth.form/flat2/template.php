<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Bitrix\Main\Text\HtmlFilter;
/**
 * @var array $arParams
 */

CUtil::InitJSCore(array("popup"));

$arAuthServices = $arPost = array();
if (is_array($arParams["~AUTH_SERVICES"])) {
    $arAuthServices = $arParams["~AUTH_SERVICES"];
}
if (is_array($arParams["~POST"])) {
    $arPost = $arParams["~POST"];
}

$hiddens = "";
foreach ($arPost as $key => $value) {
    if (!preg_match("|OPENID_IDENTITY|", $key)) {
        $hiddens .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
    }
}
?>
<script>
    function BxSocServPopup(id) {
        var content = BX("bx_socserv_form_" + id);
        if (content) {
            var popup = BX.PopupWindowManager.create("socServPopup" + id, BX("bx_socserv_icon_" + id), {
                autoHide: true,
                closeByEsc: true,
                angle: {offset: 24},
                content: content,
                offsetTop: 3
            });

            popup.show();

            var input = BX.findChild(content, {'tag': 'input', 'attribute': {'type': 'text'}}, true);
            if (input) {
                input.focus();
            }

            var button = BX.findChild(content, {'tag': 'input', 'attribute': {'type': 'submit'}}, true);
            if (button) {
                button.className = 'btn btn-primary';
            }
        }
    }
</script>



<div class="form-login__soc">
    <div class="form-login__soc-footer-lnks">
        Войти через:

        <div class="footer__soc">
<!--            <a href="#" class="form-login__soc-item footer__soc-item--tw"></a>-->
<!--            <a href="#" class="form-login__soc-item footer__soc-item--vk"></a>-->
<!--            <a href="#" class="form-login__soc-item footer__soc-item--fb"></a>-->
<!--            <a href="#" class="form-login__soc-item footer__soc-item--yt"></a>-->
<!--            <a href="#" class="form-login__soc-item footer__soc-item--ok"></a>-->
            <?
            foreach ($arAuthServices as $service):
            $onclick = ($service["ONCLICK"] <> '' ? $service["ONCLICK"] : "BxSocServPopup('" . $service["ID"] . "')");

            $class = '';

            switch ($service['ID']) {
                case 'Odnoklassniki':
                    $class = 'form-login__soc-item footer__soc-item--ok';
                    break;
            }
            ?>
                <a id="bx_socserv_icon_<?= $service["ID"] ?>" class="<?= $class ?>"
                   href="javascript:void(0)" onclick="<?= HtmlFilter::encode($onclick) ?>"
                   title="<?= HtmlFilter::encode($service["NAME"]) ?>"></a>
            <?php
                if ($service["ONCLICK"] == '' && $service["FORM_HTML"] <> ''):?>
                <div id="bx_socserv_form_<?= $service["ID"] ?>" class="bx-authform-social-popup">
                    <form action="<?= $arParams["AUTH_URL"] ?>" method="post">
                        <?= $service["FORM_HTML"] ?>
                        <?= $hiddens ?>
                        <input type="hidden" name="auth_service_id" value="<?= $service["ID"] ?>"/>
                    </form>
                </div>
                <? endif ?>
            <?endforeach;?>
        </div>
    </div>

    <a href="#" class="form-login__soc-footer-lnk link">Как это работает?</a>
</div>