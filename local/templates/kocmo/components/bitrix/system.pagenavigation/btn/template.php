<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"] . "&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?" . $arResult["NavQueryString"] : "");


/*AjaxContent::Start('AJAX_BTN_JS') ;

    ....

 AjaxContent::Finish('AJAX_BTN_JS') */

if ($arResult["NavPageCount"] > 1 && $arResult["NavPageCount"] != $arResult['NavPageNomer']):?>
    <div class="button-more-wrap">
        <div class="suggestions__btn  MY_AJAX_BTN_JS" data-Page="1">
            Показать больше
            <svg width="9" height="16">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-down"></use>
            </svg>
        </div>
    </div>

    <script type="text/javascript">
        $('body').on('click', '.MY_AJAX_BTN_JS', function () {
            var count_page = +'<?=$arResult["NavPageCount"]?>';

            var page = $('#AJAX_BTN_JS').attr('data-Page');
            if (!page) {
                $('#AJAX_BTN_JS').attr('data-Page', 1);
                page = 1;
            }
            if (+page <= +count_page) {
                var url = '<?=URL()?>?PAGEN_<?=$arResult['NavNum']?>=' + (+page + 1);
                $.post(
                    url,
                    {
                        CONTENT_ID: "AJAX_BTN_JS",
                        ACTION: "ajax"
                    },
                    onAjaxSuccess
                );

                function onAjaxSuccess(data) {
                    $('#AJAX_BTN_JS').append(data);
                    $('#AJAX_BTN_JS').attr('data-Page', (+page + 1));

                    if (+$('#AJAX_BTN_JS').attr('data-Page') >= +count_page) {
                        $('.MY_AJAX_BTN_JS').remove();
                    }

                    ReloadAjax();
                }

            }
        })
    </script>

<? endif; ?>