<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"] . "&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?" . $arResult["NavQueryString"] : "");


/*AjaxContent::Start('AJAX_BTN_JS') ;

    ....

 AjaxContent::Finish('AJAX_BTN_JS') */

if ($arResult["NavPageCount"] > 1 && $arResult["NavPageCount"] != $arResult['NavPageNomer']):?>

    <div class="button-more-wrap">
        <div class="suggestions_sale__btn  MY_AJAX_BTN_JS">
            Показать больше
            <svg width="9" height="16">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-down"></use>
            </svg>
        </div>
    </div>

    <script type="text/javascript">
        $('#AJAX_BTN_JS').attr('data-Page', '<?=$arResult["NavPageNomer"]?>');

        $('body').on('click', '.MY_AJAX_BTN_JS', function () {
            var count_page = +'<?=$arResult["NavPageCount"]?>';

            var page = $('#AJAX_BTN_JS').attr('data-Page');
            if (!page) {
                $('#AJAX_BTN_JS').attr('data-Page', 1);
                page = 1;
            }
            if (+page <= +count_page) {
                var url = '<?=$APPLICATION->GetCurPageParam('',['PAGEN_'.$arResult['NavNum']])?>';
                var regexp = /\?/gi;
                if(regexp.test(url)){
                    url+='&PAGEN_<?=$arResult['NavNum']?>=' + (+page + 1);
                }else{
                    url+='?PAGEN_<?=$arResult['NavNum']?>=' + (+page + 1);
                }

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


<? if ($arResult["NavPageCount"] > 1) {
    ?>
    <div class="pagination-wrap">
        <ul class="pagination">
            <?
            if ($arResult["bDescPageNumbering"] === true):
                $bFirst = true;
                if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
                    if ($arResult["bSavePage"]):
                        ?>

                        <a class="pagination__prev"
                           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>">
                            <svg width="21" height="9">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-pagination-left"></use>
                            </svg>
                            <?= GetMessage("nav_prev_new") ?></a>
                    <?
                    else:
                        if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"] + 1)):
                            ?>
                            <a class="pagination__prev"
                               href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">
                                <svg width="21" height="9">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                         xlink:href="#svg-pagination-left"></use>
                                </svg>
                                <?= GetMessage("nav_prev_new") ?></a>
                        <?
                        else:
                            ?>
                            <a class="pagination__prev"
                               href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>">
                                <svg width="21" height="9">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                         xlink:href="#svg-pagination-left"></use>
                                </svg>
                                <?= GetMessage("nav_prev_new") ?></a>
                        <?
                        endif;
                    endif;
                    ?>

                    <?

                    if ($arResult["nStartPage"] < $arResult["NavPageCount"]):
                        $bFirst = false;
                        if ($arResult["bSavePage"]):
                            ?>
                            <li class="pagination__el">
                                <a class="pagination__el-link"
                                   href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>">1</a>
                            </li>
                        <?
                        else:
                            ?>
                            <li class="pagination__el">
                                <a class="pagination__el-link"
                                   href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">1</a>
                            </li>
                        <?
                        endif;
                        ?>

                        <?
                        if ($arResult["nStartPage"] < ($arResult["NavPageCount"] - 1)):
                            ?>
                            <li class="pagination__el">
                                <span class="pagination__el-link pagination__el-link--dots">...</span>
                            </li>

                        <?
                        endif;
                    endif;
                endif;
                do {
                    $NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;

                    if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                        ?>
                        <li class="pagination__el">
                            <span class="pagination__el-link pagination__el-link--active"><?= $NavRecordGroupPrint ?></span>
                        </li>
                    <?
                    elseif ($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):
                        ?>
                        <li class="pagination__el">
                            <a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"
                               class=pagination__el-link"><?= $NavRecordGroupPrint ?></a>
                        </li>
                    <?
                    else:
                        ?>
                        <li class="pagination__el">
                            <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>"<?
                            ?> class="pagination__el-link"><?= $NavRecordGroupPrint ?></a>
                        </li>

                    <?
                    endif;
                    ?>

                    <?

                    $arResult["nStartPage"]--;
                    $bFirst = false;
                } while ($arResult["nStartPage"] >= $arResult["nEndPage"]);

                if ($arResult["NavPageNomer"] > 1):
                    if ($arResult["nEndPage"] > 1):
                        if ($arResult["nEndPage"] > 2):
                            ?>
                            <li class="pagination__el">
                                <span class="pagination__el-link pagination__el-link--dots">...</span>
                            </li>

                        <?
                        endif;
                        ?>
                        <a class="pagination__el-link"
                           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1"><?= $arResult["NavPageCount"] ?></a>

                    <?
                    endif;

                    ?>
                    <a class="pagination__next"
                       href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>">
                        <?= GetMessage("nav_next_new") ?>
                        <svg width="21" height="9">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-pagination-right"></use>
                        </svg>
                    </a>
                <?
                endif;

            else:
                $bFirst = true;

                if ($arResult["NavPageNomer"] > 1):
                    if ($arResult["bSavePage"]):
                        ?>
                        <a class="pagination__prev"
                           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>">
                            <svg width="21" height="9">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-pagination-left"></use>
                            </svg>
                            <?= GetMessage("nav_prev_new") ?></a>
                    <?
                    else:
                        if ($arResult["NavPageNomer"] > 2):
                            ?>
                            <a class="pagination__prev"
                               href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>">
                                <svg width="21" height="9">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                         xlink:href="#svg-pagination-left"></use>
                                </svg>
                                <?= GetMessage("nav_prev_new") ?></a>
                        <?
                        else:
                            ?>
                            <a class="pagination__prev"
                               href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">
                                <svg width="21" height="9">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                         xlink:href="#svg-pagination-left"></use>
                                </svg>
                                <?= GetMessage("nav_prev_new") ?></a>
                        <?
                        endif;

                    endif;
                    ?>

                    <?

                    if ($arResult["nStartPage"] > 1):
                        $bFirst = false;
                        if ($arResult["bSavePage"]):
                            ?>
                            <li class="pagination__el">
                                <a class="pagination__el-link"
                                   href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1">1</a>
                            </li>
                        <?
                        else:
                            ?>
                            <li class="pagination__el">
                                <a class="pagination__el-link"
                                   href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">1</a>
                            </li>
                        <?
                        endif;
                        ?>

                        <?
                        if ($arResult["nStartPage"] > 2):
                            ?>
                            <li class="pagination__el">
                                <span class="pagination__el-link pagination__el-link--dots">...</span>
                            </li>

                        <?
                        endif;
                    endif;
                endif;

                do {
                    if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                        ?>
                        <li class="pagination__el active">
                            <span class="pagination__el-link pagination__el-link--active"><?= $arResult["nStartPage"] ?></span>
                        </li>
                    <?
                    elseif ($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
                        ?>
                        <li class="pagination__el">
                            <a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"
                               class="pagination__el-link"><?= $arResult["nStartPage"] ?></a>
                        </li>
                    <?
                    else:
                        ?>
                        <li class="pagination__el">
                            <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>"<?
                            ?> class="pagination__el-link"><?= $arResult["nStartPage"] ?></a>
                        </li>
                    <?
                    endif;
                    ?>

                    <?
                    $arResult["nStartPage"]++;
                    $bFirst = false;
                } while ($arResult["nStartPage"] <= $arResult["nEndPage"]);

                if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
                    if ($arResult["nEndPage"] < $arResult["NavPageCount"]):
                        if ($arResult["nEndPage"] < ($arResult["NavPageCount"] - 1)):
                            ?>
                            <li class="pagination__el">
                                <span class="pagination__el-link pagination__el-link--dots">...</span>
                            </li>

                        <?
                        endif;
                        ?>
                        <li class="pagination__el">
                            <a class="pagination__el-link"
                               href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>"><?= $arResult["NavPageCount"] ?></a>
                        </li>
                    <?
                    endif;
                    ?>

                    <a class="pagination__next"
                       href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>">
                        <?= GetMessage("nav_next_new") ?>
                        <svg width="21" height="9">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-pagination-right"></use>
                        </svg>
                    </a>
                <?
                endif;
            endif;

            if ($arResult["bShowAll"]):
                if ($arResult["NavShowAll"]):
                    ?>
                    <li class="pagination__el">
                        <a class="blog-page-pagen"
                           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>SHOWALL_<?= $arResult["NavNum"] ?>=0"><?= GetMessage("nav_paged") ?></a>
                    </li>
                <?
                else:
                    ?>
                    <li class="pagination__el">
                        <a class="blog-page-all"
                           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>SHOWALL_<?= $arResult["NavNum"] ?>=1"><?= GetMessage("nav_all") ?></a>
                    </li>
                <?
                endif;
            endif
            ?>

        </ul>
    </div>
    <?
}


?>