<?php

use Bitrix\Main\Localization\Loc;
?>
<div class="form-recovery__header">
    На указанный телефон вам придет ссылка для восстановления пароля
</div>

<form action="<?= POST_FORM_ACTION_URI; ?>" method="post" class="form-recovery">

    <? if ( count($arResult['ERRORS']) ): ?>
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
        <input type="text" required name="USER_PHONE" value="" readonly
               class="form-field__required-input js_field-required-input" onfocus="this.removeAttribute('readonly')"
               id="form-recovery-phone">
        <label class="form-field__required-label" for="form-recovery-phone">Введите телефон
            <span class="required">*</span>
        </label>
    </div>

<!--    <div class="form-recovery__recaptcha-wrap">-->
<!--        <div class="form-field__required-wrap">-->
<!--            <input type="text" required name="form-recovery_captcha-phone" value=""-->
<!--                   class="form-field__required-input js_field-required-input" onfocus="this.removeAttribute('readonly')"-->
<!--                   readonly id="form-recovery-captcha-phone">-->
<!--            <label class="form-field__required-label" for="form-recovery-captcha-phone">Введите код-->
<!--                <span class="required">*</span>-->
<!--            </label>-->
<!--        </div>-->
<!---->
<!--        <div class="form-recovery__captcha">-->
<!--            <img src="/local/templates/kocmo/imposition/build/assets/images/temp/captcha.png" alt="">-->
<!--        </div>-->
<!--    </div>-->

    <div class="form-submit-wrap">
        <button type="submit" class="form-submit btn" name="<?= $arResult['FIELDS']['action']; ?>" value="Y">
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

    <p><a href="#" class="link">Напишите нам</a> и наши сотрудники обязательно помогут Вам
        с любой проблемой.</p>
</div>