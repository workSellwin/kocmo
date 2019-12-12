




    <a href="#" onclick="$.fancybox.close();return false;" class="popup__fancybox-close"></a>

    <h2 class="popup__title">меня заинтересовала вакансия</h2>
    <h3 class="popup__subtitle"></h3>

    <div class="double-separator"></div>




    <form class="popup-vacancies__form" id="f_feedback_<?= $ALX ?>" name="f_feedback_<?= $ALX ?>" action="<?= POST_FORM_ACTION_URI ?>" method="post"
          enctype="multipart/form-data">

        <input type="hidden" id="VAKANSIA" name="FIELDS[VAKANSIA_<?= $ALX ?>]" value="">
        <input type="hidden" class="popup-vacancies__position">
        <input type="hidden" name="FEEDBACK_FORM_<?= $ALX ?>" value="Y"/>

        <? if ($arResult["ELEMENT_NAME"] != "") { ?><input type="hidden" name="ELEMENT_NAME"
                                                           value="<?= $arResult["ELEMENT_NAME"] ?>" /><? } ?>
        <? if ($arResult['ELEMENT_EDIT_LINK'] != "") { ?><input type="hidden" name="ELEMENT_EDIT_LINK"
                                                                value="<?= $arResult['ELEMENT_EDIT_LINK'] ?>" /><? } ?>
        <? if ($arResult["ELEMENT_EDIT_LINK_ID"] != "") { ?><input type="hidden" name="ELEMENT_EDIT_LINK_ID"
                                                                   value="<?= $arResult["ELEMENT_EDIT_LINK_ID"] ?>" /><? } ?>


        <? if ($arParams["ADD_HREF_LINK"] != "N"): ?>
            <input type="hidden" name="HREF_LINK_<?= $ALX ?>"
                   value="<?= "http://" . $_SERVER["SERVER_NAME"] . POST_FORM_ACTION_URI ?>"/>
        <? endif ?>

        <?
        $countArr = count($arResult["FIELDS"]);
        $bFBtext = false;

        $arrFields_userconsent = array();
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

                <? if ($arField["USER_TYPE"] == "HTML"): ?>


                <? elseif ($arField["TYPE"] != "F"):?>

                    <div class="afbf_inputtext_bg form-field__required-wrap" id="error_<?= $arField["CODE"] ?>">


                            <input type="text" size="40" id="<?= $arField["CODE"] ?>1"
                                   name="FIELDS[<?= $arField["CODE"] ?>]" value="<?= $arField["DEFAULT_VALUE"] ?>"
                                   class="form-field__required-input js_field-required-input"
                                   onfocus="this.removeAttribute('readonly')"
                                   onblur="if(this.value==''){this.value='<?= $arField["DEFAULT_VALUE"] ?>'}"
                                   onclick="if(this.value=='<?= $arField["DEFAULT_VALUE"] ?>'){this.value=''}"/>


                        <label class="form-field__required-label" for="card-registration-number"><?=$arField['NAME']?>
                            <?if($arField['REQUIRED'] == 'Y'):?>
                                <span class="required">*</span>
                            <?endif;?>
                        </label>

                        <? if ($arField["CODE"] == "EMAIL_" . $ALX): ?>
                            <div class="afbf_error_text"><?= GetMessage("AFBF_ERROR_TEXT_EMAIL") ?></div>
                        <? elseif ($arField['REQUIRED'] == 'Y'): ?>
                            <div class="afbf_error_text"><?= GetMessage('AFBF_ERROR_TEXT') ?></div>
                        <? endif ?>
                    </div>

                <? endif ?>
            </div>
            <?
            if (!$bFBtext && ($arResult["FIELDS"][$key + 1]["SORT"] > 10000 || $key == $countArr - 1)):
                echo $strFBtext;
                $bFBtext = true;
            endif; ?>

        <? endforeach ?>
        <?
        if (!$bFBtext) {
            echo $strFBtext;
            $bFBtext = true;
        }
        ?>
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
                             src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsEx($capCode) ?>" width="180"
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
                                                          id="captcha_word_<?= $ALX ?>" name="captcha_word" size="30"
                                                          maxlength="50" value=""></div>
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


        <div class="form-field">
            <textarea name="FEEDBACK_TEXT_<?=$ALX?>" placeholder="Напишите немного о себе"></textarea>
        </div>


        <div class="popup-vacancies__form-footer">
            <input type="submit" name="SEND_FORM" id="fb_close_<?= $ALX ?>" value="Откликнуться на вакансию"
                   class="popup-vacancies__form-submit btn">

            <label class="label-wrap-file">
                <span class="file-btn">
                    <svg width="15" height="15">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-clip"></use>
                    </svg>
                    Прикрепить резюме
                </span>
                <span class="file-title"></span>
                <input type="hidden" name="codeFileFields[<?= $arField['CODE'] ?>]" value="<?= $arField['CODE'] ?>">
                <input type="hidden" name="FIELDS[myFile][<?= $arField["CODE"] ?>]">
                <input type="file" name="myFile[<?= $arField['CODE'] ?>]" class="filename js_filename">
            </label>
        </div>

    </form>


    <div class="preloader" style="display: none;">
        <svg width="64" height="64">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-preloader"></use>
        </svg>
    </div>