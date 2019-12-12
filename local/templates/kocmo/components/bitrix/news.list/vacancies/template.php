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
<div class="vacancies">

    <div class="vacancies__promo">
        <h3>МЫ ВСЕГДА РАДЫ НОВЫМ ТАЛАНТАМ</h3>

        <p>Это наиболее распространенная модель очень простой механикой постоянные покупатели накапливают
            балы последующего обмена их на материальные выгоды (дисконт, бесплатный товар, специальное
            предложение и т.д.)
            Несмотря на кажущуюся простоту данного метода, компании умудряются настолько усложнить
            программу, что сами начинают в ней путаться.</p>
    </div>

    <div class="vacancies__banner">
        <div class="vacancies__banner-title">
            вы Хотите стать <span>частью</span>
            нашей команды?
        </div>

        <div class="vacancies__banner-info">
            <div class="vacancies__banner-contact">
                <svg width="22" height="16">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-letter"></use>
                </svg>

                Присылайте резюме на адрес <a href="mailto:rabota@kocmo.by">rabota@kocmo.by</a>
            </div>

            <div class="vacancies__banner-contact">
                <svg width="22" height="22">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-phone"></use>
                </svg>

                Свяжитесь с HR менеджером: <a href="tel:+375441233223">+375 (44) 123-32-23</a>
            </div>

            <div class="vacancies__banner-subinfo">
                также Вы можете оставить заявку ниже в описании вакансии,
                которая Вас заинтересовала.
            </div>
        </div>
    </div>


    <div class="vacancies__city">
        <div class="form-field">
            <select name="" class="js_custom-select">
                <? foreach ($arResult['CITY'] as $city): ?>
                    <? // Минск
                    if ($city['ID'] == 18340):?>
                        <option selected value="<?= $city['ID'] ?>"><?= $city['NAME'] ?></option>
                    <? else: ?>
                        <option value="<?= $city['ID'] ?>"><?= $city['NAME'] ?></option>
                    <? endif; ?>
                <? endforeach; ?>
            </select>
        </div>
    </div>

    <div class="preloader-wrap">
        <div class="vacancies__offers">
            <div class="tabs-wrap js_tabs-wrap">
                <div class="tabs">
                    <? $i = 1;
                    foreach ($arResult['SECTION_LIST'] as $arItem):?>
                        <?
                        if (count($arItem['ITEMS']) > 0):?>
                            <span data-id="vacancies-<?= $arItem['ID'] ?>"
                                  data-section-id="<?= $arItem['ID'] ?>"
                                  class="tab tab--dotted js_tab <?= $i == 1 ? 'active' : '' ?>"><?= $arItem['NAME'] ?></span>
                            <?
                            $i++;
                        endif;
                    endforeach; ?>
                </div>

                <div class="panels">
                    <? $j = 1;
                    foreach ($arResult['SECTION_LIST'] as $arItem):?>
                        <div data-id="vacancies-<?= $arItem['ID'] ?>"
                             class="panel panel--customer js_panel <?= $j == 1 ? 'active' : '' ?>">
                            <?
                            foreach ($arItem['ITEMS'] as $Item):?>
                                <?
                                $this->AddEditAction($Item['ID'], $Item['EDIT_LINK'], CIBlock::GetArrayByID($Item["IBLOCK_ID"], "ELEMENT_EDIT"));
                                $this->AddDeleteAction($Item['ID'], $Item['DELETE_LINK'], CIBlock::GetArrayByID($Item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                ?>
                                <div class="vacancies__item" data-vacancies-id="<?= $Item['ID'] ?>"
                                     id="<?= $this->GetEditAreaId($Item['ID']); ?>">
                                    <div class="vacancies__item-header">
                                        <div class="vacancies__item-title"><?= $Item['NAME'] ?></div>
                                        <div class="vacancies__item-salary">Заработная плата от
                                            <span><?= $Item['PROPERTIES']['ZARPLATA']['VALUE'] ?></span>руб
                                        </div>
                                    </div>


                                    <div class="vacancies__item-description">
                                        <?= $Item['PREVIEW_TEXT'] ?>
                                    </div>

                                    <div class="vacancies__item-info" style="display: none;">
                                        <?= $Item['DETAIL_TEXT'] ?>
                                    </div>

                                    <div class="vacancies__item-send" style="display: none;">
                                        <a href="#popup-vacancies" data-vacancy="<?= $Item['NAME'] ?>"
                                           class="btn vacancies__item-btn js_fancybox-vacancy">ОТКЛИКНУТЬСЯ НА
                                            ВАКАНСИЮ</a>
                                    </div>

                                    <div class="vacancies__item-more js_vacancies__item-more">
                                        Подробнее

                                        <svg width="20" height="9">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 xlink:href="#svg-arrow-right"></use>
                                        </svg>
                                    </div>
                                </div>
                            <? endforeach; ?>
                        </div>
                        <?
                        $j++;
                    endforeach; ?>
                </div>
            </div>
        </div>

        <div class="preloader" style="display: none;">
            <svg width="64" height="64">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-preloader"></use>
            </svg>
        </div>

    </div>
</div>

<div id="popup-vacancies" class="popup popup-vacancies preloader-wrap" style="display: none;">

    <? $APPLICATION->IncludeComponent(
	"bh:feedback.form", 
	"vacancies", 
	array(
		"PROD_ID" => $elementId,
		"ACTIVE_ELEMENT" => "N",
		"ADD_HREF_LINK" => "Y",
		"ADD_LEAD" => "N",
		"ALX_LINK_POPUP" => "N",
		"BBC_MAIL" => "",
		"CATEGORY_SELECT_NAME" => "Вакансия",
		"CHECKBOX_TYPE" => "CHECKBOX",
		"CHECK_ERROR" => "N",
		"COLOR_SCHEME" => "BRIGHT",
		"EVENT_TYPE" => "ALX_VAKANSIA_FORM",
		"FB_TEXT_NAME" => "",
		"FB_TEXT_SOURCE" => "PREVIEW_TEXT",
		"FORM_ID" => "FORM_VACANCIES",
		"IBLOCK_ID" => "11",
		"IBLOCK_TYPE" => "altasib_feedback",
		"INPUT_APPEARENCE" => array(
			0 => "DEFAULT",
		),
		"JQUERY_EN" => "jquery",
		"LINK_SEND_MORE_TEXT" => "Отправить ещё одно сообщение",
		"LOCAL_REDIRECT_ENABLE" => "N",
		"MASKED_INPUT_PHONE" => array(
			0 => "PHONE",
		),
		"MESSAGE_OK" => "Ваше сообщение было успешно отправлено",
		"NAME_ELEMENT" => "FIO",
		"PROPERTY_FIELDS" => array(
			0 => "FILE",
			1 => "PHONE",
			2 => "FIO",
			3 => "EMAIL",
			4 => "FEEDBACK_TEXT",
		),
		"PROPERTY_FIELDS_REQUIRED" => array(
			0 => "PHONE",
			1 => "FIO",
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
		"SEND_IMMEDIATE" => "Y",
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
		"COMPONENT_TEMPLATE" => "vacancies",
		"COLOR_THEME" => "",
		"COLOR_OTHER" => "#009688"
	),
	false
); ?>

</div>





<script type="text/javascript">
    $(document).ready(function () {
        var DATA_CITY =<?echo CUtil::PhpToJSObject($arResult['CITY'])?>;

        $(".tabs .tab--dotted").each(function () {
            $(this).hide();
        });

        $(".panel .vacancies__item").each(function () {
            $(this).hide();
        });

        setTimeout(function () {
            $(".vacancies__city .js_custom-select").trigger("change");
        }, 100);

        $('.vacancies__city .js_custom-select').on('change', function () {
            var this_id = $(this).val();
            vacanciesCity(DATA_CITY[this_id]);
        });


        $('.vacancies__item-btn').on('click', function () {
            var tem = $(this).attr('data-vacancy');
            $('.popup-vacancies__form #VAKANSIA').val(tem);
        })

    });

    function vacanciesCity($thisCity) {
        $(".tabs .tab--dotted").each(function () {
            $(this).hide();
        });

        $(".panel .vacancies__item").each(function () {
            $(this).hide();
        });

        var i = 1;
        for (section in $thisCity['SECTION']) {
            console.log(section);
            if (i == 1) {
                $('.tabs .tab--dotted[data-section-id=' + section + ']').addClass('active');
            }
            $('.tabs .tab--dotted[data-section-id=' + section + ']').show();
            i++;
        }
        var j = 1;
        for (item in $thisCity['ITEMS']) {
            console.log(item);
            if (j == 1) {
                $('.panel .vacancies__item[data-vacancies-id=' + item + ']').parent().addClass('active');
            }
            $('.panel .vacancies__item[data-vacancies-id=' + item + ']').show();
            j++;
        }
    }

</script>