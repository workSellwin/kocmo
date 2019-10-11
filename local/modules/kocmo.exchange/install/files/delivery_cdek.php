<?php
//Автоматизированный обработчик службы доставки СДЭК
//Документация: https://dev.1c-bitrix.ru/api_help/sale/delivery.php
//b_sale_delivery_handler

use Bitrix\Main\Loader,
	Bitrix\Main\Config\Option,
	Bitrix\Main\Data\Cache,
	Manao\Cdek;

Loader::includeModule('sale');
Loader::includeModule('manao.cdek');

class CDeliveryCdek {
	
	const CACHE_TIME = 0;
	
	public function Init(){
	
		$arInit = array(
			'SID' => 'cdek',
			'NAME' => 'Доставка до пункта выдачи СДЭК',
			'DESCRIPTION' => '',
			'BASE_CURRENCY' => Option::get('sale', 'default_currency', 'RUB'),
			'HANDLER' => __FILE__,
			'GETCONFIG' => array('CDeliveryCdek', 'GetConfig'),
			'DBGETSETTINGS' => array('CDeliveryCdek', 'ReadFromDb'),
			'COMPABILITY' => array('CDeliveryCdek', 'CheckCompatibility'),
			'CALCULATOR' => array('CDeliveryCdek', 'DoCalculate'),
			'PROFILES' => array(
				'cdek_136' => array(
					'TITLE' => 'Доставка до пункта выдачи СДЭК',
					'DESCRIPTION' => 'Тестовое описание',
					'RESTRICTIONS_WEIGHT' => [0],
					'RESTRICTIONS_SUM' => [0]
				)
			)
		);
		return $arInit;
	}
	
	public function GetConfig(){
	
		$arConfig = array(
			'CONFIG_GROUPS' => array(
				'cdek_136' => 'Параметры основного профиля'
			),
			'CONFIG' => array()
		);
		return $arConfig;
	}
	
	public function ReadFromDb($sSettings) {
		return unserialize($sSettings);
	}
	
	public function CheckCompatibility($arOrder, $arConfig) {//добавить проверку на соответствие настроек модуля

		if(/*$deliveryPrice > $maxDeliveryCost || ( $maxCost != 0 && $arOrder['PRICE'] > $maxCost && $arOrder['PRICE'] != 0 && $arOrder['PRICE'] != 100500 ) */false){
			return array();
		}
		else{
			return array('cdek_136');//Массив подходящих профилей доставки
		}
	}
	
	public function DoCalculate($sProfile, $arConfig, $arOrder, $step, $sTemp = false){

		$ld = self::getLocationData($arOrder['LOCATION_FROM'], $arOrder['LOCATION_TO']);
		return self::getResultData($ld['CDEK_IDS'], $ld['CDEK_DATA']);
	}
	
	private static function getLocationData($from, $to){
		
		$res = Cdek\CdekCitiesTable::getList([
			'filter' => ['BITRIX_ID' => [$from, $to]]
		]);
		
		$cdekIds = [];
		$cityData = [];
		
		while( $fields = $res->fetch() ){
			
			if($from == $fields['BITRIX_ID']){
				$cdekIds['FROM'] = $fields['CDEK_ID'];
			}
			else{
				$cdekIds['TO'] = $fields['CDEK_ID'];
				$cityData = $fields;
			}
		}
		return ['CDEK_IDS' => $cdekIds, 'CDEK_DATA' => $cityData];
	}
	
	private static function getResultData($cdekIds, $cityData){
	
		$cache = Cache::createInstance();
		$cacheId  = implode(';', $cdekIds);
		
		if($cache->initCache(self::CACHE_TIME, $cacheId)){
			$result = $cache->getVars();
		}
		elseif($cache->startDataCache()){
		
			$utils = new Cdek\CdekUtils();
			$request = $utils->sendRequest($cdekIds['TO']);
	
			$price = $utils->getCustomerPrice();

			if ($price){
				$cache->abortDataCache();
			}
			$periodArr = $utils->getTransitionPeriod();
			
			$periodArr['min'] += Option::get('manao.cdek', 'cdek-interval-min', '0');
			$periodArr['max'] += Option::get('manao.cdek', 'cdek-interval-max', '0');
			$dateString = str_replace(['#FROM_DAY#', '#TO_DAY#'], [$periodArr['min'], $periodArr['max']], Option::get('manao.cdek', 'cdek-delivery-description', ''));
			
			$cityName = $utils->getCityNameUtf8($cityData['NAME']);

			$result = array(
				'RESULT' => 'OK',
				'VALUE' => $price,
				'TRANSIT' => $periodArr['min'] . '-' . $periodArr['max'],
				'TRANSIT_MIN' => $periodArr['min'],
				'periodFrom' => $periodArr['min'],
				'TRANSIT_MAX' => $periodArr['max'],
				'periodTo' => $periodArr['max'],
				'DATE_STRING' => $dateString,
				'DAYS_STRING' => $periodArr['min'] . '-' . $periodArr['max'] . ' ' . $utils->getLocaleStrDays($periodArr['max']),
				'PVZ_COUNT' => $utils->getPvzCount($cdekIds['TO']),
				'PVZ_MAP_DATA' => $utils->getPvzDataForYaMap($cdekIds['TO']),
				'PVZ_POINT' => $utils->getPvzPoints($cdekIds['TO']),
			);
			$cache->endDataCache($result);
		}
		return $result;
	}
}

AddEventHandler('sale', 'onSaleDeliveryHandlersBuildList', array('CDeliveryCdek', 'Init'));
?>