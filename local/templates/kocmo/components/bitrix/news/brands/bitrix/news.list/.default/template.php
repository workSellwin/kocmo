<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
//echo '<pre>', print_r($arResult['ITEMS'], true), '</pre>';
if(count($arResult['ITEMS'])){

    $cache = Bitrix\Main\Data\Cache::createInstance();
    $ids = '';

    foreach ($arResult['ITEMS'] as $arItem) {
        $ids .= $arItem['ID'];
    }
    $cacheTime = 86400;
    $cacheId = 'bindXmlId2' . md5($ids);

    if ($cache->initCache($cacheTime, $cacheId))
    {
        $bindXmlId = $cache->getVars();
    }
    elseif ($cache->startDataCache())
    {
        $bindXmlId = [];

        foreach($arResult['ITEMS'] as $arItem){
            $bindXmlId[$arItem['PROPERTIES']['BRAND_BIND']['VALUE']] = $arItem['ID'];
        }
        $res = CIBlockPropertyEnum::GetList([],["XML_ID" => array_keys($bindXmlId)] );

        while($enumFields = $res->fetch() ){
            $bindXmlId[$enumFields['XML_ID']] = $enumFields['ID'];
        }
        unset($res, $ids);

        if ( !count($bindXmlId) )
        {
            $cache->abortDataCache();
        }
        $cache->endDataCache($bindXmlId);
    }
}
$latinAlphabet = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

$activeIndex = false;
$letter = 'A';

if(isset($_GET['letter'])){

    if($_GET['letter'] == 'all') {
        $letter = 'Все';
    }
    elseif($_GET['letter'] == 'cyrillic'){
        $letter = 'А-Я';
    }
    elseif($_GET['letter'] == 'num'){
        $letter = '0-9';
    }
    else{
        $searchIndex = array_search(mb_substr($_GET['letter'], 0, 1), $latinAlphabet);

        if (!empty($searchIndex) || $searchIndex === 0) {
            $activeIndex = $searchIndex;
            $letter = $latinAlphabet[$activeIndex];
        }
    }
}
else{
    $activeIndex = 0;
}

use Bitrix\Main\Data\Cache; ?>

<div class="brands">
    <?$APPLICATION->IncludeComponent(
	"bitrix:search.title", 
	"brands",
	array(
		"SHOW_INPUT" => "Y",
		"INPUT_ID" => "title-search-input",
		"CONTAINER_ID" => "title-search",
		"PRICE_CODE" => array(
			0 => "ROZNICHNAYA",
			1 => "AKTSIONNAYA",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "150",
		"SHOW_PREVIEW" => "Y",
		"PREVIEW_WIDTH" => "75",
		"PREVIEW_HEIGHT" => "75",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "BYN",
		"PAGE" => "#SITE_DIR#search/index.php",
		"NUM_CATEGORIES" => "1",
		"TOP_COUNT" => "5",
		"ORDER" => "date",
		"USE_LANGUAGE_GUESS" => "Y",
		"CHECK_DATES" => "Y",
		"SHOW_OTHERS" => "N",
		"COMPONENT_TEMPLATE" => "brands",
		"CATEGORY_0_TITLE" => "",
		"CATEGORY_0" => array(
			0 => "iblock_catalog",
		),
		"CATEGORY_0_iblock_catalog" => array(
			0 => "7",
		),
		"TEMPLATE_THEME" => "blue"
	),
	false
);?>
    <div class="brands-filter">
        <div class="container brands-filter__container">
            <ul class="brands-filter__list">

                <?foreach($latinAlphabet as $index => $symbol):?>
                    <li class="brands-filter__list-item<?echo $index == $activeIndex && $activeIndex !== false ? ' active' : '';?><?
                    echo isset($arParams['BRANDS_FIRST_LETTER'][$symbol]) ? "" : " letter-disabled";
                    ?>">
                        <a href="<?=$APPLICATION->GetCurPageParam("letter=$symbol", ["letter"]);?>" class="brands-filter__lnk"><?=$symbol?></a>
                    </li>
                <?endforeach;?>
            </ul>

            <ul class="brands-filter__sorter">
                <li class="brands-filter__sorter-item">
                    <a href="<?=$APPLICATION->GetCurPageParam("letter=all", ["letter"]);?>"
                    class="brands-filter__sorter-lnk<?=$_GET['letter'] == 'all' ? " active" : "";?>">Все</a></li>

                <li class="brands-filter__sorter-item">
                    <a href="<?=$APPLICATION->GetCurPageParam("letter=cyrillic", ["letter"]);?>"
                        class="brands-filter__sorter-lnk<?=$_GET['letter'] == 'cyrillic' ? " active" : "";?><?
                        $bflk = array_keys($arParams['BRANDS_FIRST_LETTER']);
                        $tempStr = implode('', $bflk);
                        preg_match('#[А-Яа-яЁё]#u',$tempStr, $matches);
                        if( !count($matches) ) {
                            echo " letter-disabled";
                        }
                        ?>">А - Я</a>
                </li>
                <li class="brands-filter__sorter-item">
                    <a href="<?=$APPLICATION->GetCurPageParam("letter=num", ["letter"]);?>"
                       class="brands-filter__sorter-lnk<?=$_GET['letter'] == 'num' ? " active" : "";?><?
                       $bflk = array_keys($arParams['BRANDS_FIRST_LETTER']);
                       $tempStr = implode('', $bflk);
                       preg_match('#[0-9]#',$tempStr, $matches);
                       if( !count($matches) ) {
                           echo " letter-disabled";
                       }
                       ?>">0 - 9</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="brands-inner container">
        <h2 class="brands-inner__title"><?=$letter?></h2>

        <ul class="brands-inner__list">
            <?foreach($arResult['ITEMS'] as $item):?>
                <?
//                $PROPERTY_ID = $arParams['FILTER_PROPERTY_ID'];//id свойства
//                $htmlKey = $bindXmlId[$item['PROPERTIES']['BRAND_BIND']['VALUE']];//id списочного свойства
//                $keyCrc = abs(crc32($htmlKey));
//                $filterPropertyID = $arParams['FILTER_NAME'].'_'.$PROPERTY_ID.'_'.$keyCrc;
//                $url = '/catalog/?set_filter=y&' . $filterPropertyID . '=Y';
                ?>
                <li id="=<?$this->GetEditAreaId($item['ID'])?>" class="brands-inner__list-item"><a href="<?=$item['DETAIL_PAGE_URL']?>" class="brands-inner__lnk"><?=$item['NAME']?></a></li>
            <?endforeach;?>
        </ul>
    </div>
</div>