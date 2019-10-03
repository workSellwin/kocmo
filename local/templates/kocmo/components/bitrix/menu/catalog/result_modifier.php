<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult))
    return;

$arSectionsInfo = [];
if (IsModuleInstalled("iblock")) {
    $arFilter = [
        "TYPE" => "catalog",
        "SITE_ID" => SITE_ID,
        "ACTIVE" => "Y"
    ];

    $obCache = new CPHPCache();
    if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/menu")) {
        $arSectionsInfo = $obCache->GetVars();
    } elseif ($obCache->StartDataCache()) {
        if (CModule::IncludeModule("iblock")) {
            $dbIBlock = CIBlock::GetList(array('SORT' => 'ASC', 'ID' => 'ASC'), $arFilter);
            $dbIBlock = new CIBlockResult($dbIBlock);
            $curIblockID = 0;
            if ($arIBlock = $dbIBlock->GetNext()) {
                $dbSections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arIBlock["ID"]), false, array("ID", "SECTION_PAGE_URL", "PICTURE", "DESCRIPTION", "DEPTH_LEVEL"));
                while ($arSections = $dbSections->GetNext()) {
                    $pictureSrc = CFile::GetFileArray($arSections["PICTURE"]);

                    if ($pictureSrc)
                        $arResizePicture = CFile::ResizeImageGet(
                            $arSections["PICTURE"],
                            array("width" => 240, 'height' => 700),
                            BX_RESIZE_IMAGE_PROPORTIONAL,
                            true
                        );

                    $arSectionsInfo[crc32($arSections["SECTION_PAGE_URL"])]["PICTURE"] = $pictureSrc ? $arResizePicture["src"] : false;
                    $arSectionsInfo[crc32($arSections["SECTION_PAGE_URL"])]["DESCRIPTION"] = $arSections["DESCRIPTION"];
                    $arBrandFinish = [];
                    if ($arSections['DEPTH_LEVEL'] == 1) {
                        $arBrand = [];
                        $propCode = 'MATERIAL';// свойства
                        $arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", 'PROPERTY_' . $propCode);
                        $arFilter = Array("IBLOCK_ID" => $arIBlock["ID"], "SECTION_ID" => $arSections['ID'], "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y");
                        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                        while ($arFields = $res->GetNext()) {
                            if ($brand = $arFields['PROPERTY_' . $propCode . '_VALUE']) {
                                $arBrand[trim($brand)] = trim($brand);
                            }
                        }
                        ksort($arBrand);
                        $n = 0;
                        foreach ($arBrand as $item) {
                            $k = strtoupper(substr($item, 0, 1));
                            $arBrandFinish[$k][$n]['NAME'] = $item;
                            $n++;
                        }
                    }
                    $arSectionsInfo[crc32($arSections["SECTION_PAGE_URL"])]['BRAND'] = $arBrandFinish;

                }
                if (defined("BX_COMP_MANAGED_CACHE")) {
                    global $CACHE_MANAGER;
                    $CACHE_MANAGER->StartTagCache("/iblock/menu");
                    $CACHE_MANAGER->RegisterTag("iblock_id_" . $arIBlock["ID"]);
                    $CACHE_MANAGER->EndTagCache();
                }
            }
        }
        $obCache->EndDataCache($arSectionsInfo);
    }
}


foreach ($arResult as &$item) {
    if ($data = $arSectionsInfo[crc32($item['LINK'])]) {
        $item['INFO'] = $data;
    }
}

$arResult = getChilds($arResult);
