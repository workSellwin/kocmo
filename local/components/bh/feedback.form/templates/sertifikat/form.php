<form class="e-gift__form container" id="f_feedback_<?= $ALX ?>" name="f_feedback_<?= $ALX ?>"
      action="<?= POST_FORM_ACTION_URI ?>" method="post"
      enctype="multipart/form-data">

    <input type="hidden" id="COLOR_SERTIFIKAT" name="FIELDS[COLOR_SERTIFIKAT_<?= $ALX ?>]" value="">
    <input type="hidden" id="GUI_SERTIFIKAT" name="FIELDS[GUI_SERTIFIKAT_<?= $ALX ?>]" value="<?=$arParams['UID_SUM'][$arParams['SUM_SERTIFIKATA'][0]]?>">
    <input type="hidden" id="SUM_SERTIFIKAT" name="FIELDS[SUM_SERTIFIKAT_<?= $ALX ?>]" value="<?=$arParams['SUM_SERTIFIKATA'][0]?>">


    <input type="hidden" name="FEEDBACK_FORM_<?= $ALX ?>" value="Y"/>

    <? if ($arResult["ELEMENT_NAME"] != "") { ?><input type="hidden" name="ELEMENT_NAME"
                                                       value="<?= $arResult["ELEMENT_NAME"] ?>" /><? } ?>
    <? if ($arResult['ELEMENT_EDIT_LINK'] != "") { ?><input type="hidden" name="ELEMENT_EDIT_LINK"
                                                            value="<?= $arResult['ELEMENT_EDIT_LINK'] ?>" /><? } ?>
    <? if ($arResult["ELEMENT_EDIT_LINK_ID"] != "") { ?><input type="hidden" name="ELEMENT_EDIT_LINK_ID"
                                                               value="<?= $arResult["ELEMENT_EDIT_LINK_ID"] ?>" /><? } ?>


    <div class="e-gift__cert" style="background-size: cover!important;">
        <div class="e-gift__cert-title">подарочный <span>сертификат</span></div>

        <div class="double-separator e-gift__cert-separator"></div>

        <div class="e-gift__cert-sum">на сумму <span class="js_slider-pointer-price">0</span> BYN</div>

        <div class="form-field">

            <input name="FIELDS[FIO_<?= $ALX ?>]" value="" class="form-field__input" type="text"
                   placeholder="Введите имя">

        </div>

        <div class="form-field">
            <textarea name="FEEDBACK_TEXT_<?=$ALX?>"  placeholder="Введите текст поздравления"></textarea>
        </div>
    </div>


    <div class="e-gift__cert-choice">


        <div class="e-gift__slider-wrapper">
            <div class="e-gift__slider-title-wrap">
                <div class="e-gift__slider-title">Выберите дизайн сертификата:</div>

                <div class="e-gift__slider-control">
                    <div class="e-gift__slider-prev js_e-gift__slider-prev">
                        <svg width="31" height="9">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-slider-left"></use>
                        </svg>
                    </div>
                    <div class="e-gift__slider-counter js_e-gift__slider-counter" style="opacity: 0">
                        <div class="e-gift__slider-counter-current js_slider-counter-current">0</div>
                        &nbsp;/&nbsp;
                        <div class="e-gift__slider-counter-all js_slider-counter-all">0</div>
                    </div>
                    <div class="e-gift__slider-next js_e-gift__slider-next">
                        <svg width="31" height="9">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                 xlink:href="#svg-slider-right"></use>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="e-gift__slider-container swiper-container js_e-gift__slider-container">
                <div class="e-gift__slider-wrap swiper-wrapper">
                    <? foreach ($arParams['GALEREYA'] as $key => $photo):
                        $img_src = CFile::ResizeImageGet($photo, array('width' => 624, 'height' => 747), BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>
                        <label class="e-gift__slider-item swiper-slide radio js_radio" onclick="sertificatFon(this)">
                            <!-- 340x221 -->
                            <img src="<?= $img_src['src'] ?>" alt="">
                            <input type="radio" name="e_gift_design" checked value="">
                        </label>
                    <? endforeach; ?>
                </div>

                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>


        <div class="e-gift__cert-choice-sum">
            <div class="e-gift__cert-choice-sum-title">Выберите сумму сертификата:</div>
            <div class="e-gift__cert-choice-sum-select slider-pointer">
                <div class="slider-pointer__line">
                    <? foreach ($arParams['SUM_SERTIFIKATA'] as $key => $sum): ?>
                        <label class="slider-pointer__label js_radio" onclick="setEgiftSum(this)">
                            <input type="radio"
                                   data-gui="<?=$arParams['UID_SUM'][$sum]?>"
                                   class="js_slider-pointer-set-price" <?= $key === 0 ? 'checked="checked"' : '' ?>
                                   name="e_gift_cost" value="<?= $sum ?>">
                            <span><?= $sum ?></span>
                        </label>
                    <? endforeach; ?>
                </div>

                <div class="slider-pointer__selected-cost">
                    <span class="js_slider-pointer-price">0</span> BYN
                </div>
            </div>
        </div>


        <div class="e-gift__cert-shipment">

            <div class="e-gift__cert-shipment-title">Доставка E-Gift:</div>


            <? if ($arParams["ADD_HREF_LINK"] != "N"): ?>
                <input type="hidden" name="HREF_LINK_<?= $ALX ?>"
                       value="<?= "http://" . $_SERVER["SERVER_NAME"] . POST_FORM_ACTION_URI ?>"/>
            <? endif ?>
            <? if (count($arResult["TYPE_QUESTION"]) >= 1): ?>
                <? /* TYPE_QUESTION */ ?>
                <div class="afbf_item_pole required is_filled">
                    <div class="afbf_name"><?= $arParams["CATEGORY_SELECT_NAME"] ?></div>
                    <div class="afbf_inputtext_bg">
                        <input type="hidden" id="type_question_name_<?= $ALX ?>" name="type_question_name_<?= $ALX ?>"
                               value="<?= $arResult["TYPE_QUESTION"][0]["NAME"] ?>">
                        <select id="type_question_<?= $ALX ?>" class="afbf_select" name="type_question_<?= $ALX ?>"
                                onchange="ALX_SetNameQuestion(this,'<?= $ALX ?>');">
                            <option value=""><? if (!in_array("FLOATING_LABELS", $arParams['INPUT_APPEARENCE'])): echo GetMessage("ALX_CATEGORY_NO"); endif; ?></option>
                            <? foreach ($arResult["TYPE_QUESTION"] as $arField): ?>
                                <? if (trim(htmlspecialcharsEx($_POST["type_question_" . $ALX])) == $arField["ID"]): ?>
                                    <option value="<?= $arField["ID"] ?>" selected><?= $arField["NAME"] ?></option>
                                <? else: ?>
                                    <option value="<?= $arField["ID"] ?>"><?= $arField["NAME"] ?></option>
                                <? endif ?>
                            <? endforeach ?>
                        </select>
                    </div>
                    <? if ($arParams['REQUIRED_SECTION'] == "Y"): ?>
                        <div class="afbf_error_text"><?= GetMessage('AFBF_ERROR_TEXT') ?></div>
                    <? endif; ?>
                </div>
            <? endif ?>
            <? $k = 0; ?>

           <? $arrFields_userconsent = array();
            foreach ($arResult["FIELDS"] as $key => $arField):?>


                <? $arrFields_userconsent[] = $arField['NAME'];
                $fieldClass = '';
                $nameClass = '';
                if ($arField['DEFAULT_VALUE'] || $arField['AUTOCOMPLETE_VALUE'] || $arField["TYPE"] == "L" || $arField["TYPE"] == "E" || $arField["TYPE"] == "G")
                    $fieldClass .= ' is_filled';
                if ($arField["REQUIRED"] == 'Y')
                    $fieldClass .= ' required';
                if ($arField["CODE"] == "EMAIL_" . $ALX)
                    $fieldClass .= ' is_email';
                if ($arField['LIST_TYPE'] == 'C')
                    $nameClass .= ' static_name';
                ?>
                <div id="afbf_<?= mb_strtolower($arField["CODE"]) ?>" class="afbf_item_pole<?= $fieldClass ?>">

                    <? if ($arField["TYPE"] != "F"):?>

        <?//PR($arField)?>

                        <?if ($arField['CODE'] == 'EMAIL_'.$ALX):?>


                            <div class="e-gift__cert-shipment-row">
                                <div class="form-field__required-wrap e-gift__cert-shipment-item afbf_inputtext_bg" id="error_<?= $arField["CODE"] ?>">
                                    <input type="text"
                                           name="FIELDS[<?= $arField["CODE"] ?>]"
                                           value=""
                                           class="form-field__required-input js_field-required-input"
                                           onfocus="this.removeAttribute('readonly')"
                                           readonly
                                           id="form-register-mail">
                                    <label class="form-field__required-label afbf_inputtext" for="form-register-mail"><?=$arField["NAME"]?><span class="required">*</span></label>

                                    <? if ($arField["CODE"] == "EMAIL_" . $ALX): ?>
                                        <div class="afbf_error_text"><?= GetMessage("AFBF_ERROR_TEXT_EMAIL") ?></div>
                                    <? elseif ($arField['REQUIRED'] == 'Y'): ?>
                                        <div class="afbf_error_text"><?= GetMessage('AFBF_ERROR_TEXT') ?></div>
                                    <? endif ?>

                                </div>

                                <div class="e-gift__cert-shipment-info e-gift__cert-shipment-item">На этот Email будет отправлен
                                    сертификат
                                </div>
                            </div>


                        <?elseif ($arField['CODE'] == 'DATE_'.$ALX):?>

                            <div class="e-gift__cert-shipment-row">
                                <div class="form-field e-gift__cert-shipment-item e-gift__cert-shipment-item--date afbf_inputtext_bg" id="error_<?= $arField["CODE"] ?>">
                                    <input  name="FIELDS[<?= $arField["CODE"] ?>]"
                                           value=""
                                           class="form-field__input"
                                           type="text"
                                           onclick="BX.calendar({node: this, field: this, bTime: false});"
                                           placeholder="<?=$arField["NAME"]?>">
                                </div>

                                <? if ($arField["CODE"] == "EMAIL_" . $ALX): ?>
                                    <div class="afbf_error_text"><?= GetMessage("AFBF_ERROR_TEXT_EMAIL") ?></div>
                                <? elseif ($arField['REQUIRED'] == 'Y'): ?>
                                    <div class="afbf_error_text"><?= GetMessage('AFBF_ERROR_TEXT') ?></div>
                                <? endif ?>

                                <div class="e-gift__cert-shipment-info e-gift__cert-shipment-item">Если дата не указана,
                                    сертификат
                                    будет отправлен сразу после оплаты
                                </div>
                            </div>


                        <?elseif ($arField['CODE'] == 'NAME_OTPRAVITEL_'.$ALX):?>

                            <div class="e-gift__cert-shipment-row">
                                <div class="form-field e-gift__cert-shipment-item afbf_inputtext_bg" id="error_<?= $arField["CODE"] ?>">
                                    <input name="FIELDS[<?= $arField["CODE"] ?>]"
                                           value=""
                                           class="form-field__input"
                                           type="text"
                                           placeholder="<?=$arField["NAME"]?>">
                                </div>

                                <? if ($arField["CODE"] == "EMAIL_" . $ALX): ?>
                                    <div class="afbf_error_text"><?= GetMessage("AFBF_ERROR_TEXT_EMAIL") ?></div>
                                <? elseif ($arField['REQUIRED'] == 'Y'): ?>
                                    <div class="afbf_error_text"><?= GetMessage('AFBF_ERROR_TEXT') ?></div>
                                <? endif ?>

                                <div class="e-gift__cert-shipment-info e-gift__cert-shipment-item">От этого имени будет
                                    отправлен сертификат.
                                    Если Вы хотите отправить анонимный
                                    подарок оставьте это поле пустым
                                </div>
                            </div>

                        <?endif;?>

                    <? endif ?>
                </div>

            <? endforeach ?>


            <? if ($arParams["USE_CAPTCHA"]): ?>
                <? if ($arParams["CAPTCHA_TYPE"] != 'recaptcha'): ?>
                    <div class="afbf_item_pole item_pole__captcha required">

                        <? if ($fVerComposite) $frame = $this->createFrame()->begin('loading... <img src="/bitrix/themes/.default/start_menu/main/loading.gif">'); ?>
                        <? $capCode = $GLOBALS["APPLICATION"]->CaptchaGetCode();
                        $_SESSION['ALX_CAPTHA_CODE'] = $capCode; ?>
                        <input type="hidden" id="alx_fb_captchaSid_<?= $ALX ?>" name="captcha_sid"
                               value="<?= htmlspecialcharsEx($capCode) ?>">
                        <div class="afbf_pole_captcha">
                            <img class="image" id="alx_cm_CAPTCHA_<?= $ALX ?>"
                                 src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsEx($capCode) ?>"
                                 width="180"
                                 height="40">
                            <? if ($arParams["CHANGE_CAPTCHA"] == "Y"): ?>
                                <span class="afbf_captcha_reload"
                                      onclick="ALX_ChangeCaptcha('<?= $ALX ?>');return false;"></span>
                            <? else: ?>
                                <span class="afbf_captcha_reload"
                                      onclick="capCode='<?= htmlspecialcharsEx($capCode) ?>';ALX_ReloadCaptcha(capCode,'<?= $ALX ?>');return false;"></span>
                            <? endif; ?>
                        </div>
                        <? if ($fVerComposite) $frame->end(); ?>
                        <div class="afbf_name"><?= GetMessage("ALX_TP_MESSAGE_INPUTF") ?> <span
                                    class="afbf_required_text">*</span></div>
                        <div class="afbf_inputtext_bg"><input type="text" class="afbf_inputtext"
                                                              id="captcha_word_<?= $ALX ?>"
                                                              name="captcha_word" size="30" maxlength="50" value="">
                        </div>
                        <div class="afbf_error_text"><?= GetMessage("ALX_CP_WRONG_CAPTCHA") ?></div>
                    </div>
                <? else: ?>
                    <? if (isset($arResult["SITE_KEY"])): ?>
                        <div class="afbf_item_pole required is_filled">
                            <div class="afbf_name"><?= GetMessage("ALX_TP_MESSAGE_RECAPTCHA") ?><span
                                        class="afbf_required_text">*</span></div>

                            <? if ($fVerComposite) $frame2 = $this->createFrame()->begin('loading... <img src="/bitrix/themes/.default/start_menu/main/loading.gif">'); ?>
                            <div class="afbf_pole_captcha">
                                <div class="g-recaptcha" id="html_element_recaptcha_<?= $ALX ?>"
                                     onload="AltasibFeedbackOnload_<?= $ALX ?>()"
                                     data-sitekey="<?= $arResult["SITE_KEY"] ?>"></div>
                                <span class="afbf_captcha_reload" onclick="grecaptcha.reset();return false;"></span>
                            </div>

                            <script type="text/javascript">
                                var AltasibFeedbackOnload_<?=$ALX?> = function () {
                                    grecaptcha.render('html_element_recaptcha_<?=$ALX?>', {
                                        'sitekey': '<?=$arResult["SITE_KEY"];?>',
                                        'theme': '<?=$arParams["RECAPTCHA_THEME"];?>',
                                        'type': '<?=$arParams["RECAPTCHA_TYPE"];?>'
                                    });
                                };
                                <?                            if($arParams['ALX_LINK_POPUP'] == 'Y'):?>
                                $.getScript('https://www.google.com/recaptcha/api.js?onload=AltasibFeedbackOnload_<?=$ALX?>&render=explicit&hl=<?=LANGUAGE_ID?>')
                                    .fail(function (jqxhr, settings, exception) {
                                        console.log('Error loading google :)')
                                    });
                                <?                            endif?>
                            </script>

                            <div class="afbf_error_text"><?= GetMessage("ALX_CP_WRONG_RECAPTCHA_MIR") ?></div>
                            <? if ($fVerComposite) $frame2->end(); ?>
                        </div>
                    <? endif; ?>
                <? endif; ?>
            <? endif ?>

            <? /*if($arParams['AGREEMENT']=='Y')
			{
				$cAddClass = $arParams['CHECKBOX_TYPE'] == 'TOGGLE' ? ' toggle' : '';
				if(!isset($_POST["FIELDS"][$arField["CODE"]]) && !isset($arResult["FORM_ERRORS"]["EMPTY_FIELD"][$arField["CODE"]])):?>
				<div class="afbf_item_pole required" id="afbf_agreement">
					<div class="afbf_checkbox<?=$cAddClass?>">
						<label for="alx_fb_agreement<?=$ALX?>" style="margin-left: 0;">
							<input id="alx_fb_agreement<?=$ALX?>" type="checkbox" name="alx_fb_agreement" value="yes" />
							<span class="afbf_checkbox_box">
								<span class="afbf_checkbox_check"></span>
							</span><?=GetMessage("AFBF_AGREEMENT")?></label><br />
					</div>
	<?				else:?>
					<div class="afbf_checkbox<?=$cAddClass?>">
						<label for="alx_fb_agreement<?=$ALX?>">
							<input id="alx_fb_agreement<?=$ALX?>" type="checkbox" name="alx_fb_agreement" value="yes" />
							<span class="afbf_checkbox_box">
								<span class="afbf_checkbox_check"></span>
							</span><?=GetMessage("AFBF_AGREEMENT")?></label><br />						
					</div>
<?				endif;?>				
				<div class="afbf_error_text"><?=GetMessage('AFBF_ERROR_TEXT_AGREEMENT')?></div>	
				</div>					
				<?				
			}*/

            ?>
            <? if ($arParams['USER_CONSENT'] == 'Y'): ?>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.userconsent.request",
                    "altasib_fb",
                    array(
                        "ID" => $arParams["USER_CONSENT_ID"],
                        "IS_CHECKED" => $arParams["USER_CONSENT_IS_CHECKED"],
                        "AUTO_SAVE" => "Y",
                        "INPUT_NAME" => "alx_fb_agreement",
                        "IS_LOADED" => $arParams["USER_CONSENT_IS_LOADED"],
                        "REPLACE" => array(
                            'button_caption' => GetMessage('ALX_TP_MESSAGE_SUBMIT'),
                            'fields' => $arrFields_userconsent,
                            'INPUT_LABEL' => $arParams["USER_CONSENT_INPUT_LABEL"],
                        ),
                    ),
                    $component,
                    array("HIDE_ICONS" => "Y", "ACTIVE_COMPONENT" => "Y")
                ); ?>
                <div class="afbf_error_text"><?= GetMessage('AFBF_ERROR_TEXT_AGREEMENT') ?></div>
                <script>
                    BX.message({
                        MAIN_USER_CONSENT_REQUEST_TITLE: '<?=getMessage('MAIN_USER_CONSENT_REQUEST_TITLE')?>',
                        MAIN_USER_CONSENT_REQUEST_BTN_ACCEPT: '<?=getMessage('MAIN_USER_CONSENT_REQUEST_BTN_ACCEPT')?>',
                        MAIN_USER_CONSENT_REQUEST_BTN_REJECT: '<?=getMessage('MAIN_USER_CONSENT_REQUEST_BTN_REJECT')?>',
                        MAIN_USER_CONSENT_REQUEST_LOADING: '<?=getMessage('MAIN_USER_CONSENT_REQUEST_LOADING')?>',
                        MAIN_USER_CONSENT_REQUEST_ERR_TEXT_LOAD: '<?=getMessage('MAIN_USER_CONSENT_REQUEST_ERR_TEXT_LOAD')?>'

                    });
                </script>
            <? $path_userconsent = $this->__folder . "/bitrix/main.userconsent.request/altasib_fb"; ?>
                <script type="text/javascript" src="<?= $path_userconsent ?>/user_consent.js"></script>
                <link href="<?= $path_userconsent ?>/user_consent.css" type="text/css" rel="stylesheet"/>

                <? //include($path.'/lang/ru/user_consent.php');?>
            <? endif; ?>
            <? echo bitrix_sessid_post() ?>

            
            
            
            
            <div class="e-gift__cert-shipment-row e-gift__cert-shipment-footer">
                <button type="submit" name="SEND_FORM" class="form-submit btn e-gift__cert-shipment-item">
                    Купить e gift

                    <svg width="25" height="9">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                             xlink:href="#svg-arrow-right"></use>
                    </svg>
                </button>

                <div class="e-gift__cert-shipment-info e-gift-cert__shipment-info--submit e-gift__cert-shipment-item">
                    <p>Оплата сертификата только банковской картой</p>
                    <p>Сертификат может быть использован в любом
                        магазине КОСМО и при покупке в интернет
                        магазине</p>
                </div>
            </div>

        </div>


    </div>

</form>