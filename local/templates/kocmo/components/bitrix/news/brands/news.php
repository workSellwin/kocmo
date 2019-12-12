<? use Lui\Kocmo\IncludeComponent;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

if(isset($_GET['letter'])) {
	if($_GET['letter'] == 'all') {
        //
    }
	elseif($_GET['letter'] == 'cyrillic'){
		$GLOBALS[$arParams['FILTER_NAME']]["NAME"] = ['А%', 'Б%', 'В%', 'Г%', 'Д%', 'Е%', 'Ё%', 'Ж%', 'З%', 'И%'
			, 'К%', 'Л%', 'М%', 'Н%', 'О%', 'П%', 'Р%', 'С%', 'Т%', 'У%', 'Ф%', 'Х%', 'Ч%', 'Ш%', 'Э%', 'Ю%', 'Я%'];
	}
	elseif($_GET['letter'] == 'num'){
		$GLOBALS[$arParams['FILTER_NAME']]["NAME"] = ['0%', '1%', '2%', '3%', '4%', '5%', '6%', '7%', '8%', '9%'];
	}
    else {
		$GLOBALS[$arParams['FILTER_NAME']]["NAME"] = mb_substr($_GET['letter'], 0, 1) . '%';
	}
}
else{
	$GLOBALS[$arParams['FILTER_NAME']]["NAME"] = 'A%';
}

$cache = Bitrix\Main\Data\Cache::createInstance();
$cacheTime = 600;
$cacheId = 'brands5';

if ($cache->initCache($cacheTime, $cacheId))
{
	$brandsFirstLetter = $cache->getVars();
}
elseif ($cache->startDataCache())
{
	$res = CIBlockElement::GetList([],["IBLOCK_ID" => 7, "ACTIVE" => 'Y'],false,false,["NAME"]);
	$brandsFirstLetter = [];

	while($brandField = $res->fetch()){

		$letter = mb_substr($brandField["NAME"], 0, 1);

		if(!empty($letter) ){
			$letter = mb_strtoupper($letter);
			$brandsFirstLetter[$letter] = true;
		}
	}

	if (!count($brandsFirstLetter))
	{
		$cache->abortDataCache();
	}
	$cache->endDataCache($brandsFirstLetter);
}

$cacheTime = 86400;
$cacheId = 'filter_prop1';

if ($cache->initCache($cacheTime, $cacheId))
{
	$filterData = $cache->getVars();
}
elseif ($cache->startDataCache())
{
	$res = CIBlockProperty::GetList([], ['XML_ID' => '42d1082e-9ccb-11e8-a215-00505601048d']);

	if($propFields = $res->fetch() ){
		$filterData['FILTER_PROPERTY_ID'] = $propFields['ID'];
	}

	if ( empty($filterData['FILTER_PROPERTY_ID']) )
	{
		$cache->abortDataCache();
	}
	$cache->endDataCache($filterData);
}

//echo '<pre>', print_r($arParams, true), '</pre>';
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	".default",
	Array(
		"FILTER_PROPERTY_ID" => $filterData['FILTER_PROPERTY_ID'],
		"BRANDS_FIRST_LETTER" => $brandsFirstLetter,
		//"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"NEWS_COUNT" => $arParams["NEWS_COUNT"],
		"SORT_BY1" => $arParams["SORT_BY1"],
		"SORT_ORDER1" => $arParams["SORT_ORDER1"],
		"SORT_BY2" => $arParams["SORT_BY2"],
		"SORT_ORDER2" => $arParams["SORT_ORDER2"],
		"FIELD_CODE" => $arParams["LIST_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
		//"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		//"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		//"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"FILE_404" => $arParams["FILE_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
		"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
		"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],
	),
	$component
);?>
