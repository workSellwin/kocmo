<style>
	#cdek-create-cities{
		color: white;
		background: #86ad00;
		border-radius: 3px;
		border: none;
		width: 100%;
		cursor:pointer;
	}
	#bx-admin-prefix .adm-designed-checkbox-label{
		height:30px;
		display:none;
	}
	#bx-admin-prefix .adm-designed-checkbox{
		display: inline-block !important;
		height: 15px;
		margin-top: 0;
	}
</style>
<?
	use Bitrix\Main\Config\Option,
		Bitrix\Main\Localization\Loc,
		Bitrix\Main\Loader;
	
	Loc::loadMessages(__FILE__);
	global $APPLICATION;

	if(isset($_POST["weight"])){
		Option::set("manao.cdek", "cdek-weight", $_POST["weight"]);
	}
	if(isset($_POST["markup"])){
		Option::set("manao.cdek", "cdek-markup", $_POST["markup"]);
	}
	if(isset($_POST["max-delivery-cost"])){
		Option::set("manao.cdek", "cdek-max-delivery-cost", $_POST["max-delivery-cost"]);
	}
	if(isset($_POST["max-order-cost"])){
		Option::set("manao.cdek", "cdek-max-order-cost", $_POST["max-order-cost"]);
	}
	if(isset($_POST["interval-min"])){
		Option::set("manao.cdek", "cdek-interval-min", $_POST["interval-min"]);
	}
	if(isset($_POST["interval-max"])){
		Option::set("manao.cdek", "cdek-interval-max", $_POST["interval-max"]);
	}
	if(isset($_POST["create-cities"]) && $_POST["create-cities"] == 'Y' && !empty($_POST['apply']) ){
		Loader::includeModule('manao.cdek');
		$fC = new Manao\Cdek\FillInCdekCities();
		$fC->fillCitiesFromCsv();
	}
	if(isset($_POST["delivery-description"])){
		Option::set("manao.cdek", "cdek-delivery-description", $_POST["delivery-description"]);
	}
	
	
	if(isset($_POST["cdek-cache-id"])){
		Option::set("manao.cdek", "cdek-cache-id", $_POST["cdek-cache-id"]);
	}
	if(isset($_POST["cdek-cache-id-pickup"])){
		Option::set("manao.cdek", "cdek-cache-id-pickup", $_POST["cdek-cache-id-pickup"]);
	}
	if(isset($_POST["cdek-cache-time"])){
		Option::set("manao.cdek", "cdek-cache-time", $_POST["cdek-cache-time"]);
	}
	if(isset($_POST["cdek-cache-time-pickup"])){
		Option::set("manao.cdek", "cdek-cache-time-pickup", $_POST["cdek-cache-time-pickup"]);
	}

	$mainTab = [
		"DIV" => "main",
		"TAB" => Loc::getMessage("MAIN_OPTIONS"),
		"ICON" => "fileman_settings",
		"TITLE" => Loc::getMessage("MODULE_OPTIONS")
	];
	$aTabs[] = $mainTab;
	
	$fillCityTab = [
		"DIV" => "fill-city",
		"TAB" => Loc::getMessage("FILL_CITIES_TAB"),
		"ICON" => "fileman_settings",
		"TITLE" => Loc::getMessage("FILL_CITIES_TITLE")
	];
	
	$aTabs[] = $fillCityTab;
	
	$addCustomCity = [
		"DIV" => "add-city",
		"TAB" => Loc::getMessage("ADD_CITY_TAB"),
		"ICON" => "fileman_settings",
		"TITLE" => Loc::getMessage("ADD_CITY_TITLE")
	];
	
	$aTabs[] = $addCustomCity;
	
	$cacheTab = [
		"DIV" => "cache-tab",
		"TAB" => Loc::getMessage("CACHE_TAB"),
		"ICON" => "fileman_settings",
		"TITLE" => Loc::getMessage("CACHE_TITLE")
	];
	
	$aTabs[] = $cacheTab;
	
	$tabControl = new CAdmintabControl("tabControl", $aTabs);
	
	$tabControl->Begin();
?>
<div class="options-wrapper">
	<form method="POST" action="">
		<?=bitrix_sessid_post()?>
		<?$tabControl->BeginNextTab();?>
			<tr>
				<td valign="top"><label for="cdek-weight"><?=Loc::getMessage("M_WEIGHT")?></label></td>
				<td><input type="text" name="weight" id="cdek-weight" size="40" value="<?=Option::get("manao.cdek", "cdek-weight")?>"></td>
			</tr>
			<tr>
				<td valign="top"><label for="cdek-markup"><?=Loc::getMessage("M_MARKUP")?></label></td>
				<td><input type="text" name="markup" id="cdek-markup" size="40" value="<?=Option::get("manao.cdek", "cdek-markup")?>"></td>
			</tr>
			<tr>
				<td valign="top"><label for="cdek-max-delivery-cost"><?=Loc::getMessage("M_MAX_DELIVERY_COST_NPP")?></label></td>
				<td><input type="text" name="max-delivery-cost" id="cdek-max-delivery-cost" size="40" value="<?=Option::get("manao.cdek", "cdek-max-delivery-cost")?>"></td>
			</tr>
			<tr>
				<td valign="top"><label for="cdek-max-order-cost"><?=Loc::getMessage("M_MAX_ORDER_COST_NPP")?></label></td>
				<td><input type="text" name="max-order-cost" id="cdek-max-order-cost" size="40" value="<?=Option::get("manao.cdek", "cdek-max-order-cost")?>"></td>
			</tr>
			<tr>
				<td valign="top"><label for="cdek-interval"><?=Loc::getMessage("M_INTERVAL")?></label></td>
				<td><input type="text" name="interval-min" id="cdek-interval-min" size="20" value="<?=Option::get("manao.cdek", "cdek-interval-min")?>">
				<input type="text" name="interval-max" id="cdek-interval-max" size="20" value="<?=Option::get("manao.cdek", "cdek-interval-max")?>"></td>
			</tr>
			<tr>
				<td valign="top"><label for="cdek-delivery-description"><?=Loc::getMessage("DELIVERY_DESCRIPTION")?></label></td>
				<td><textarea type="text" name="delivery-description" id="cdek-delivery-description" cols="40"rows="10"><?=Option::get("manao.cdek", "cdek-delivery-description")?></textarea></td>
			</tr>
		<?$tabControl->BeginNextTab();?>
		<tr>
			<td valign="top"><label for="cdek-create-cities"><?=Loc::getMessage("CREATE_CITIES_BTN")?></label></td>
			<!--<td><button type="submit" name="create-cities" id="cdek-create-cities" value="">Начать</button></td>-->
			<td><input type="checkbox" name="create-cities" id="cdek-create-cities" value="Y"></td>
		</tr>
		<?$tabControl->BeginNextTab();?>
			<?$APPLICATION->IncludeComponent(
				"manao:sale.ajax.locations",
				"manao-cdek-select-location",
				array(
					"AJAX_CALL" => "N",
					"COUNTRY_INPUT_NAME" => "COUNTRY",
					"REGION_INPUT_NAME" => "REGION",
					"CITY_INPUT_NAME" => "cdek-bitrix-city-id",
					"CITY_OUT_LOCATION" => "Y",
					"CDEK_ID_INPUT" => "cdek-cityId",
					"CDEK_CITY_INPUT" => "cdek-city-name",
					"CDEK_REGION_INPUT" => "cdek-city-region",
				),
				null,
				array('HIDE_ICONS' => 'Y')
			);?> 
		<?$tabControl->BeginNextTab();?>
		<tr>
			<td valign="top"><label for="cdek-cache-id"><?=Loc::getMessage("CACHE_ID")?></label></td>
			<td><input type="text" name="cdek-cache-id" id="cdek-cache-id" size="20" value="<?=Option::get("manao.cdek", "cdek-cache-id")?>"></td>
		</tr>
		<tr>
			<td valign="top"><label for="cdek-cache-id-pickup"><?=Loc::getMessage("CACHE_ID_PICKUP")?></label></td>
			<td><input type="text" name="cdek-cache-id-pickup" id="cdek-cache-id-pickup" size="20" value="<?=Option::get("manao.cdek", "cdek-cache-id-pickup")?>"></td>
		</tr>
		<tr>
			<td valign="top"><label for="cdek-cache-time"><?=Loc::getMessage("CACHE_TIME")?></label></td>
			<td><input type="text" name="cdek-cache-time" id="cdek-cache-time" size="20" value="<?=Option::get("manao.cdek", "cdek-cache-time")?>"></td>
		</tr>
		<tr>
			<td valign="top"><label for="cdek-cache-time-pickup"><?=Loc::getMessage("CACHE_TIME_PICKUP")?></label></td>
			<td><input type="text" name="cdek-cache-time-pickup" id="cdek-cache-time-pickup" size="20" value="<?=Option::get("manao.cdek", "cdek-cache-time-pickup")?>"></td>
		</tr>
		<?$tabControl->End();?>
		<?$tabControl->Buttons(array());?>
	</form>
</div>
