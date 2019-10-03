<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$frame = $this->createFrame()->begin("");
if ($img = $arResult['BANNER_PROPERTIES']['IMAGE_ID']) {
    $src = CFile::GetPath($img);
    echo <<<BANER
    <div class="top-banner" style="background-image: url('{$src}')"></div>
BANER;
} else {
    echo $arResult["BANNER"];
}
$frame->end();
