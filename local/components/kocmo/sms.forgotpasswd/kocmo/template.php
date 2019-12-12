<div class="form-recovery__header">
    На указанный телефон вам придет ссылка для восстановления пароля
</div>

<form action="" method="post" class="form-recovery">
    <div class="form-field__required-wrap">
        <input type="text" required
               name="form-recovery_phone"
               value=""
               class="form-field__required-input js_field-required-input"
               onfocus="this.removeAttribute('readonly')"
               readonly
               id="form-recovery-phone">
        <label class="form-field__required-label" for="form-recovery-phone">Введите телефон
            <span class="required">*</span></label>
    </div>

    <div class="form-recovery__recaptcha-wrap">
        <div class="form-field__required-wrap">
            <input type="text" required
                   name="form-recovery_captcha-phone"
                   value=""
                   class="form-field__required-input js_field-required-input"
                   onfocus="this.removeAttribute('readonly')"
                   readonly
                   id="form-recovery-captcha-phone">
            <label class="form-field__required-label" for="form-recovery-captcha-phone">Введите код
                <span class="required">*</span></label>
        </div>

        <div class="form-recovery__captcha">
            <img src="/assets/images/temp/captcha.png" alt="">
        </div>
    </div>

    <div class="form-submit-wrap">
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

    <p><a href="#" class="link">Напишите нам</a> и наши сотрудники обязательно помогут Вам
        с любой проблемой.</p>
</div>