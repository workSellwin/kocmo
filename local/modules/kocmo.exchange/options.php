<?
use Bitrix\Main\Config\Option,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);
global $APPLICATION;

$moduleName = 'kocmo.exchange';
$prefix = "exchange-";
$hrefs = [
    [
        "NAME" => "catalog_id",
        "LANG" => "CATALOG_ID",
    ],
    [
        "NAME" => "offers_id",
        "LANG" => "OFFERS_ID",
    ],
    [
        "NAME" => "section-href",
        "LANG" => "HREF_GET_SECTIONS",
    ],
    [
        "NAME" => "product-href",
        "LANG" => "HREF_GET_PRODUCTS",
    ],
    [
        "NAME" => "props-href",
        "LANG" => "HREF_GET_PROPS",
    ],
    [
        "NAME" => "image-href",
        "LANG" => "HREF_GET_IMAGE",
    ],
    [
        "NAME" => "schema-href",
        "LANG" => "HREF_GET_SCHEMA",
    ],
    [
        "NAME" => "price-type-href",
        "LANG" => "HREF_GET_PRICE_TYPE",
    ],
    [
        "NAME" => "price-href",
        "LANG" => "HREF_GET_PRICE",
    ],
    [
        "NAME" => "store-href",
        "LANG" => "HREF_GET_STORE",
    ],
    [
        "NAME" => "rest-href",
        "LANG" => "HREF_GET_REST",
    ],
];

if (isset($_POST) && count($_POST)) {

    $names = array_column($hrefs, 'NAME');

    foreach ($_POST as $name => $param) {

        if (in_array($name, $names) ) {
            Option::set($moduleName, $prefix . $name, $param);
        }
    }
    header("location: " . $APPLICATION->GetCurPage() . "?lang=ru&mid=kocmo.exchange");
}
$aTabs = [];

$mainTab = [
    "DIV" => "main",
    "TAB" => Loc::getMessage("MAIN_OPTIONS"),
    "ICON" => "fileman_settings",
    "TITLE" => Loc::getMessage("MODULE_OPTIONS")
];
$aTabs[] = $mainTab;

$updateProductTab = [
    "DIV" => "upd-product",
    "TAB" => Loc::getMessage("UPDATE_PRODUCT_TAB"),
    "ICON" => "fileman_settings",
    "TITLE" => Loc::getMessage("UPDATE_PRODUCT_OPTIONS")
];
$aTabs[] = $updateProductTab;

$updatePricesTab = [
    "DIV" => "upd",
    "TAB" => Loc::getMessage("UPDATE_TAB"),
    "ICON" => "fileman_settings",
    "TITLE" => Loc::getMessage("UPDATE_OPTIONS")
];
$aTabs[] = $updatePricesTab;

$tabControl = new CAdmintabControl("tabControl", $aTabs);
$tabControl->Begin();

?>
<div class="options-wrapper">
    <form method="POST" action="<?= $APPLICATION->GetCurPage() . "?lang=ru&mid=kocmo.exchange"?>" enctype="multipart/form-data">
        <?= bitrix_sessid_post() ?>
        <? $tabControl->BeginNextTab(); ?>

        <? foreach ($hrefs as $arHref): ?>

            <tr>
                <?
                $name = $arHref['NAME'];
                ?>
                <td valign="top"><label for="<?= $name ?>"><?= Loc::getMessage($arHref['LANG']) ?></label></td>
                <td><input type="text" name="<?= $name ?>" id="<?= $name ?>" size="40"
                           value="<?= Option::get($moduleName, $prefix . $name) ?>"></td>
            </tr>
        <? endforeach; ?>
        <? $tabControl->BeginNextTab(); ?>
        <tr>
            <td valign="middle"><?= Loc::getMessage('EXTERNAL_KEY') ?></td>
            <td><input type="text" name="product-xml-id" value="" size="40"></td>
            <td><input type="button" name="update-product" value="<?= Loc::getMessage('REFRESH') ?>"></td>
        </tr>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                let updateBtn = document.querySelector('input[name="update-product"]');

                updateBtn.addEventListener('click', event => {

                    let xmlId = document.querySelector('input[name="product-xml-id"]').value;

                    if(xmlId) {

                        let xhr = new XMLHttpRequest();

                        let PARAMS = {
                            'XML_ID': xmlId
                        };
                        PARAMS = JSON.stringify(PARAMS);
                        xhr.open('get', '/ajax/?ACTION=UpdateProduct&METHOD=update&PARAMS=' + PARAMS);

                        xhr.onload = function (data) {
                            let respTxt = data.currentTarget.responseText;
                            respTxt = JSON.parse(respTxt);
                            console.log(respTxt);

                            if(respTxt.SUCCESS){
                                alert('ok');
                            }
                            else{
                                alert('error');
                            }
                        };

                        xhr.setRequestHeader('Content-Type', 'application/json; charset=utf-8');
                        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                        xhr.send();
                    }
                })
            })
        </script>
        <? $tabControl->BeginNextTab(); ?>

        <tr>
            <td valign="middle"><?= Loc::getMessage('UPDATE_SECTIONS') ?></td>
            <td>
                <input type="button" name="update-sections" value="<?= Loc::getMessage('REFRESH') ?>"
                       data-method="updSections">
            </td>
        </tr>
        <tr>
            <td valign="middle"><?= Loc::getMessage('UPDATE_PROPERTIES') ?></td>
            <td>
                <input type="button" name="update-properties" value="<?= Loc::getMessage('REFRESH') ?>"
                       data-method="updProperties">
            </td>
        </tr>
        <tr>
            <td valign="middle"><?= Loc::getMessage('ADD_AGENT_FOR_UPDATE_REST') ?></td>
            <td>
                <input type="button" name="update-rest" value="<?= Loc::getMessage('REFRESH') ?>"
                    data-method="updRests">
            </td>
        </tr>
        <tr>
            <td valign="middle"><?= Loc::getMessage('UPDATE_REST') ?></td>
            <td>
                <?
                    $stores = \Bitrix\Catalog\StoreTable::getlist([])->fetchAll();
                    $stores = array_column($stores, 'TITLE', "ID");
                ?>
                <select name="store-id" id="store-id">
                    <?foreach($stores as $id => $title):?>
                        <option value="<?= $id ?>"><?= $title ?></option>
                    <?endforeach;?>
                </select>
            </td>
            <td>
                <input type="button" name="update-rest-one" value="<?= Loc::getMessage('REFRESH') ?>"
                       data-method="updRest">
            </td>
        </tr>
        <tr>
            <td valign="middle"><?= Loc::getMessage('UPDATE_PRICES') ?></td>
            <td>
                <input type="button" name="update-prices" value="<?= Loc::getMessage('REFRESH') ?>"
                       data-method="updPrices">
            </td>
        </tr>
        <tr>
            <td valign="middle"><?= Loc::getMessage('UPDATE_BRANDS') ?></td>
            <td>
                <input type="button" name="update-brands" value="<?= Loc::getMessage('REFRESH') ?>"
                       data-method="updBrands">
            </td>
        </tr>

        <script>

            let sectionsBtn = document.querySelector('input[name="update-sections"]');
            sectionsBtn.addEventListener('click', updateEntity);

            let propertiesBtn = document.querySelector('input[name="update-properties"]');
            propertiesBtn.addEventListener('click', updateEntity);

            let pricesBtn = document.querySelector('input[name="update-prices"]');
            pricesBtn.addEventListener('click', updateEntity);

            let restsBtn = document.querySelector('input[name="update-rest"]');
            restsBtn.addEventListener('click', updateEntity);

            let restBtn = document.querySelector('input[name="update-rest-one"]');
            restBtn.addEventListener('click', updateEntity);

            let brandsBtn = document.querySelector('input[name="update-brands"]');
            brandsBtn.addEventListener('click', updateEntity);

            function updateEntity() {

                let xhr = new XMLHttpRequest();


                let method = this.dataset.method;

                if(!method){
                    return false;
                }

                let PARAMS = getParam(method);

                xhr.open('get', '/ajax/?ACTION=catalogUpdate&METHOD=' + method + '&PARAMS=' + PARAMS);

                xhr.onload = function (data) {

                    responseHasCome();
                    let respTxt = data.currentTarget.responseText;
                    respTxt = JSON.parse(respTxt);
                    console.log(respTxt);

                    if (respTxt.SUCCESS) {
                        alert('ok');
                    } else {
                        alert('error');
                    }
                };

                xhr.setRequestHeader('Content-Type', 'application/json; charset=utf-8');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.send();
                waitResponse();
            }
            
            function waitResponse() {
                BX.showWait();
                let btns = document.querySelectorAll('input[data-method]');

                for(let i = 0; i < btns.length; i++){
                    btns[i].disabled = true;
                }
            }
            
            function responseHasCome() {
                BX.closeWait();

                let btns = document.querySelectorAll('input[data-method]');

                for(let i = 0; i < btns.length; i++){
                    btns[i].disabled = false;
                }
            }

            function getParam(method) {

                let PARAMS = {};

                switch(method){
                    case 'updRest':
                        PARAMS['LAST_STORE_ID'] = document.querySelector('[name="store-id"]').value;
                        break;
                    default:
                        break;
                }
                return JSON.stringify(PARAMS);
            }
        </script>
        <? $tabControl->End(); ?>
        <? $tabControl->Buttons([]); ?>
    </form>

</div>

