<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$APPLICATION->AddHeadScript("/bitrix/js/main/cphttprequest.js");

if ($arParams["AJAX_CALL"] != "Y"
	&& count($arParams["LOC_DEFAULT"]) > 0
	&& $arParams["PUBLIC"] != "N"
	&& $arParams["SHOW_QUICK_CHOOSE"] == "Y"):

	$isChecked = "";
	foreach ($arParams["LOC_DEFAULT"] as $val):
		$checked = "";
		if ((($val["ID"] == IntVal($_REQUEST["NEW_LOCATION_".$arParams["ORDER_PROPS_ID"]])) || ($val["ID"] == $arParams["CITY"])) && (!isset($_REQUEST["CHANGE_ZIP"]) || $_REQUEST["CHANGE_ZIP"] != "Y"))
		{
			$checked = "checked";
			$isChecked = "Y";
		}?>

		<div><input onChange="<?=$arParams["ONCITYCHANGE"]?>;" <?=$checked?> type="radio" name="NEW_LOCATION_<?=$arParams["ORDER_PROPS_ID"]?>" value="<?=$val["ID"]?>" id="loc_<?=$val["ID"]?>" /><label for="loc_<?=$val["ID"]?>"><?=$val["LOC_DEFAULT_NAME"]?></label></div>
	<?endforeach;?>
	<div><input <? if($isChecked!="Y") echo 'checked';?> type="radio" onclick="clearLocInput();" name="NEW_LOCATION_<?=$arParams["ORDER_PROPS_ID"]?>" value="0" id="loc_0" /><label for="loc_0"><?=GetMessage("LOC_DEFAULT_NAME_NULL")?>:</label></div>
<?endif;?>
<label>Введите город (для подстановки в "ID города в bitrix"): 
	<input
		size="<?=$arParams["SIZE1"]?>"
		name="<?=$arParams["CITY_INPUT_NAME"]?>_val"
		id="<?=$arParams["CITY_INPUT_NAME"]?>_val"
		value="<?=$arResult["LOCATION_STRING"]?>"
		class="search-suggest" type="text"
		autocomplete="off"
		onfocus="loc_sug_CheckThis(this, this.id);"
		<?=($arResult["SINGLE_CITY"] == "Y" ? " disabled" : "")?>/>
</label>
<tr>
	<td valign="top"><label for="<?=$arParams["CITY_INPUT_NAME"]?>">ID города в bitrix</label></td>
	<td><input type="text" name="<?=$arParams["CITY_INPUT_NAME"]?>" id="<?=$arParams["CITY_INPUT_NAME"]?>" value="<?=$arParams["LOCATION_VALUE"]?>"></td>
</tr>
<tr>
	<td valign="top"><label for="<?=$arParams["CDEK_ID_INPUT"]?>">ID города в СДЭК</label></td>
	<td><input type="text" name="<?=$arParams["CDEK_ID_INPUT"]?>" id="<?=$arParams["CDEK_ID_INPUT"]?>" value=""></td>
</tr>
<tr>
	<td valign="top"><label for="<?=$arParams["CDEK_CITY_INPUT"]?>">Название города в bitrix</label></td>
	<td><input type="text" name="<?=$arParams["CDEK_CITY_INPUT"]?>" id="<?=$arParams["CDEK_CITY_INPUT"]?>" value=""></td>
</tr>
<tr>
	<td valign="top"><label for="<?=$arParams["CDEK_REGION_INPUT"]?>">Название региона в bitrix</label></td>
	<td><input type="text" name="<?=$arParams["CDEK_REGION_INPUT"]?>" id="<?=$arParams["CDEK_REGION_INPUT"]?>" value=""></td>
</tr>

<script>
	if (typeof oObject != "object")
		window.oObject = {};

	document.loc_sug_CheckThis = function(oObj, id){
	
		try{
			if(SuggestLoadedSale){
			
				window.oObject[oObj.id] = new JsSuggestSale(oObj, '<?=$arResult["ADDITIONAL_VALUES"]?>', '', '', '<?=CUtil::JSEscape($arParams["ONCITYCHANGE"])?>');
				return;
			}
			else{
				setTimeout(loc_sug_CheckThis(oObj, id), 10);
			}
		}
		catch(e){
			setTimeout(loc_sug_CheckThis(oObj, id), 10);
		}
	}
	
	clearLocInput = function(){
	
		var inp = BX("<?=$arParams["CITY_INPUT_NAME"]?>_val");
		
		if(inp){
			inp.value = "";
			inp.focus();
		}
	}
	
	let timerId = setInterval(function(){
	
		if(window.oObject && window.oObject['cdek-bitrix-city-id_val'] && window.oObject['cdek-bitrix-city-id_val'].oLast
			&& window.oObject['cdek-bitrix-city-id_val'].oLast.arr && window.oObject['cdek-bitrix-city-id_val'].oLast.arr[1]){
			document.getElementById('cdek-city-name').value = window.oObject['cdek-bitrix-city-id_val'].oLast.arr[0];
			document.getElementById('cdek-city-region').value = window.oObject['cdek-bitrix-city-id_val'].oLast.arr[1];
			//console.log(window.oObject['cdek-bitrix-city-id_val'].oLast.arr);
			clearInterval(timerId);
		}
	}, 1000);
</script>

<?
if($_POST){

	if( !empty($_POST['cdek-bitrix-city-id']) && !empty($_POST['cdek-cityId'])
		&& !empty($_POST['cdek-city-name']) && !empty($_POST['cdek-city-region'])
	){
		Bitrix\Main\Loader::includeModule('manao.cdek');

		$fC = new Manao\Cdek\FillInCdekCities();
		$fC->add([
			'BITRIX_ID' => $_POST['cdek-bitrix-city-id'],
			'CDEK_ID' => $_POST['cdek-cityId'],
			'NAME' => $_POST['cdek-city-name'],
			'REGION' => $_POST['cdek-city-region'],
		]);
           
		$errors = $fC->getError();
		
		if( count($errors) && count($errors[0]) ){
		
			echo "<div style='color:red'>";
			
			foreach($errors[0] as $err){
				echo "<p>" . $err->getMessage() . "</p>";
			}
			
			echo "</div>";
		}
		//echo "<pre>".print_r($errors[0], true)."</pre>";
	}
}
?>