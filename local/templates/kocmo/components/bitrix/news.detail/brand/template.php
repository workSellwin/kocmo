<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Data\Cache; ?>
<?
$catalogIblockId = 2;

$cache = Bitrix\Main\Data\Cache::createInstance();
$cacheTime = 86400 * 1;
$cacheId = 'filter_prop3';

if ($cache->initCache($cacheTime, $cacheId))
{
    $filterData = $cache->getVars();
}
elseif ($cache->startDataCache())
{
    $res = CIBlockProperty::GetList([], ['XML_ID' => '42d1082e-9ccb-11e8-a215-00505601048d']);//42d1082e-9ccb-11e8-a215-00505601048d - MARKA

    if($propFields = $res->fetch() ){
        $filterData['FILTER_PROPERTY_ID'] = $propFields['ID'];
    }

    if ( empty($filterData['FILTER_PROPERTY_ID']) )
    {
        $cache->abortDataCache();
    }
    $cache->endDataCache($filterData);
}

$marka = empty($arParams["ENUM_VALUE"]) ? $arParams["ELEMENT_CODE"]: $arParams["ENUM_VALUE"];

$cacheTime = 86400 * 1;
$cacheId = 'MARKA4_' . $arParams["ELEMENT_CODE"];

if ($cache->initCache($cacheTime, $cacheId))
{
    $sections = $cache->getVars();
}
elseif ($cache->startDataCache()) {
    $res = CIBlockElement::GetList(
        [],
        ['IBLOCK_ID' => $catalogIblockId, 'PROPERTY_MARKA_VALUE' => $marka],
        false,
        false,
        ['ID', 'IBLOCK_ID', 'PROPERTY_MARKA', 'NAME', 'IBLOCK_SECTION_ID']
    );
    $sections = [];
    $htmlKey = false;

    while ($e = $res->fetch()) {


        if( empty($e['IBLOCK_SECTION_ID']) ){
            continue;
        }

        if (empty($htmlKey)) {
            $htmlKey = $e['PROPERTY_MARKA_ENUM_ID'];
        }
        $sections[$e['IBLOCK_SECTION_ID']] = true;
    }

    unset($res);

    if (count($sections)) {
        $res = CIBlockSection::GetList(
            [],
            ['IBLOCK_ID' => $catalogIblockId, 'ID' => array_keys($sections), 'GLOBAL_ACTIVE' => 'Y'],
            false,
            ['ID', 'IBLOCK_ID', 'NAME', 'SECTION_PAGE_URL', 'CODE', 'XML_ID']
        );

        while ($s = $res->GetNext()) {

            $PROPERTY_ID = $filterData['FILTER_PROPERTY_ID'];//id свойства
            //$htmlKey = 22;//id списочного свойства
            $keyCrc = abs(crc32($htmlKey));
            $filterPropertyID = $arParams['FILTER_NAME'] . '_' . $PROPERTY_ID . '_' . $keyCrc;
            $filterGetParam = '?set_filter=y&' . $filterPropertyID . '=Y';
            $s['SECTION_PAGE_URL'] .= $filterGetParam;
            $sections[$s['ID']] = $s;
        }
    }

    if ( count($sections) )
    {
        $cache->abortDataCache();
    }
    $cache->endDataCache($sections);
}
//pr($sections);
?>
<?if(count($sections)):?>

<div class="category-subcategory">
    <div class="container">
        <ul class="category-subcategory__inner">
            <?
            $first = true;
            ?>
            <? foreach ($sections as $section): ?>
                <? if ($first && false): ?>
                    <li class="category-subcategory__item">
                        <span class="category-subcategory__lnk"><?= $section['NAME'] ?></span>
                    </li>
                <?
                    $first = false;
                    $GLOBALS['SECTION_ID'] = $section['ID'];
                ?>
                <? else: ?>
                    <li class="category-subcategory__item">
                        <a href="<?=$section['SECTION_PAGE_URL']?>" class="category-subcategory__lnk"><span><?= $section['NAME'] ?></span></a>
                    </li>
                <? endif; ?>
            <? endforeach; ?>
        </ul>
    </div>
</div>
<?endif;?>
<div class="brands-inner-info container">
    <div class="brands-inner-info__logo">
        <img src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>" alt="">
    </div>
    <div class="brands-inner-info__description">
        <?echo $arResult["PREVIEW_TEXT"];?>
    </div>
    <div class="brands-inner-info__more js_brands-inner-info__more">читать далее</div>
</div>