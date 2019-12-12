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
    <div class="reviews__content preloader-wrap">
        <div class="reviews__content-inner">

            <div id="AJAX_COMMENT_CONTAINER" data-NavPageCount="<?= $arResult['NAV_RESULT']->NavPageCount ?>"
                 data-Page="<?= isset($_REQUEST['PAGEN_6']) ? $_REQUEST['PAGEN_6'] : 1 ?>">

                <? if (isset($_REQUEST['ajax_comment']) && $_REQUEST['ajax_comment'] == 'Y'):
                    $GLOBALS['APPLICATION']->RestartBuffer();
                endif; ?>
                <? foreach ($arResult["ITEMS"] as $arItem): ?>
                    <? $PROP = array_column($arItem['PROPERTIES'], 'VALUE', 'CODE');
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    if ($PROP['USER_ID']) {
                        $rsUser = CUser::GetByID($PROP['USER_ID']);
                        $arUser = $rsUser->Fetch();
                    }
                    ?>
                    <div id="<?= $this->GetEditAreaId($arItem['ID']); ?>" class="reviews__item">
                        <div class="reviews__item-aside">

                            <div class="reviews__item-name-wrap">
                                <div class="reviews__item-name"><?= $arUser['NAME'] ? $arUser['NAME'] : 'Анонимный пользователь' ?></div>
                                <div class="reviews__item-header-mobile">
                                    <div class="reviews__item-date"><?= $arItem['DISPLAY_ACTIVE_FROM'] ?></div>
                                        <?if($PROP['RATING']):?>
                                            <div class="reviews__item-stars">
                                                <div class="bx-rating">
                                                    <?for($i=1; $i<= 5; $i++):
                                                        if ($i <= $PROP['RATING']):
                                                            $className = "fa fa-star reviews-prod";
                                                        else:
                                                            $className = "fa fa-star-o reviews-prod";
                                                        endif;?>
                                                        <i  class="<?echo $className?>"
                                                            title="<?echo $name?>">
                                                        </i>
                                                    <?endfor;?>
                                                </div>
                                            </div>
                                        <?endif;?>
                                </div>
                            </div>

                            <div class="reviews__item-bio">
                                <table class="reviews__item-bio-table">
                                    <tr>
                                        <td>Возраст:</td>
                                        <td><?= $arResult['arSelectoptionprofile']['AGE'][$PROP['AGE']]['UF_NAME'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Цвет глаз:</td>
                                        <td><?= $arResult['arSelectoptionprofile']['EYE_COLOR'][$PROP['EYE_COLOR']]['UF_NAME'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Тип кожи:</td>
                                        <td><?= $arResult['arSelectoptionprofile']['SKIN_TYPE'][$PROP['SKIN_TYPE']]['UF_NAME'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Цвет волос:</td>
                                        <td><?= $arResult['arSelectoptionprofile']['HAIR_COLOR'][$PROP['HAIR_COLOR']]['UF_NAME'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="reviews__item-main">
                            <div class="reviews__item-main-header">
                                <?if($PROP['RATING']):?>
                                    <div class="reviews__item-stars">
                                        <div class="bx-rating">
                                            <?for($i=1; $i<= 5; $i++):
                                                if ($i <= $PROP['RATING']):
                                                    $className = "fa fa-star reviews-prod";
                                                else:
                                                    $className = "fa fa-star-o reviews-prod";
                                                endif;?>
                                                <i  class="<?echo $className?>"
                                                    title="<?echo $name?>">
                                                </i>
                                            <?endfor;?>
                                        </div>
                                    </div>
                                <?endif;?>
                                <div class="reviews__item-date" <?=!$PROP['RATING'] ? 'style="margin-left: 0!important"' : ''?>><?= $arItem['DISPLAY_ACTIVE_FROM'] ?></div>
                            </div>
                            <div class="reviews__item-main-content" >
                                <p><?= $arItem['PREVIEW_TEXT'] ?></p>
                            </div>
                            <?if(strlen($arItem['PREVIEW_TEXT']) > 100 ):?>
                                <div class="reviews__item-main-more js_reviews__item-main-more">читать далее</div>
                            <?endif;?>
                        </div>
                    </div>
                <? endforeach; ?>
                <? if (isset($_REQUEST['ajax_comment']) && $_REQUEST['ajax_comment'] == 'Y'): ?>
                    <? die(); ?>
                <? endif; ?>
            </div>
        </div>

        <div class="preloader" style="display: none;">
            <svg width="64" height="64">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-preloader"></use>
            </svg>
        </div>
    </div>

<?

//PAGEN_6
if ($arResult['NAV_RESULT']->NavPageNomer < $arResult['NAV_RESULT']->NavPageCount):
    $page = isset($_REQUEST['PAGEN_6']) ? $_REQUEST['PAGEN_6'] + 1 : 1; ?>
    <div class="reviews__footer">
        <div class="button-more-wrap">
            <div class="button-more__btn js_reviews_comment__btn" data-url="<?= URL() ?>?PAGEN_6="
                 data-num="<?= $page ?>">
                Показать больше
                <svg width="9" height="16">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-down"></use>
                </svg>
            </div>
        </div>
    </div>
<? endif; ?>