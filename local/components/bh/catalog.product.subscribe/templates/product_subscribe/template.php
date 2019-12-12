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

?>

<div id="popup-preorder" class="popup popup-preorder" style="display: none;">
    <h2 class="popup__title">ПРЕДВАРИТЕЛЬНЫЙ ЗАКАЗ</h2>

    <form action="" method="post" id="form_prod_popup" class="preorder__form">
        <input type="hidden" name="ACTION" value="Subscribe">
        <input type="hidden" name="METHOD" value="Add">
        <input type="hidden" name="PARAMS[ITEM_ID]" value="<?= $arParams['PRODUCT_ID'] ?>">
        <input type="hidden" name="PARAMS[USER_ID]" value="<?=$_SESSION['SESS_AUTH']['USER_ID']?>">
        <input type="hidden" name="PARAMS[SITE_ID]" value="<?=SITE_ID?>">
        <div class="popup-preorder__content">
            <div class="popup-preorder__img">
                <img src="<?= $arParams['DETAIL_PICTURE'] ?>" alt="">
            </div>
            <div class="popup-preorder__product-info-wrap">
                <div class="popup-preorder__product-title"><?= $arParams['NAME'] ?></div>
                <div class="popup-preorder__product-description">
                    <?= $arParams['PREVIEW_TEXT'] ?>
                </div>
                <div class="popup-preorder__product-sku">Артикул: <?= $arParams['ARTIKUL'] ?></div>
                <div class="popup-preorder__product-color"></div>
            </div>
        </div>
        <div class="popup-preorder__info">
            Чтобы получить уведомление о появлении товара, укажите свой email:
        </div>
        <div class="alert alert-danger subscribe-message" style="display: none;"></div>
        <div class="popup-preorder__submit">
            <div class="popup-preorder__submit-field form-field__required-wrap">
                <input type="email" name="PARAMS[EMAIL]"
                       required
                       class="popup-preorder__input btn form-field__required-input js_field-required-input"
                       id="preorder-submit"
                       value="<?=$_SESSION['SESS_AUTH']['EMAIL']?>">
                <label class="form-field__required-label" for="preorder-submit">Введите ваш email <span
                            class="required">*</span></label>
            </div>

            <input type="submit" value="Отправить" class="popup-preorder__submit-btn">
        </div>
    </form>
</div>