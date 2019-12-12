<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '';

//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()
$css = $APPLICATION->GetCSSArray();


$strReturn .= ' <div class="breadcrumbs-wrapper"><div class="breadcrumbs js_breadcrumbs">';

$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	$arrow = ($index > 0? '<i class="fa fa-angle-right"></i>' : '');

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
		$strReturn .= '
				<a class="breadcrumbs__lnk" href="'.$arResult[$index]["LINK"].'" title="'.$title.'">
					'.$title.'
				</a>';
	}
	else
	{
		$strReturn .= '
	
				<span class="breadcrumbs__lnk">'.$title.'</span>';
	}
}

$strReturn .= '</div></div><div style="clear:both"></div>';

return $strReturn;