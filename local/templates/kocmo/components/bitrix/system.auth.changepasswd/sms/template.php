<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
if (!empty($arParams["~AUTH_RESULT"])):
    $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
    ?>
    <div class="alert <?= ($arParams["~AUTH_RESULT"]["TYPE"] == "OK" ? "alert-success" : "alert-danger") ?>"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
<? endif ?>

<form method="post" action="<?= $arResult["AUTH_FORM"] ?>" name="bform" class="form-create-password">
    <? if (strlen($arResult["BACKURL"]) > 0): ?>
        <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
    <? endif ?>
    <input type="hidden" name="AUTH_FORM" value="Y">
    <input type="hidden" name="change_password" value="yes">
    <input type="hidden" name="lang" value="ru">
    <input type="hidden" name="TYPE" value="CHANGE_PWD">

    <input type="hidden" name="USER_CHECKWORD" value=""/>
    <input type="hidden" name="USER_LOGIN" value=""/>
    <?
        $phone = urldecode($_SESSION["system.auth.changepasswd"]["USER_PHONE_NUMBER"]);
        $phone = preg_replace('/[^0-9]/', '', $phone);
    ?>
    <input type="hidden" name="PHONE" value="<?= $phone ?>">

    <div class="form-field__required-wrap">
        <input type="text" required="" name="SMS_CODE" value="<?= urldecode($_REQUEST["SMS_CODE"]) ?>"
               class="form-field__required-input js_field-required-input" onfocus="this.removeAttribute('readonly')"
               id="SMS_CODE">
        <label class="form-field__required-label" for="form-create-password-sms" style="">Введите код из СМС
            <span class="required">*</span></label>
    </div>

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
            Изменить
            <svg width="25" height="9">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-right"></use>
            </svg>
        </button>
    </div>

    		<div class="bx-authform-description-container">
    			<? //echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?>
    		</div>
</form>

<script>
    document.bform.USER_CHECKWORD.focus();
</script>