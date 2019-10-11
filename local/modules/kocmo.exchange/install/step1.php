<? use Bitrix\Main\Localization\Loc;?>
<? Loc::loadMessages(__FILE__); ?>
<form action="<?=$APPLICATION->GetCurPage()?>" name="form1">
<?=bitrix_sessid_post()?>
<input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
<input type="hidden" name="id" value="kocmo.exchange">
<input type="hidden" name="install" value="Y">
<input type="hidden" name="step" value="2">

	<table cellpadding="3" cellspacing="0" border="0" width="0%">
		<tr>
			<td><b><?= GetMessage("SELECT_SITE") ?></b></td>
		</tr>
		<tr>
			<td>
				<p>
					<select name="SELECT_SITE">
					<? $rsSites = CSite::GetList($by="SORT", $order="ASC"); ?>
					<? while ($arSite = $rsSites->Fetch()): ?>
					<option value="<?= $arSite['ID']?>">
						<?= $arSite['NAME']?>
					</option>
					<? endwhile; ?>
					</select>
				</p>
			</td>
		</tr>
	</table>		
	<br>
	<input type="submit" name="inst" value="<?= GetMessage("MOD_INSTALL")?>">
</form>