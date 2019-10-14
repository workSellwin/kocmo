<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class kocmo_exchange extends CModule
{
	public $MODULE_ID = "kocmo.exchange";
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $PARTNER_NAME;
	public $PARTNER_URI;
	public $IBLOCK_TYPE = "kocmo_exchange";
	public $ERROR = array();

	function kocmo_exchange() {
		$arModuleVersion = array();
		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");
		
		if (is_array($arModuleVersion) && isset($arModuleVersion["VERSION"]))
		{
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

		$this->MODULE_NAME = Loc::getMessage("K_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("K_MODULE_DESCRIPTION");
		
		$this->PARTNER_NAME = "Sellwin";
		$this->PARTNER_URI = "https://kocmo.by";
	}
	
	function InstallFiles($arParams = array()){

        $bl1 = copyDirFiles(
            $_SERVER['DOCUMENT_ROOT'] . getLocalPath("modules/" . $this->MODULE_ID. "/install/admin"),
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin',
            true, true
        );
		return boolval($bl1);//($bl1 && $bl2);
	}

	function UnInstallFiles(){

		$bl1 = DeleteDirFilesEx("/bitrix/admin/kocmo_exchange.php");

		return boolval($bl1);
	}
	
	function installDB(){

		global $DB;
		return $DB->RunSqlBatch( __DIR__ . '/DB/mySQL/install.sql' );
	}

	function UnInstallDB(){

		global $DB;
		return $DB->RunSqlBatch( __DIR__ . '/DB/mySQL/uninstall.sql' );
	}
	
	function DoInstall(){
	
		global $APPLICATION, $step;
		
		$step = (int)$step;
		$this->ERROR = false;

		if(!ModuleManager::isModuleInstalled("iblock"))
			$errors = Loc::getMessage("K_UNINS_IBLOCK");
		else
		{
			if( $step < 2 ){
				$APPLICATION->IncludeAdminFile(
					Loc::getMessage("K_INSTALL_TITLE"),
					$_SERVER["DOCUMENT_ROOT"].getLocalPath("modules/".$this->MODULE_ID."/install/step1.php")
				);
			}
			elseif ( $step == 2 ){
        
				$this->InstallFiles();
				$this->installDB();

				RegisterModule($this->MODULE_ID);	
				$APPLICATION->IncludeAdminFile(
					Loc::getMessage("K_INSTALL_TITLE"),
					$_SERVER["DOCUMENT_ROOT"].getLocalPath("modules/".$this->MODULE_ID."/install/step2.php")
				);
			}
		}
	}
	
	function DoUninstall(){
	
		global $APPLICATION, $step;

		$step = (int)$step;
		
		if( $step < 2 ){
			$APPLICATION->IncludeAdminFile(
				Loc::getMessage("K_UNINSTALL_TITLE"),
				$_SERVER["DOCUMENT_ROOT"].getLocalPath("modules/".$this->MODULE_ID."/install/unstep1.php")
			);
		}
		elseif( $step == 2 ){
		
			$this->ERROR = false;
			
			if($_REQUEST['uninstall_files'] == 'Y'){
				$this->UnInstallFiles();
			}
			if($_REQUEST['uninstall_db'] == 'Y'){
				$this->UnInstallDB();
			}

			UnRegisterModule($this->MODULE_ID);
			
			$APPLICATION->IncludeAdminFile(
				Loc::getMessage("K_UNINSTALL_TITLE"),
				$_SERVER["DOCUMENT_ROOT"].getLocalPath("modules/".$this->MODULE_ID."/install/unstep2.php")
			);
		}
	}
}
