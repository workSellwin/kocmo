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
$this->setFrameMode(true); ?>




<div id="popup-store-amount" class="popup popup-check-sale preloader-wrap" style="display: none;">

    <a href="#"  style="top: 17px; right: 17px;" onclick="$.fancybox.close();return false;" class="popup__fancybox-close"></a>


    <h2 style="font-size: 22px; color: #80007a" class="popup__title"><?= $arParams["NAME_PRODUCT"] ?></h2>

    <div class="double-separator"></div>

    <div class="popup-check-sale__form-wrap">
        <span style="color: #666;font-size: 20px;font-weight: bold">Магазины, в которых товар в наличии</span>
        <div class="bx_storege" id="catalog_store_amount_div">
            <? if (!empty($arResult["STORES"])): ?>

                <br>
                <ul id="c_store_amount">
                    <? foreach ($arResult["STORES"] as $pid => $arProperty): ?>
                    <?$arProperty["TITLE"]=substr($arProperty["TITLE"],strpos($arProperty["TITLE"],'(')+1,-1) ?>
                        <?if($arProperty['AMOUNT'] > 0){?>
                            <li>
                            <? if (isset($arProperty["TITLE"])): ?>
                                <span style="color: #666;font-size: 17px"><?= $arProperty["TITLE"] ?></span>
                            <? endif; ?>
                        </li>
                        <?}?>
                    <? endforeach; ?>
                </ul>
            <? endif; ?>
        </div>
        <? if (isset($arResult["IS_SKU"]) && $arResult["IS_SKU"] == 1): ?>
            <script type="text/javascript">
                var obStoreAmount = new JCCatalogStoreSKU(<? echo CUtil::PhpToJSObject($arResult['JS'], false, true, true); ?>);
            </script>
        <?
        endif; ?>
    </div>

    <div class="preloader" style="display: none;">
        <svg width="64" height="64">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-preloader"></use>
        </svg>
    </div>
</div>


<? //PR($arResult)?>
