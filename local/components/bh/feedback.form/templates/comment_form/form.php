<?
$HB = new highloadApi(3);
$Selectoptionprofile = $HB->getElementHighload(array(), array('UF_SORT' => 'ASC'));
$arSelectoptionprofile = array();
foreach ($Selectoptionprofile as $val) {
    //Возвраст
    if ($val['UF_TYPE'] == 1) {
        $arSelectoptionprofile['AGE_' . $ALX][] = $val;
    }
    //Цвет глаз
    if ($val['UF_TYPE'] == 2) {
        $arSelectoptionprofile['EYE_COLOR_' . $ALX][] = $val;
    }
    //Цвет волос
    if ($val['UF_TYPE'] == 3) {
        $arSelectoptionprofile['HAIR_COLOR_' . $ALX][] = $val;
    }
    //Тип кожи
    if ($val['UF_TYPE'] == 4) {
        $arSelectoptionprofile['SKIN_TYPE_' . $ALX][] = $val;
    }

}

?>


<script type="text/javascript">
    $(document).ready(function () {
        if (typeof $.dropdown != 'undefined') {
            $(".afbf_item_pole .afbf_select").dropdown({
                "dropdownClass": "feedback_dropdown"
            });
        }
    });
</script>
<? if (trim($arParams["ALX_POPUP_TITLE"]) != ""): ?><h1
        align="center"><?= trim($arParams["ALX_POPUP_TITLE"]) ?></h1><? endif; ?>
<? echo CFile::ShowImage($arResult["POPUP_PICTURE"]["src"], 50, 40, "border=0", "", false) . "<br>"; ?>
<? if ($arResult["RIGHT_TO_EDIT"]): ?>
    <a href="<?= $arResult["ELEMENT_EDIT_LINK"] ?>"><? echo $arResult["ELEMENT_NAME"] ?></a>
<? else: ?>
    <? echo $arResult["ELEMENT_NAME"] ?>
<? endif; ?>

<div class="alert alert-danger afbf_error_text_main" style="display: none">
    Заполните все поля<br>
</div>

<form id="f_feedback_<?= $ALX ?>" name="f_feedback_<?= $ALX ?>" class="reviews__form" style="display: none;"
      action="<?= POST_FORM_ACTION_URI ?>" method="post"
      enctype="multipart/form-data">

    <input type="hidden" name="FIELDS[PROD_ID_<?= $ALX ?>]"
           value="<?= $arParams['PROD_ID'] ?>"/>

    <input type="hidden" class="RATING" name="FIELDS[RATING_<?= $ALX ?>]"
           value=""/>

    <input type="hidden" name="ACTIVE_FROM"
           value=""/>



    <input type="hidden" name="FEEDBACK_FORM_<?= $ALX ?>" value="Y"/>

        <? if ($arResult["ELEMENT_NAME"] != "") { ?>
            <input type="hidden" name="ELEMENT_NAME" value="<?= $arResult["ELEMENT_NAME"] ?>" /><? } ?>
        <? if ($arResult['ELEMENT_EDIT_LINK'] != "") { ?>
            <input type="hidden" name="ELEMENT_EDIT_LINK" value="<?= $arResult['ELEMENT_EDIT_LINK'] ?>" /><? } ?>
        <? if ($arResult["ELEMENT_EDIT_LINK_ID"] != "") { ?>
            <input type="hidden" name="ELEMENT_EDIT_LINK_ID" value="<?= $arResult["ELEMENT_EDIT_LINK_ID"] ?>" /><? } ?>
        <? if ($arParams["ADD_HREF_LINK"] != "N"): ?>
            <input type="hidden" name="HREF_LINK_<?= $ALX ?>" value="<?= "http://" . $_SERVER["SERVER_NAME"] . POST_FORM_ACTION_URI ?>"/>
        <? endif ?>
        <? if (count($arResult["TYPE_QUESTION"]) >= 1): ?>
            <? /* TYPE_QUESTION */ ?>
        <? endif ?>
        <? $k = 0; ?>
        <?
        $countArr = count($arResult["FIELDS"]);
        $bFBtext = false;
        $strFBtext = '</div><div class="reviews__form-columns-item">';
        ?>
        <? if ((is_array($arParams["PROPERTY_FIELDS"]) && in_array("FEEDBACK_TEXT", $arParams["PROPERTY_FIELDS"]))
            || (
                $arParams["SECTION_FIELDS_ENABLE"] == "Y" && !empty($arResult["CURSECT_FIELDS"])
                && is_array($arResult["CURSECT_FIELDS"]) && in_array("FEEDBACK_TEXT", $arResult["CURSECT_FIELDS"])
            )
        ) {
            $strLen = mb_strlen($arResult["FEEDBACK_TEXT"], 'utf-8');
            $strFBtext = '<div class="afbf_item_pole';
            $strFBtext .= ($strLen > 0) ? ' is_filled' : '';
            $strFBtext .= in_array("FEEDBACK_TEXT_" . $ALX, $arParams["PROPERTY_FIELDS_REQUIRED"]) ? ' required' : '';
            $strFBtext .= '">';

            $strFBtext .= '
					<div class="afbf_inputtext_bg" id="error_EMPTY_TEXT">
						<textarea class="afbf_textarea" cols="10" rows="10" id="EMPTY_TEXT' . $ALX . '" name="FEEDBACK_TEXT_' . $ALX . '">' . $arResult["FEEDBACK_TEXT"] . '</textarea>
					</div>
					<div class="afbf_error_text">' . GetMessage("AFBF_ERROR_TEXT") . '</div>
				</div>';

        } ?>
        <?
        $arrFields_userconsent = array();
       ?>

    <div class="reviews__form-header">
        Добавить отзыв:
    </div>

    <div class="reviews__form-columns">


        <div class="reviews__form-columns-item">

            <?foreach ($arResult["FIELDS"] as $key => $arField):?>
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


                    <? if ($arField["TYPE"] == "L"): ?>
                        <? /* HTML/TEXT */ ?>
                    <? elseif ($arField["USER_TYPE"] == "HTML"): ?>
                        <? /* Date or DateTime */ ?>
                    <?
                    elseif ($arField["USER_TYPE"] == "Date" || $arField["USER_TYPE"] == "DateTime"):?>
                        <? /* ELEMENTS */
                        ?>
                    <? elseif ($arField["TYPE"] == "E"): ?>

                        <? /* SECTIONS */ ?>
                    <? elseif ($arField["TYPE"] == "G"): ?>

                        <? /* STRING */ ?>
                    <? elseif ($arField["TYPE"] != "F"): ?>


                            <div class="form-field afbf_item_pole required" id="error_<?= $arField["CODE"] ?>">


                                <select name="FIELDS[<?= $arField["CODE"] ?>]" class="js_custom-select">
                                    <option value="not_val" selected>Выберите <?= $arField["NAME"] ?></option>

                                    <? foreach ($arSelectoptionprofile as $k => $val): ?>
                                        <? if ($k == $arField["CODE"]): ?>
                                            <? foreach ($val as $va): ?>
                                                <option value="<?= $va['UF_XML_ID'] ?>"><?= $va['UF_NAME'] ?></option>
                                            <? endforeach; ?>
                                        <? endif; ?>
                                    <? endforeach; ?>
                                </select>

                            </div>



                        <? /* FILE */ ?>


                    <? elseif ($arField["TYPE"] == "F"): ?>
                    <? endif ?>
            <? endforeach ?>
        </div>

        <div class="reviews__form-columns-item">

            <div class="reviews__form-columns-item-header">
                <div class="reviews__form-columns-item-header-title">Напишите свой отзыв:</div>

                <div class="reviews__form-columns-item-header-stars-title">
                    <span>Поставьте вашу оценку:</span>
                    <div class="bx-rating">
                        <? for ($i = 1; $i <= 5; $i++):
                               // $className = "fa fa-star reviews-prod";
                                ?>
                            <i class="fa fa-star-o reviews-prod"
                               title="<? echo $name ?>"
                               data-rating-num="<?=$i?>"
                               onmouseover="myJCFlatVote(this, true)"
                               onmouseout="myJCFlatVote(this, false)"
                               onclick="onclickVote(this)">
                            </i>
                        <? endfor; ?>
                    </div>
                </div>
            </div>

            <div class="form-field afbf_item_pole required">
                <textarea name="FEEDBACK_TEXT_<?= $ALX ?>" placeholder="Напишите свой отзыв"></textarea>
            </div>

            <div class="reviews__form-submit-wrap">
                <input type="submit" id="fb_close_<?= $ALX ?>" name="SEND_FORM"
                       class="reviews__form-submit btn btn--transparent" value="опубликовать">
            </div>

        </div>

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


    </div>
</form>

<script>

    function myJCFlatVote(div, flag) {
        var my_div;
        //Left from current
        my_div = div;
        while (my_div = my_div.previousSibling)
        {
            if (flag)
                BX.addClass(my_div, 'bx-star-active');
            else
                BX.removeClass(my_div, 'bx-star-active');
        }

        //current
        my_div = div;
        if (flag)
            BX.addClass(my_div, 'bx-star-active');
        else
            BX.removeClass(my_div, 'bx-star-active');
        //Right from the current
        my_div = div;
        while (my_div = my_div.nextSibling)
        {
            BX.removeClass(my_div, 'bx-star-active');
        }
    }

    function onclickVote($this) {
        var rating = $($this).attr('data-rating-num');
        var cont = $($this).parent().children(".fa");
        $('input.RATING').val(rating);

        cont.each(function(){
            var ret = $(this).attr('data-rating-num');
            if(ret<=rating){
                $(this).removeClass('fa-star-o');
                $(this).addClass('fa-star');
            }else{
                $(this).removeClass('fa-star');
                $(this).addClass('fa-star-o');
            }
        });
    }


  /*  $('boby').on('click', '.reviews__form-submit', function () {
        var id_form = "f_feedback_<?//= $ALX ?>";
        var flag = true;
        console.log(id_form);
        $("#"+id_form+" .required").each(function(){

            if($(this).hasClass('.error_pole')){
                flag = false;
                $('.afbf_error_text_main').show();
            }else{

                $('.afbf_error_text_main').hide();
            }
        });

        if(flag){
            return true;
        }else{
            return false;
        }
    })*/


</script>