<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
    <!-- desktop - 1920x250px, mobile - 126x480 -->
    <div class="e-gift-banner js_category-banner"
        <? if ($arResult['PROPERTIES']['LINK']): ?>
            onclick="location='<?= $arResult['PROPERTIES']['LINK'] ?>'"
            style="cursor: pointer;"
        <? endif; ?>
         data-mobile-background="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>"
         data-desktop-background="<?= $arResult['PREVIEW_PICTURE']['SRC'] ?>"
    ></div>

<? if ($arResult['PREVIEW_TEXT']): ?>
    <div class="top-promo container">
        <?= $arResult['PREVIEW_TEXT'] ?>
    </div>
<? endif; ?>

    <div class="e-gift">


        <? $APPLICATION->IncludeComponent(
	"bh:feedback.form", 
	"sertifikat", 
	array(
		"PROD_ID" => $elementId,
		"ACTIVE_ELEMENT" => "Y",
		"ADD_HREF_LINK" => "Y",
		"ADD_LEAD" => "N",
		"ALX_LINK_POPUP" => "N",
		"BBC_MAIL" => "",
		"CATEGORY_SELECT_NAME" => "Вакансия",
		"CHECKBOX_TYPE" => "CHECKBOX",
		"CHECK_ERROR" => "N",
		"COLOR_SCHEME" => "BRIGHT",
		"EVENT_TYPE" => "ALX_SERTIFIKAT_FORM",
		"FB_TEXT_NAME" => "Введите текст поздравления",
		"FB_TEXT_SOURCE" => "PREVIEW_TEXT",
		"FORM_ID" => "FORM_SERTIFIKAT",
		"IBLOCK_ID" => "12",
		"IBLOCK_TYPE" => "podarochnyye_sertifikaty",
		"INPUT_APPEARENCE" => array(
			0 => "DEFAULT",
		),
		"JQUERY_EN" => "jquery",
		"LINK_SEND_MORE_TEXT" => "Отправить ещё одно сообщение",
		"LOCAL_REDIRECT_ENABLE" => "Y",
		"MASKED_INPUT_PHONE" => array(
			0 => "PHONE",
		),
		"MESSAGE_OK" => "Ваш запрос обрабатывается пожалуйста подождите.",
		"NAME_ELEMENT" => "EMAIL",
		"PROPERTY_FIELDS" => array(
			0 => "FIO",
			1 => "EMAIL",
			2 => "DATE",
			3 => "NAME_OTPRAVITEL",
			4 => "FEEDBACK_TEXT",
		),
		"PROPERTY_FIELDS_REQUIRED" => array(
			0 => "EMAIL",
		),
		"PROPS_AUTOCOMPLETE_EMAIL" => array(
			0 => "EMAIL",
		),
		"PROPS_AUTOCOMPLETE_NAME" => array(
			0 => "FIO",
		),
		"PROPS_AUTOCOMPLETE_PERSONAL_PHONE" => array(
			0 => "PHONE",
		),
		"PROPS_AUTOCOMPLETE_VETO" => "N",
		"REQUIRED_SECTION" => "N",
		"SECTION_FIELDS_ENABLE" => "N",
		"SECTION_MAIL_ALL" => "",
		"SEND_IMMEDIATE" => "N",
		"SEND_MAIL" => "N",
		"SHOW_LINK_TO_SEND_MORE" => "Y",
		"SHOW_MESSAGE_LINK" => "Y",
		"SPEC_CHAR" => "N",
		"USERMAIL_FROM" => "N",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_INPUT_LABEL" => "",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_CAPTCHA" => "N",
		"WIDTH_FORM" => "50%",
		"COMPONENT_TEMPLATE" => "sertifikat",
		"COLOR_THEME" => "",
		"COLOR_OTHER" => "#009688",
		"GALEREYA" => $arResult["PROPERTIES"]["GALEREYA"],
		"SUM_SERTIFIKATA" => $arResult["PROPERTIES"]["SUM_SERTIFIKATA"],
		"LOCAL_REDIRECT_URL" => "/cart/?ORDER_ID=",
		"EGIFT" => "Y",
		"ADD_EVENT_FILES" => "N",
        'UID_SUM'=>$arResult['UID_SUM'],
	),
	false
); ?>


    </div>

<? if ($arResult['DETAIL_TEXT']): ?>
    <div class="promo">
        <div class="container">
            <?= $arResult['DETAIL_TEXT'] ?>
        </div>
    </div>
<? endif; ?>

    <script type="text/javascript">
        var i = 1;
        $('.e-gift__slider-wrap .e-gift__slider-item').each(function () {
            if (i == 1) {
                $(this).trigger('click');
            }
            i++;
        });

        function sertificatFon($this) {
            var img_src = $($this).children('img').attr('src');
            $('div.e-gift__cert').css("background-size", "cover");
            $('div.e-gift__cert').css("background-image", "url('" + img_src + "')");
            $('input#COLOR_SERTIFIKAT').val(img_src);
        }

        function setEgiftSum($this) {
            var sum = $($this).children().val();
            var gui = $($this).children().attr('data-gui');
            $('input#SUM_SERTIFIKAT').val(sum);
            $('input#GUI_SERTIFIKAT').val(gui);
        }

    </script>

<? //PR($arResult)?>