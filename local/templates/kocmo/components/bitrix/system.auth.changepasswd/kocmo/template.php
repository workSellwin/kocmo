<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

//one css for all system.auth.* forms
//$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
//
//if ($arResult["PHONE_REGISTRATION"] && false) {
//    CJSCore::Init('phone_auth');
//}
//if (!empty($arParams["~AUTH_RESULT"])):
//    $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
//    ?>
<!--    <div class="alert --><?//= ($arParams["~AUTH_RESULT"]["TYPE"] == "OK" ? "alert-success" : "alert-danger") ?><!--">--><?//= nl2br(htmlspecialcharsbx($text)) ?><!--</div>-->
<?// endif ?>
<?
if ( empty($_GET['USER_CHECKWORD']) || empty($_GET['USER_LOGIN']) || $_GET['change_password'] != 'yes') {
    $arResult['ERRORS'][] = 'Перейдите по ссылке отправленной вам на почту';
}
else {
    if( count($_POST) && empty($_POST['USER_PASSWORD']) ){
        $arResult['ERRORS'][] = 'Введите пароль';
    }
    else if ( count($_POST) && empty($_POST['USER_CONFIRM_PASSWORD']) ){
        $arResult['ERRORS'][] = 'подтвердите пароль';
    }
    else if (!empty($_POST['USER_PASSWORD'])) {

        if ($_POST['USER_PASSWORD'] != $_POST['USER_CONFIRM_PASSWORD']) {
            $arResult['ERRORS'][] = 'Пароли не совпадают';
        }

        if ( strlen($_POST['USER_PASSWORD']) < 6 || strlen($_POST['USER_CONFIRM_PASSWORD']) < 6 ) {
            $arResult['ERRORS'][] = 'Пароль должен быть не менее 6 символов длиной';
        }
    }
}
?>
<? if ( count($arResult['ERRORS']) ): ?>
    <div class="alert alert-danger">
        <?
        foreach($arResult['ERRORS'] as $message){
            echo $message, '<br>';
        }
        ?>
    </div>
<? endif; ?>

<? if ( count($arResult['ERRORS']) == 0 || true ){
    if( count($_POST)  && empty($_POST['USER_PASSWORD']) && $_POST['USER_PASSWORD'] == $_POST['USER_CONFIRM_PASSWORD']){
        echo '<div>', 'Дождитесь подтвержения смены пароля на почту и <a href="/auth/">авторизуйтесь</a>' , '</div>';
    }
}?>
<?
//echo '<pre>', print_r($arResult, true), '</pre>';
?>
<form method="post" action="<?= $arResult["AUTH_FORM"] ?>" name="bform" class="form-create-password">
    <? if (strlen($arResult["BACKURL"]) > 0): ?>
        <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
    <? endif ?>
    <input type="hidden" name="AUTH_FORM" value="Y">
    <input type="hidden" name="TYPE" value="CHANGE_PWD">
    <input type="hidden" name="USER_CHECKWORD" value="<?= urldecode($_GET["USER_CHECKWORD"]) ?>"/>
    <input type="hidden" name="USER_LOGIN" value="<?= urldecode($_GET["USER_LOGIN"]) ?>"/>

    <? if ($arResult["PHONE_REGISTRATION"] && false): ?>
        <div class="bx-authform-formgroup-container">
            <div class="bx-authform-label-container"><? echo GetMessage("change_pass_phone_number") ?></div>
            <div class="bx-authform-input-container">
                <input type="text" value="<?= htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"]) ?>"
                       disabled="disabled"/>
                <input type="hidden" name="USER_PHONE_NUMBER"
                       value="<?= htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"]) ?>"/>
            </div>
        </div>
    <? endif ?>


    <div class="form-field__required-wrap">
        <input type="password" required="" name="USER_PASSWORD" maxlength="255"
               value="<?= $arResult["USER_PASSWORD"] ?>" class="form-field__required-input js_field-required-input"
               onfocus="this.removeAttribute('readonly')" id="form-create-password-new" autocomplete="off">

        <label class="form-field__required-label" for="form-create-password-new">Введите новый пароль
            <span class="required">*</span></label>
    </div>

    <div class="form-field__required-wrap">
        <input type="password" required="" name="USER_CONFIRM_PASSWORD"
               value="<?= $arResult["USER_CONFIRM_PASSWORD"] ?>"
               class="form-field__required-input js_field-required-input" onfocus="this.removeAttribute('readonly')"
               id="form-create-password-repeat" autocomplete="off">

        <label class="form-field__required-label" for="form-create-password-repeat">Повторите новый пароль
            <span class="required">*</span></label>
    </div>

    <? if ($arResult["USE_CAPTCHA"]): ?>
        <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>

        <div class="bx-authform-formgroup-container">
            <div class="bx-authform-label-container"><? echo GetMessage("system_auth_captcha") ?></div>
            <div class="bx-captcha"><img
                        src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" width="180"
                        height="40" alt="CAPTCHA"/></div>
            <div class="bx-authform-input-container">
                <input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/>
            </div>
        </div>
    <? endif ?>

    <div class="form-submit-wrap">
        <button type="submit" class="form-submit btn" name="change_pwd">
            <?= GetMessage("AUTH_CHANGE") ?>

            <svg width="25" height="9">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-right"></use>
            </svg>
        </button>
    </div>

    <!--		<div class="bx-authform-description-container">-->
    <!--			--><? //echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?>
    <!--		</div>-->
</form>
<? if ($arResult["PHONE_REGISTRATION"] && false): ?>

    <script>
        new BX.PhoneAuth({
            containerId: 'bx_chpass_resend',
            errorContainerId: 'bx_chpass_error',
            interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
            data:
            <?=CUtil::PhpToJSObject([
                'signedData' => $arResult["SIGNED_DATA"]
            ])?>,
            onError:
                function (response) {
                    var errorNode = BX('bx_chpass_error');
                    errorNode.innerHTML = '';
                    for (var i = 0; i < response.errors.length; i++) {
                        errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br />';
                    }
                    errorNode.style.display = '';
                }
        });
    </script>

    <div class="alert alert-danger" id="bx_chpass_error" style="display:none"></div>

    <div id="bx_chpass_resend"></div>

<? endif ?>
<!-- ------------------------  -->

<script type="text/javascript">
    document.bform.USER_CHECKWORD.focus();
</script>
