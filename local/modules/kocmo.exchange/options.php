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
        if (in_array($name, $names) && !empty($param) ) {
            Option::set($moduleName, $prefix . $name, $param);
        }
    }
    header("location: " . $APPLICATION->GetCurPage() . "?lang=ru&mid=kocmo.exchange");
}

$mainTab = [
    "DIV" => "main",
    "TAB" => Loc::getMessage("MAIN_OPTIONS"),
    "ICON" => "fileman_settings",
    "TITLE" => Loc::getMessage("MODULE_OPTIONS")
];
$aTabs[] = $mainTab;
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

        <? $tabControl->End(); ?>
        <? $tabControl->Buttons([]); ?>
    </form>
</div>
