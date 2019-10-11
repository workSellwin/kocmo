<? IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/metadata/install/install.php"); ?>
<?
	Bitrix\Main\Localization\Loc::loadMessages(__FILE__);
?>
<style>
	.unistall-label{
		display:block;
		line-height:2;
	}
	.unistall-label span{
		display: inline-block;
		width: 80%;
		font-size: 16px;
	}
	form.uninst-step1 input[type="submit"]{
		margin-top:10px;
	}
</style>
<form class="uninst-step1" action="<?echo $APPLICATION->GetCurPage()?>">
<?=bitrix_sessid_post()?>
	<input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
	<input type="hidden" name="id" value="kocmo.exchange">
	<input type="hidden" name="uninstall" value="Y">
	<input type="hidden" name="step" value="2">
	<?echo CAdminMessage::ShowMessage(GetMessage("MOD_UNINST_WARN"))?>
	<label class="unistall-label"><span><?=GetMessage("UNINST_DB");?></span><input type="checkbox" name="uninstall_db" value="Y"></label>
	<label class="unistall-label"><span><?=GetMessage("UNINST_FILES");?></span><input type="checkbox" name="uninstall_files" value="Y"></label>
	<label class="unistall-label"><span><?=GetMessage("NAFIG");?></span><input type="checkbox" name="all_off" value="Y"></label>
	<label class="unistall-label"><span><?=GetMessage("RETURN");?></span><input type="checkbox" name="return_to_the_past" value="Y"></label>
	<div><input type="submit" name="inst" value="<?echo GetMessage("MOD_UNINST_DEL")?>"></div>
</form>