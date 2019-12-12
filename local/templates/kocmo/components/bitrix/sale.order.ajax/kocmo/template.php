<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CUser $USER
 * @var SaleOrderAjax $component
 * @var string $templateFolder
 */

$context = Main\Application::getInstance()->getContext();
$request = $context->getRequest();


$arDeliveryParams = [
    '4' => [
        'additional' => $arResult['BLOCK_PROPS']['2'],
        'info' => [
            'title' => 'Стоимость: 10 руб',
            'class-subtitle' => 'basket-shipment__item-info-second-title',
            'subtitle' => 'При заказе от 40 руб бесплатно',
        ],
    ],
    '2' => [
        'additional' => $arResult['BLOCK_PROPS']['2'],
        'info' => [
            'title' => 'Бесплатно',
            'class-subtitle' => 'basket-shipment__item-info-second-title--free',
            'subtitle' => 'При заказе от 40 руб бесплатно',
        ],
    ],
    '3' => [
        'additional' => [

        ],
        'info' => [
            'title' => 'Бесплатно',
            'class-subtitle' => 'basket-shipment__item-info-second-title--free',
            'subtitle' => '',
        ],
    ],
];
if ($arResult['ERROR'] and $_REQUEST['STEP'] == 2) {
    $arResult['ERROR'] = array_unique($arResult['ERROR']);
   // echo "<div class='alert-danger alert'>" . implode('<br>', $arResult['ERROR']) . "</div>";
}

if (strlen($request->get('ORDER_ID')) > 0) {
    include(Main\Application::getDocumentRoot() . $templateFolder . '/confirm.php');
} elseif ($arParams['DISABLE_BASKET_REDIRECT'] === 'Y' && $arResult['SHOW_EMPTY_BASKET']) {
    include(Main\Application::getDocumentRoot() . $templateFolder . '/empty.php');
} elseif ($_REQUEST['STEP'] == 2) {
    include(Main\Application::getDocumentRoot() . $templateFolder . '/step2.php');
} else {
    $arDelivery = $arResult['DELIVERY'];
    $arPaySystem = $arResult['PAY_SYSTEM'];


    ?>
    <input type="hidden" name="PERSON_TYPE" value="1">
    <input type="hidden" name="SITE_ID" value="<?= SITE_ID ?>">
    <input type="hidden" name="soa-action" value="saveOrderAjax">
    <div class="basket-additional container">
        <? if ($arDiscountProp = $arResult['BLOCK_PROPS']['6']) { ?>
            <div class="basket-additional__item basket-discount">
                <div class="basket-additional__title">Скидки:</div>
                <div class="basket-discount__inner">
                    <? foreach ($arDiscountProp as $blDiscount) { ?>

                        <? if ($blDiscount['ERROR']) { ?>
                            <div class="alert alert-danger"><?= $blDiscount['ERROR'] ?></div>
                        <? } ?>
                        <? if ($blDiscount['INFO']) { ?>
                            <div class="alert alert-success"><?= $blDiscount['INFO'] ?></div>
                        <? } ?>
                        <? if ($blDiscount['CODE'] == 'CARD_COSMO') { ?>
                            <? if (!$blDiscount['VALUE']) {
                                continue;
                            } ?>
                            <input name="ORDER_PROP_<?= $blDiscount['ID'] ?>" value="<?= $blDiscount['VALUE'] ?>"
                                   type="hidden">
                            <div class="basket-discount__item">
                                <div class="basket-discount__item-title"><?= $blDiscount['NAME'] ?></div>
                                <div class="basket-discount__item-input full">
                                    <div class="form-field">
                                        <div class="form-field__input"> <?= $blDiscount['VALUE'] ?></div>
                                    </div>
                                </div>
                            </div>
                        <? } else { ?>
                            <div class="basket-discount__item">
                                <div class="basket-discount__item-title"><?= $blDiscount['NAME'] ?></div>
                                <div class="basket-discount__item-input">
                                    <div class="form-field">
                                        <input name="ORDER_PROP_<?= $blDiscount['ID'] ?>"
                                               value="<?= $blDiscount['VALUE'] ?>"
                                               class="form-field__input <? if ($blDiscount['READONLY'] == "Y") { ?> readonly<? } ?>"
                                               type="text"

                                               placeholder="<?= $blDiscount['DESCRIPTION'] ?>">
                                    </div>
                                </div>
                                <div class="basket-discount__item-button">
                                    <button class="btn basket-additional__btn"
                                            name="SUBMIT_PROP_<?= $blDiscount['ID'] ?>">
                                        <svg width="22" height="17">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 xlink:href="#svg-checked"></use>
                                        </svg>
                                        применить
                                    </button>
                                </div>
                            </div>
                        <? } ?>
                    <? } ?>
                </div>
            </div>
        <? } ?>
        <div class="basket-additional__item basket-shipment">
            <div class="basket-additional__title">Доставка:</div>

            <div class="basket-shipment__inner">
                <? foreach ($arDelivery as $id => $arDeliver) { ?>
                    <? $param = $arDeliveryParams[$id] ?>
                    <? $info = $param['info'] ?>
                    <? $additional = $param['additional'] ?>
                    <div class="basket-shipment__item-wrap basket-radio-wrap">
                        <div class="basket-shipment__item">
                            <label class="radio js_radio">
                                <input type="radio" class="js_basket-radio"
                                       name="DELIVERY_ID" <?= $arDeliver['CHECKED'] == 'Y' ? 'checked' : ''; ?>
                                       value="<?= $arDeliver['ID'] ?>">
                                <div class="basket-shipment__item-info">
                                    <div class="basket-shipment__item-info-title"><?= $arDeliver['NAME'] ?></div>
                                    <? if ($arDeliver['DESCRIPTION']) { ?>
                                        <div class="basket-shipment__item-info-subtitle">
                                            <?= $arDeliver['DESCRIPTION'] ?>
                                        </div>
                                    <? } ?>
                                </div>
                                <div class="basket-shipment__item-info">
                                    <div class="<?= $info['class-subtitle'] ?>">
                                        <?= $info['title'] ?>
                                    </div>
                                    <? if ($info['subtitle']) { ?>
                                        <div class="basket-shipment__item-info-subtitle">
                                            <?= $info['subtitle'] ?>
                                        </div>
                                    <? } ?>
                                </div>
                            </label>
                        </div>
                        <div class="basket-shipment__item-additional" style="display: none;">
                            <? foreach ($additional as $prop) { ?>
                                <? switch ($prop['TYPE']) {
                                    case 'SELECT':
                                        ?>
                                        <div class="form-field">
                                            <select name="ORDER_PROP_<?= $prop['ID'] ?>" class="js_custom-select">
                                                <option value="" selected><?= $prop['NAME'] ?></option>
                                                <?
                                                foreach ($prop['OPTIONS'] as $inv => $opt) {
                                                    ?>
                                                    <option value="<?= $inv ?>"><?= $opt ?></option>
                                                    <?
                                                } ?>
                                            </select>
                                        </div>
                                        <?
                                        break;
                                    case 'DATE':
                                        ?>
                                        <div class="form-field form-field--date">
                                            <input name="ORDER_PROP_<?= $prop['ID'] ?>"
                                                   value=""
                                                   class="form-field__input"
                                                   type="text"
                                                   onclick="BX.calendar({node: this, field: this, bTime: false});"
                                                   placeholder="<?= $prop['NAME'] ?>">
                                        </div>
                                        <?
                                        break;
                                } ?>
                            <? } ?>
                            <? if ($arStory = $arDeliver['STORE']) { ?>
                                <div class="form-field">
                                    <select name="BUYER_STORE" class="js_custom-select">
                                        <option value="" selected>Выберите адрес магазина</option>
                                        <? foreach ($arStory as $story) { ?>
                                            <option value="<?= $story['ID'] ?>"><?= $story['TITLE'] ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                            <? } ?>
                        </div>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
    <hr>

    <? if ($arBlockPrice = $arResult['BLOCK_PRICE']) { ?>

        <? AjaxContent::Start('ajax_basket_price_container') ?>

        <div class="basket-price container">
            <div class="basket-price__details">
                <div class="basket-price__item sum">
                    <div class="basket-price__item-title">Сумма заказа:</div>
                    <div class="basket-price__item-price">
                        <span class="basket-price__item-sum"><?= number_format_kocmo($arBlockPrice['Sum']) ?></span>
                        <span class="basket-price__item-currency"> руб</span>
                    </div>
                </div>
                <? if ($arBlockPrice['Economy']) { ?>
                    <div class="basket-price__item economy">
                        <div class="basket-price__item-title">Ваша экономия:</div>
                        <div class="basket-price__item-price">
                            <span class="basket-price__item-sum"><?= number_format_kocmo($arBlockPrice['Economy']) ?></span>
                            <span class="basket-price__item-currency"> руб</span>
                        </div>
                    </div>
                <? } ?>
                <? if ($arBlockPrice['Certificate']) { ?>
                    <div class="basket-price__item certificate">
                        <div class="basket-price__item-title">Оплачено сертификатом:</div>
                        <div class="basket-price__item-price">
                            <span class="basket-price__item-sum"><?= number_format_kocmo($arBlockPrice['Certificate']) ?></span>
                            <span class="basket-price__item-currency"> руб</span>
                        </div>
                    </div>
                <? } ?>
                <? if ($arBlockPrice['Delivery']) { ?>
                    <div class="basket-price__item delivery">
                        <div class="basket-price__item-title">Курьерская доставка:</div>
                        <div class="basket-price__item-price">
                            <span class="basket-price__item-sum"><?= number_format_kocmo($arBlockPrice['Delivery']) ?></span>
                            <span class="basket-price__item-currency"> руб</span>
                        </div>
                    </div>
                <? } ?>
            </div>

            <div class="basket-price__divider"></div>

            <div class="basket-price__total">
                Итого:
                <span class="basket-price__total-sum"><?= number_format_kocmo($arBlockPrice['Total']) ?></span>
                <span class="basket-price__total-currency">руб</span>
            </div>
        </div>

        <? AjaxContent::Finish('ajax_basket_price_container') ?>

    <? } ?>

    <hr>

    <div class="basket-payment container">
        <div class="basket-payment__title">Оплата:</div>

        <div class="basket-payment__inner">
            <? foreach ($arPaySystem as $paySystem) { ?>
                <?
                $id = $paySystem['ID'];
                ?>
                <label class="radio js_radio basket-radio-wrap basket-payment__item">
                    <input type="radio" class="js_basket-radio" name="PAY_SYSTEM_ID"
                        <?= $paySystem['CHECKED'] == 'Y' ? 'checked' : ''; ?> value="<?= $id ?>">
                    <div class="basket-payment__item-info">
                        <div class="basket-payment__item-info-title"><?= $paySystem['NAME'] ?></div>
                        <? if ($paySystem['DESCRIPTION']) { ?>
                            <div class="basket-payment__item-info-subtitle">
                                <?= $paySystem['DESCRIPTION'] ?>
                            </div>
                        <? } ?>
                    </div>
                </label>
            <? } ?>
        </div>

        <div class="basket-payment__logo" style="display: none">
            <div class="basket-payment__logo-item">
                <img src="/assets/images/basket-payment/Visa.png" alt="">
            </div>

            <div class="basket-payment__logo-item">
                <img src="/assets/images/basket-payment/mc.png" alt="">
            </div>

            <div class="basket-payment__logo-item">
                <img src="/assets/images/basket-payment/webpay.png" alt="">
            </div>

            <div class="basket-payment__logo-item">
                <img src="/assets/images/basket-payment/raschet.png" alt="">
            </div>

            <div class="basket-payment__logo-item">
                <img src="/assets/images/basket-payment/blc.png" alt="">
            </div>
        </div>
    </div>

    <div class="basket-footer container">
        <div class="basket-footer__inner">
            <a href="/catalog/" class="basket-footer__back">
                <svg width="25" height="17">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-pagination-left"></use>
                </svg>

                Вернуться в каталог
            </a>
            <button type="submit" name="STEP" value="2" class="basket-footer__submit btn">
                ОФормить заказ
                <svg width="26" height="16">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-right-bold"></use>
                </svg>
            </button>
        </div>
    </div>

    </form>
<? } ?>
