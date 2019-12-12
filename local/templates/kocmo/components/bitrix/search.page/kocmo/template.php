<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

use Bitrix\Main\Grid\Declension;use Lui\Kocmo\Data\IblockElement;use Lui\Kocmo\Data\IblockSection;

$count = 0;
$q = $arResult['REQUEST']['QUERY'];
if (is_object($arResult['NAV_RESULT'])) {
    $count = $arResult['NAV_RESULT']->NavRecordCount;
    if ($arResult['SEARCH']) {
        $arID = array_column($arResult['SEARCH'], 'ITEM_ID');
        $obData = new IblockElement();
        $sectionID = array_values(array_diff((array_unique(array_column($obData->GetIDs($arID), 'IBLOCK_SECTION_ID'))), ['']));
        $obSection = new IblockSection();
        $arSections = $obSection->GetIDs($sectionID);
        $arSections = array_column($arSections, 'NAME', 'ID');
        $searchQuery='';
         if (isset($_REQUEST['q']) && is_string($_REQUEST['q'])){
            $searchQuery = trim($_REQUEST['q']);
         }
         $tplUrl="?q={$searchQuery}&SECTION_ID=#ID#";
        foreach ($arSections as $id=>&$name){
            $name=[ 'NAME'=>$name, 'URL'=>str_replace('#ID#',$id,$tplUrl),  'ACTIVE'=>$_REQUEST['SECTION_ID']==$id ? 'Y' : 'N',];
        }
        $arSections[]=['NAME'=>'Все','URL'=>'?q='.$searchQuery, 'ACTIVE'=>!isset($_REQUEST['SECTION_ID']) ? 'Y' : 'N',];
    }
}
if ($arSections) {
    ?>
    </div>
    <div class="category-subcategory">
        <div class="container">
            <ul class="category-subcategory__inner">
            <?foreach ($arSections as $id=>$arSection){?>
                <li class="category-subcategory__item">
                    <?if($arSection['ACTIVE']=='Y'){?>
                        <span class="category-subcategory__lnk"><?=$arSection['NAME']?></span>
                    <?}else{?>
                         <a href="<?=$arSection['URL']?>" class="category-subcategory__lnk"><span><?=$arSection['NAME']?></span></a>
                     <?}?>
                </li>
               <?}?>
            </ul>
        </div>
    </div>
    <div class="container">
    <?
}
if ($count) {
    $obD = new Declension('товар', 'товара', 'товаров');
    $world = $obD->get($count);
    $this->SetViewTarget('search-result-info');
    ?>
    <div class="search-result-info">По вашему запросу: <span><?= $q; ?></span> найдено
    <b><?= $count ?> <?= $world ?></b></div><?
    $this->EndViewTarget();
}

