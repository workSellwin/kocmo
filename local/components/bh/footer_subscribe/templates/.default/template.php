<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$frame = $this->createFrame()->begin();
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
<?
$frame = $this->createFrame("subscribe-form", false)->begin();
?>

<div class="footer-nav-column">
    <div class="footer-nav__title-submit">подписаться на расслыку</div>
    <div class="box-message" style="margin-bottom: 10px;font-size: 13px;"></div>
    <form id="form_subscribe" class="footer-submit">
        <input type="hidden" name="subscribe_form" value="Y">
        <input class="footer-submit__input field-bordered" type="text" name="EMAIL_SUBSCRIBE"
               onblur="try {rrApi.setEmail(this.value);}catch(e){}"
               placeholder="Введите ваш email">
        <input type="submit" class="btn btn--transparent footer-submit__btn subscribe-btn--js" value="ПОДПИСАТЬСЯ">
    </form>
</div>

<script>
    $(document).ready(function(){
        $('#form_subscribe').submit(function (event) {
            var url = '<?=$APPLICATION->GetCurPage()?>';
            var data = $(this).serialize();
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                success: function(msg){
                    $('.box-message').html(msg);
                }
            });
            return false;
        })
    });
</script>
