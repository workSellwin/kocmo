<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);


if ($arResult['AUTHORIZED']) {
    echo Loc::getMessage('MAIN_AUTH_PWD_SUCCESS');
    return;
}
?>

<div class="form-recovery__header">
    На указанный email вам придет ссылка для восстановления пароля
</div>
<form name="bform" method="post" action="<?= POST_FORM_ACTION_URI; ?>" class="form-recovery">
    <? if ($arResult['ERRORS']): ?>
        <div class="alert alert-danger">
            <? foreach ($arResult['ERRORS'] as $error) {
                echo $error;
            }
            ?>
        </div>
    <? elseif ($arResult['SUCCESS']): ?>
        <div class="alert alert-success">
            <?= $arResult['SUCCESS']; ?>
        </div>
    <? endif; ?>
    <div class="form-field__required-wrap">
        <input type="email" required
               name="<?= $arResult['FIELDS']['email']; ?>"
               value=""
               class="form-field__required-input js_field-required-input"
               onfocus="this.removeAttribute('readonly')"
               readonly
               id="form-recovery-mail">
        <label class="form-field__required-label" for="form-recovery-mail">Введите email
            <span class="required">*</span></label>
    </div>
    <? if ($arResult['CAPTCHA_CODE']) { ?>
        <input type="hidden" name="captcha_sid" value="<?= \htmlspecialcharsbx($arResult['CAPTCHA_CODE']); ?>"/>
        <div class="form-recovery__recaptcha-wrap">
            <div class="form-field__required-wrap">
                <input type="text" required
                       name="captcha_word"
                       value=""
                       class="form-field__required-input js_field-required-input"
                       onfocus="this.removeAttribute('readonly')"
                       readonly
                       id="form-recovery-captcha">
                <label class="form-field__required-label" for="form-recovery-mail">Введите код
                    <span class="required">*</span></label>
            </div>

            <div class="form-recovery__captcha">
                <img width="177" height="42"
                     src="/bitrix/tools/captcha.php?captcha_sid=<?= \htmlspecialcharsbx($arResult['CAPTCHA_CODE']); ?>"
                     alt="">
            </div>
        </div>
    <? } ?>
    <div class="form-submit-wrap">
        <input type="hidden" name="<?= $arResult['FIELDS']['action']; ?>"
               value="<?= Loc::getMessage('MAIN_AUTH_PWD_FIELD_SUBMIT'); ?>"/>
        <button type="submit" class="form-submit btn">
            ВОССТАНОВИТЬ
            <svg width="25" height="9">
                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                     xlink:href="#svg-arrow-right"></use>
            </svg>
        </button>
    </div>
</form>

<div class="double-separator-reverse form-login__separator"></div>

<div class="form-recovery__footer">
    <b>Не получается восстановить пароль?</b>

    <p><a href="#" class="link">Напишите нам</a> и наши сотрудники помогут Вам.
        Чтобы восстановить пароль, необходимо сообщить Ваши фа-милию и имя, адрес электронной почты,
        который Вы указывали при регистрации в KOCMO.BY</p>
</div>
