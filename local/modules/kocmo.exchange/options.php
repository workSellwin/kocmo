<style>
	/*#cdek-create-cities{*/
	/*	color: white;*/
	/*	background: #86ad00;*/
	/*	border-radius: 3px;*/
	/*	border: none;*/
	/*	width: 100%;*/
	/*	cursor:pointer;*/
	/*}*/
	/*#bx-admin-prefix .adm-designed-checkbox-label{*/
	/*	height:30px;*/
	/*	display:none;*/
	/*}*/
	/*#bx-admin-prefix .adm-designed-checkbox{*/
	/*	display: inline-block !important;*/
	/*	height: 15px;*/
	/*	margin-top: 0;*/
	/*}*/
</style>
<?
	use Bitrix\Main\Config\Option,
		Bitrix\Main\Localization\Loc,
		Bitrix\Main\Loader;
	
	Loc::loadMessages(__FILE__);
	global $APPLICATION;

//	if(isset($_POST["weight"])){
//		Option::set("manao.cdek", "cdek-weight", $_POST["weight"]);
//	}
//	if(isset($_POST["markup"])){
//		Option::set("manao.cdek", "cdek-markup", $_POST["markup"]);
//	}
//	if(isset($_POST["max-delivery-cost"])){
//		Option::set("manao.cdek", "cdek-max-delivery-cost", $_POST["max-delivery-cost"]);
//	}
//	if(isset($_POST["max-order-cost"])){
//		Option::set("manao.cdek", "cdek-max-order-cost", $_POST["max-order-cost"]);
//	}
//	if(isset($_POST["interval-min"])){
//		Option::set("manao.cdek", "cdek-interval-min", $_POST["interval-min"]);
//	}
//	if(isset($_POST["interval-max"])){
//		Option::set("manao.cdek", "cdek-interval-max", $_POST["interval-max"]);
//	}
//	if(isset($_POST["create-cities"]) && $_POST["create-cities"] == 'Y' && !empty($_POST['apply']) ){
//		Loader::includeModule('manao.cdek');
//		$fC = new Manao\Cdek\FillInCdekCities();
//		$fC->fillCitiesFromCsv();
//	}
//	if(isset($_POST["delivery-description"])){
//		Option::set("manao.cdek", "cdek-delivery-description", $_POST["delivery-description"]);
//	}
//
//
//	if(isset($_POST["cdek-cache-id"])){
//		Option::set("manao.cdek", "cdek-cache-id", $_POST["cdek-cache-id"]);
//	}
//	if(isset($_POST["cdek-cache-id-pickup"])){
//		Option::set("manao.cdek", "cdek-cache-id-pickup", $_POST["cdek-cache-id-pickup"]);
//	}
//	if(isset($_POST["cdek-cache-time"])){
//		Option::set("manao.cdek", "cdek-cache-time", $_POST["cdek-cache-time"]);
//	}
//	if(isset($_POST["cdek-cache-time-pickup"])){
//		Option::set("manao.cdek", "cdek-cache-time-pickup", $_POST["cdek-cache-time-pickup"]);
//	}

	$mainTab = [
		"DIV" => "main",
		"TAB" => Loc::getMessage("MAIN_OPTIONS"),
		"ICON" => "fileman_settings",
		"TITLE" => Loc::getMessage("MODULE_OPTIONS")
	];
	$aTabs[] = $mainTab;
	
//	$fillCityTab = [
//		"DIV" => "fill-city",
//		"TAB" => Loc::getMessage("FILL_CITIES_TAB"),
//		"ICON" => "fileman_settings",
//		"TITLE" => Loc::getMessage("FILL_CITIES_TITLE")
//	];
//
//	$aTabs[] = $fillCityTab;
//
//	$addCustomCity = [
//		"DIV" => "add-city",
//		"TAB" => Loc::getMessage("ADD_CITY_TAB"),
//		"ICON" => "fileman_settings",
//		"TITLE" => Loc::getMessage("ADD_CITY_TITLE")
//	];
//
//	$aTabs[] = $addCustomCity;
//
//	$cacheTab = [
//		"DIV" => "cache-tab",
//		"TAB" => Loc::getMessage("CACHE_TAB"),
//		"ICON" => "fileman_settings",
//		"TITLE" => Loc::getMessage("CACHE_TITLE")
//	];
//
//	$aTabs[] = $cacheTab;
	
	$tabControl = new CAdmintabControl("tabControl", $aTabs);
	$tabControl->Begin();
	$moduleName = 'kocmo.exchange';
	$prefix = "exchange-";
?>
<div class="options-wrapper">
	<form method="POST" action="">
		<?=bitrix_sessid_post()?>
		<?$tabControl->BeginNextTab();?>
			<tr>
                <?
                    $name = "section-href";
                ?>
				<td valign="top"><label for="<?=$name?>"><?=Loc::getMessage("HREF_GET_SECTIONS")?></label></td>
				<td><input type="text" name="<?=$name?>" id="<?=$name?>" size="40" value="<?=Option::get($moduleName, $prefix . $name)?>"></td>
			</tr>
			<tr>
                <?
                $name = "product-href";
                ?>
                <td valign="top"><label for="<?=$name?>"><?=Loc::getMessage("HREF_GET_PRODUCTS")?></label></td>
                <td><input type="text" name="<?=$name?>" id="<?=$name?>" size="40" value="<?=Option::get($moduleName, $prefix . $name)?>"></td>
			</tr>

<!--			<tr>-->
<!--				<td valign="top"><label for="cdek-delivery-description">--><?//=Loc::getMessage("DELIVERY_DESCRIPTION")?><!--</label></td>-->
<!--				<td><textarea type="text" name="delivery-description" id="cdek-delivery-description" cols="40"rows="10">--><?//=Option::get("manao.cdek", "cdek-delivery-description")?><!--</textarea></td>-->
<!--			</tr>-->

		<?$tabControl->End();?>
		<?$tabControl->Buttons(array());?>
	</form>
</div>
