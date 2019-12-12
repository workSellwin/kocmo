<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Lui\Kocmo\Helper\Order;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 */
if ($arParams["SET_TITLE"] == "Y") {
    $APPLICATION->SetTitle(Loc::getMessage("SOA_ORDER_COMPLETE"));
}
?>

<? if (!empty($arResult["ORDER"])): ?>
    <?
    $order = $arResult['ORDER'];
    $uid = $order['XML_ID'];
    /*
           $ob = new \Lui\Kocmo\Request\Order($order['ID']);
           $data = $ob->Run();
        $uid = $data['UID'];
    */
    ?>
    <div class="order-confirmation-wrap">
        <div class="order-confirmation">
            <div class="order-confirmation__envelop"></div>
            <div class="order-confirmation__letter">
                <div class="order-confirmation__letter-header">
                    <div class="order-confirmation__letter-title">
                        Спасибо что оформили заказ<br>
                        в нашем <b>магазине КОСМО</b>!
                    </div>
                    <div class="order-confirmation__letter-transaction">Номер
                        заказа: <?= $order['ACCOUNT_NUMBER'] ?></div>
                    <? if ($uid) { ?>
                        <div class="order-confirmation__letter-transaction">UID
                            заказа: <?= $uid ?></div>
                    <? } ?>
                </div>

                <div class="order-confirmation__letter-details">
                    <div class="order-confirmation__letter-details-item">
                        Доставка: <?= Order::GetDeliveryName($order['DELIVERY_ID']) ?>
                    </div>
                    <div class="order-confirmation__letter-details-item">
                        Дата доставки: 22.04.19
                    </div>
                    <div class="order-confirmation__letter-details-item">
                        Оплата: <?= Order::GePaymentName($order['PAY_SYSTEM_ID']) ?>
                    </div>
                    <div class="order-confirmation__letter-details-item">
                        Сумма заказа: <?= $order['PRICE'] ?> руб.
                    </div>
                </div>

                <div class="order-confirmation__letter-contacts">
                    <div class="order-confirmation__letter-contacts-info">
                        <span>Мы с радостью ответим</span>
                        на все ваши вопросы!
                    </div>

                    <div class="order-confirmation__letter-contacts-phone">
                        666-55-44
                        <span>все операторы</span>
                    </div>
                </div>

                <a href="/catalog/" class="order-confirmation__lnk btn btn--transparent">
                    ВЕрнуться к покупкам

                    <svg width="21" height="9">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-pagination-right"></use>
                    </svg>
                </a>
            </div>


        </div>
    </div>
    <?
    if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y') {
        if (!empty($arResult["PAYMENT"])) {
            ?>
            <div id="none-form-pay" style="display: none"><?
            foreach ($arResult["PAYMENT"] as $payment) {
                if ($payment["PAID"] != 'Y') {
                    if (!empty($arResult['PAY_SYSTEM_LIST'])
                        && array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])
                    ) {
                        $arPaySystem = $arResult['PAY_SYSTEM_LIST_BY_PAYMENT_ID'][$payment["ID"]];
                        if (empty($arPaySystem["ERROR"])) {
                            ?>
                            <?= str_replace('target="_blank"', '', $arPaySystem["BUFFERED_OUTPUT"]) ?>
                            <?
                        } else {
                            ?>
                            <span style="color:red;"><?= Loc::getMessage("SOA_ORDER_PS_ERROR") ?></span>
                            <?
                        }
                    } else {
                        ?>
                        <span style="color:red;"><?= Loc::getMessage("SOA_ORDER_PS_ERROR") ?></span>
                        <?
                    }
                }
            }
            ?>
            <script>
                $(document).ready(function () {
                    $('#none-form-pay form').submit();
                });
            </script>
            </div><?
        }
    } else {
        ?>
        <br/><strong><?= $arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR'] ?></strong>
        <?
    }
    ?>

<? else: ?>

    <b><?= Loc::getMessage("SOA_ERROR_ORDER") ?></b>
    <br/><br/>

    <table class="sale_order_full_table">
        <tr>
            <td>
                <?= Loc::getMessage("SOA_ERROR_ORDER_LOST", ["#ORDER_ID#" => htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"])]) ?>
                <?= Loc::getMessage("SOA_ERROR_ORDER_LOST1") ?>
            </td>
        </tr>
    </table>

<? endif ?>

<?
global $USER;
$ANONYMOUS_USER = 3;
if($USER->GetID() == $ANONYMOUS_USER){
    $USER->Logout();
}


?>