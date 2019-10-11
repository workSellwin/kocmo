<?if(!check_bitrix_sessid()) return;?>
<? use Bitrix\Main\Localization\Loc; ?>
<?
if ($this->ERROR === false || empty($this->ERROR)):
	echo CAdminMessage::ShowNote( Loc::getMessage("MOD_UNINST_OK") );
else:
	for($i=0; $i<count($this->ERROR); $i++)
		$alErrors .= $this->ERROR[$i]."<br>";
	echo CAdminMessage::ShowMessage(Array("TYPE"=>"ERROR", "MESSAGE" => Loc::getMessage("MOD_UNINST_ERR"), "DETAILS"=>$alErrors, "HTML"=>true));
endif;
?>
<form action="<?=$APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?=LANG?>">
	<input type="submit" name="" value="<?=Loc::getMessage("MOD_BACK")?>">
</form>
