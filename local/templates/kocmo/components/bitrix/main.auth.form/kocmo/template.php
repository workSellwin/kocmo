<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if ($arResult['AUTHORIZED']) {
    LocalRedirect(SITE_DIR);
    return;
}
?>

<form name="<?= $arResult['FORM_ID']; ?>" method="post" target="_top" action="<?= POST_FORM_ACTION_URI; ?>"
      class="form-login">
    <? if ($arResult['ERRORS']): ?>
        <div class="alert alert-danger">
            <? foreach ($arResult['ERRORS'] as $error) {
                echo $error;
            }
            ?>
        </div>
        <br>
    <? endif; ?>
    <div class="form-field">
        <input name="<?= $arResult['FIELDS']['login']; ?>"
               value="<?= \htmlspecialcharsbx($arResult['LAST_LOGIN']); ?>"
               class="form-field__input"
               type="text"
               onfocus="this.removeAttribute('readonly');"
               readonly
               placeholder="Введите ваш email или телефон">
        <div class="form-field__tooltip">
            <svg width="20" height="20">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-tooltip"></use>
            </svg>
            <div class="form-field__tooltip-helper">
                Формат для ввода телефона<br>
                +375 _ _ - _ _ _ - _ _ - _ _
            </div>
        </div>
    </div>
    <div class="form-field">
        <input name="<?= $arResult['FIELDS']['password']; ?>"
               value=""
               class="form-field__input"
               autocomplete="off"
               type="password"
               placeholder="Введите пароль">
    </div>
    <div class="form-login__subfields">
        <? if ($arResult['STORE_PASSWORD'] == 'Y') { ?>
            <label class="checkbox js_checkbox">
                <input type="checkbox" id="USER_REMEMBER" name="<?= $arResult['FIELDS']['remember']; ?>"
                       value="Y" checked="checked">
                Запомнить меня
            </label>
        <? } ?>
        <a href="<?= $arParams['AUTH_FORGOT_PASSWORD_URL'] ?>" class="form-login__forget-password link">
            Забыли пароль?</a>
    </div>

    <? if ($arResult['CAPTCHA_CODE']): ?>
        <input type="hidden" name="captcha_sid" value="<?= \htmlspecialcharsbx($arResult['CAPTCHA_CODE']); ?>"/>
        <div class="bx-authform-formgroup-container dbg_captha">
            <div class="bx-authform-label-container">
                <?= Loc::getMessage('MAIN_AUTH_FORM_FIELD_CAPTCHA'); ?>
            </div>
            <div class="bx-captcha"><img
                        src="/bitrix/tools/captcha.php?captcha_sid=<?= \htmlspecialcharsbx($arResult['CAPTCHA_CODE']); ?>"
                        width="180" height="40" alt="CAPTCHA"/></div>
            <div class="bx-authform-input-container">
                <input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/>
            </div>
        </div>
    <? endif; ?>
    <input type="hidden" name="<?= $arResult['FIELDS']['action']; ?>"
           value="<?= Loc::getMessage('MAIN_AUTH_FORM_FIELD_SUBMIT'); ?>"/>
    <div class="form-submit-wrap">
        <button type="submit" class="form-submit btn">Войти
            <svg width="25" height="9">
                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                     xlink:href="#svg-arrow-right"></use>
            </svg>
        </button>
    </div>
</form>
<div class="double-separator-reverse form-login__separator"></div>

<?if($arResult["AUTH_SERVICES"]):?>
    <?
    $APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "flat2",
        array(
            "AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
            "AUTH_URL"=>$arResult["AUTH_URL"],
        ),
        $component,
        array("HIDE_ICONS"=>"Y")
    );
    ?>
<?endif?>

<script>
    <?if ($arResult['LAST_LOGIN'] != ''):?>
    try{document.<?= $arResult['FORM_ID'];?>.USER_PASSWORD.focus();}catch(e){}
    <?else:?>
    try{document.<?= $arResult['FORM_ID'];?>.USER_LOGIN.focus();}catch(e){}
    <?endif?>
</script>
