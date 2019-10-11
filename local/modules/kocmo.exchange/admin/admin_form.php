<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
	
	if (!$_POST['et_open_window']){
		return;
	}
	
	use Bitrix\Main\Localization\Loc;
	Loc::loadMessages(__FILE__); 
	
	require_once( "../lib/data.php" );
	
	$curPage = $_POST['CUR_PAGE'];
	Manao\Easytarget\DataTable::setPublicFormResult($curPage);
	
	if( is_array($_REQUEST['ET_FORM']) && count($_REQUEST['ET_FORM']) ){
		Manao\Easytarget\DataTable::saveFormData($_REQUEST['ET_FORM'], $_REQUEST['CUR_PAGE']);
	}
?>
<form class="easy_target_form" data-cur-page="<?=$curPage?>" name="easy_target_form" method="POST" action="" enctype="multipart/form-data">
	<table>
		<tr>
			<th><?=Loc::getMessage('SELECTOR');?></th>
			<th><?=Loc::getMessage('SCRIPT_BODY_TXT');?></th>
			<th><?=Loc::getMessage('RECURSIVE');?></th>
			<th><?=Loc::getMessage('DEL');?></th>
		</tr>
		
		<?if( $arResult = Manao\Easytarget\DataTable::getResult() ):?>
		
			<input type="hidden" name="et_open_window" value="Y">
			<input type="hidden" name="CUR_PAGE" value="<?=$curPage?>">
			
			<?foreach ($arResult as $key => $itemTarget):?>
				<tr>
					<input type="hidden" name="ET_FORM[<?=$key?>][ID]" value="<?=$itemTarget['ID']?>">
					<input type="hidden" name="ET_FORM[<?=$key?>][URL]" value="<?=$itemTarget['URL']?>">
					<td>
						<input type="text" name="ET_FORM[<?=$key?>][DOM_ELEMENT_ID]" value="<?=htmlspecialcharsbx( stripslashes($itemTarget['DOM_ELEMENT_ID']) );?>" size="40">
					</td>
					<td>
						<textarea type="text" name="ET_FORM[<?=$key?>][SCRIPT_BODY]" rows="4" cols="40"><?=$itemTarget['SCRIPT_BODY']?></textarea>
					</td>
					<td>
						<input type="checkbox" name="ET_FORM[<?=$key?>][ALL_PAGES]" value="Y" <?=($itemTarget['RECURS'] == 'Y') ? 'checked' : '' ;?>>
					</td>
					<td>
						<input type="checkbox" name="ET_FORM[<?=$key?>][DELETE]" value="Y">
					</td>
				</tr>
			<?endforeach;?>
		<?else:?>
			<input type="hidden" name="et_open_window" value="Y">
			<input type="hidden" name="CUR_PAGE" value="<?=$curPage?>">
			
			<tr>
				<input type="hidden" name="ET_FORM[0][ID]" value="">
				<input type="hidden" name="ET_FORM[0][URL]" value="<?=$curPage?>">
				<td>
					<input type="text" name="ET_FORM[0][DOM_ELEMENT_ID]" value="" size="40">
				</td>
				<td>
					<textarea type="text" name="ET_FORM[0][SCRIPT_BODY]" rows="4" cols="40"><?=$itemTarget['SCRIPT_BODY']?></textarea>
				</td>
				<td>
					<input type="checkbox" name="ET_FORM[0][ALL_PAGES]" value="Y">
				</td>
				<td>
					<input type="checkbox" name="ET_FORM[0][DELETE]" value="Y">
				</td>
			</tr>
		<?endif;?>
		<?if($_POST['elemSelect'] == 'Y'):?>
			<tr>
				<input type="hidden" name="ET_FORM[0][ID]" value="">
				<input type="hidden" name="ET_FORM[0][URL]" value="<?=$curPage?>">
				<td>
					<input type="text" name="ET_FORM[0][DOM_ELEMENT_ID]" value="<?=$_POST['selector']?>" size="40">
				</td>
				<td>
					<textarea type="text" name="ET_FORM[0][SCRIPT_BODY]" rows="4" cols="40"><?=$itemTarget['SCRIPT_BODY']?></textarea>
				</td>
				<td>
					<input type="checkbox" name="ET_FORM[0][ALL_PAGES]" value="Y">
				</td>
				<td>
					<input type="checkbox" name="ET_FORM[0][DELETE]" value="Y">
				</td>
			</tr>
		<?endif;?>
	</table>
	<a class="add_string" onclick="addRow();"><?=Loc::getMessage('ADD_STRING');?></a> 
	<input type="submit" style="display: none;">
</form>
<?if($_POST['create_popup'] != 'Y'):?>
<script>
	var buttons = window.seopopup.ShowButtons();
	for(let i = 0; i < buttons.length; i++){
		seopopup.PARTS.FOOT.appendChild(buttons[i]);
	}
</script>
<?endif;?>


