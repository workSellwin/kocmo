<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */


global $APPLICATION;
$arChain = array_column($APPLICATION->arAdditionalChain, 'TITLE');
if(!in_array($arResult['NAME'], $arChain)){
    $APPLICATION->AddChainItem($arResult['NAME'], $arResult['SECTION_PAGE_URL']);
}