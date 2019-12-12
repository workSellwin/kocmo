<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
CJSCore::Init(array("jquery", "date"));
CAjax::Init();
$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name, $component->arParams['AJAX_OPTION_ADDITIONAL']);
PR($bxajaxid, true);
$arResult['ITEMS'][] = [
    'type' => 'text',
    'code' => 'NAME',
    'name' => 'Введите имя',
    'value' => $arResult["VALUES"]['NAME'],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];
$arResult['ITEMS'][] = [
    'type' => 'text',
    'code' => 'LAST_NAME',
    'name' => 'Введите вашу фамилию',
    'value' => $arResult["VALUES"]['LAST_NAME'],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];
$arResult['ITEMS'][] = [
    'type' => 'text',
    'code' => 'PERSONAL_PHONE',
    'name' => 'Введите ваш телефон',
    'value' => $arResult["VALUES"]['PERSONAL_PHONE'],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];
$arResult['ITEMS'][] = [
    'type' => 'email',
    'code' => 'EMAIL',
    'name' => 'Введите email',
    'value' => $arResult["VALUES"]['EMAIL'],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];
$arResult['ITEMS'][] = [
    'type' => 'password',
    'code' => 'PASSWORD',
    'name' => 'Придумайте пароль',
    'value' => $arResult["VALUES"]['PASSWORD'],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];
$arResult['ITEMS'][] = [
    'type' => 'password',
    'code' => 'CONFIRM_PASSWORD',
    'name' => 'Повторите пароль',
    'value' => $arResult["VALUES"]['CONFIRM_PASSWORD'],
    'required' => 'Y',
    'class' => '',
    'onfocus' => '',
];
$arResult['ITEMS'][] = [
    'type' => 'date',
    'code' => 'PERSONAL_BIRTHDAY',
    'name' => 'Дата рождения',
    'value' => $arResult["VALUES"]['PERSONAL_BIRTHDAY'],
    'required' => '',
    'class' => '',
    'onfocus' => '',
];
?>
<form action="" method="post" class="form-register">
    <?
    if (count($arResult["ERRORS"]) > 0) {
        foreach ($arResult["ERRORS"] as $key => $error) {
            if (intval($key) == 0 && $key !== 0) {
                $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;" . GetMessage("REGISTER_FIELD_" . $key) . "&quot;", $error);
            }
        }
        ?>
        <div class="alert alert-danger"><?
        echo implode('<br>', $arResult["ERRORS"]);
        ?></div><?
    }
    ?>
    <input type="hidden" name="SIGNED_DATA" value="<?= htmlspecialcharsbx($arResult["SIGNED_DATA"]) ?>"/>
    <div class="two-columns-grid two-columns-grid--form-register">
        <? foreach ($arResult["ITEMS"] as $code => $arItem) { ?>
            <? switch ($arItem['type']) {
                case 'email':
                case 'password':
                case 'text':
                    ?>
                    <div class="two-columns-grid__item">
                        <div class="form-field__required-wrap">
                            <input type="<?= $arItem['type'] ?>" <?= $arItem['required'] ? 'required' : ''; ?>
                                   name="REGISTER[<?= $arItem['code'] ?>]"
                                   value="<?= $arItem['value'] ?>"
                                   class="<?= $arItem['required'] ? 'form-field__required-input js_field-required-input' : ''; ?> ">
                            <label class="form-field__required-label" for="form-register-name"><?= $arItem['name'] ?>
                                <? if ($arItem['required']) { ?>
                                    <span class="required">*</span>
                                <? } ?>
                            </label>
                        </div>
                    </div>
                    <? break;
                case 'date':
                    ?>
                    <div class="two-columns-grid__item">
                        <div class="form-field form-field--date">
                            <input name="REGISTER[<?= $arItem['code'] ?>]"
                                   value="<?= $arItem['value'] ?>"
                                   class="form-field__input"
                                   type="text"
                                   onclick="BX.calendar({node: this, field: this, bTime: false});"
                                   placeholder="<?= $arItem['name'] ?>">
                            <svg width="16" height="15">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                     xlink:href="#svg-calendar"></use>
                            </svg>
                        </div>
                    </div>
                    <? break; ?>
                <? } ?>
        <? } ?>


        <div class="two-columns-grid__item">
            <div class="form-register__checkbox-header">
                Выберите ваш пол <span class="required">*</span>
            </div>

            <div class="form-register__checkbox-wrap">
                <label class="radio js_radio">
                    <input type="radio" name="form-register_gender" required value="">
                    Женский
                </label>

                <label class="radio js_radio">
                    <input type="radio" name="form-register_gender" value="">
                    Мужской
                </label>
            </div>

            <div class="form-register__checkbox-footer">
                <label class="checkbox js_checkbox">
                    <input type="checkbox" required name="form-register_agreement">Я согласен на
                    сбор и обработку своих персональных
                    данных
                </label>
            </div>
        </div>

        <div class="two-columns-grid__item form-register__gifts">
            <label class="checkbox js_checkbox">
                <input type="checkbox" checked="checked" name="form-register_gifts">Я хочу
                получать эксклюзивные скидки и подарки
            </label>
        </div>

    </div>
    <input type="hidden" name="register_submit_button" value="<?= GetMessage("AUTH_REGISTER") ?>"/>
    <div class="form-submit-wrap">
        <button type="submit" class="form-submit btn">
            ЗАРЕГИСТРИРОВАТЬСЯ

            <svg width="25" height="9">
                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                     xlink:href="#svg-arrow-right"></use>
            </svg>
        </button>
    </div>

    <div class="form-register__attention">
        Нажимая кнопку зарегистрироваться Вы соглашаетесь
        с условиями <a href="#" target="_blank" class="link">публичной оферты</a>
    </div>
</form>
