<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Loader;
use Bitrix\Currency;

if (!Loader::includeModule('iblock'))
	return;

$catalogIncluded = Loader::includeModule('catalog');
$iblockExists = (!empty($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0);

$arIBlockType = CIBlockParameters::GetIBlockTypes();
$arIBlock = array();
$iblockFilter = (
	!empty($arCurrentValues['IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch())
	$arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
unset($arr, $rsIBlock, $iblockFilter);

$arPrice = array();
if ($catalogIncluded)
{
	$arPrice = CCatalogIBlockParameters::getPriceTypesList();
}

$arProperty_UF = array();
$arSProperty_LNS = array();
if ($iblockExists)
{
	$arUserFields = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("IBLOCK_".$arCurrentValues["IBLOCK_ID"]."_SECTION");
	foreach($arUserFields as $FIELD_NAME=>$arUserField)
	{
		$arProperty_UF[$FIELD_NAME] = $arUserField["LIST_COLUMN_LABEL"]? $arUserField["LIST_COLUMN_LABEL"]: $FIELD_NAME;
		if($arUserField["USER_TYPE"]["BASE_TYPE"]=="string")
			$arSProperty_LNS[$FIELD_NAME] = $arProperty_UF[$FIELD_NAME];
	}
	unset($arUserFields, $FIELD_NAME, $arUserField);
}

/*$arComponentParameters = array(
	"GROUPS" => array(
		"PRICES" => array(
			"NAME" => GetMessage("CP_BCSF_PRICES"),
		),
		"XML_EXPORT" => array(
			"NAME" => GetMessage("CP_BCSF_GROUP_XML_EXPORT"),
		),
	),
	"PARAMETERS" => array(

		"SECTION_CODE" => array(
			"PARENT" => "ORDER_ID",
			"NAME" => 'ID заказа',
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),

	),
);*/


if (empty($arPrice))
{
	unset($arComponentParameters["PARAMETERS"]["PRICE_CODE"]);
}