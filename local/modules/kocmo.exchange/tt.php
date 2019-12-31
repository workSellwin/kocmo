<?
\Bitrix\Main\Loader::includeModule('kocmo.exchange');

$options  = [
'KLIENT_NUMBER'   => '1104009121',//'1001027795',
'KLIENT_NUMBER_BY'   => '1104009121',//'1001027795',
'KLIENT_KEY'      => 'CA461909F1DFED320BFBCA5B90A002AD5756D6BF',//'182A17BD6FC5557D1FCA30FA1D56593EB21AEF88',
'KLIENT_CURRENCY' => 'BYN',
'IS_TEST'         => true,
'DB' => [	'DSN' => 'mysql:dbname=sitemanager;host=localhost',
'PASSWORD' => 'rSJ(b%UBvnXWYViJ!KUm',
'USERNAME' => 'bitrix0'],
];
$config  = new \Ipol\DPD\Config\Config($options);

$shipment = new \Ipol\DPD\Shipment($config);
$shipment->setSender('Беларусь',  'Минская','г Минск');
$shipment->setReceiver('Беларусь', 'Минская', 'Щомыслица');
//pr($shipment,14);
/**
* Отправка от
* false - от двери
* true  - от терминала
*/
$shipment->setSelfPickup(false);

/**
* Отправка до
* false - от двери
* true  - от терминала
*/
$shipment->setSelfDelivery(false);

$shipment->setItems([
[
'NAME'       => 'Тестовый заказ ТТ',
'QUANTITY'   => '8',
'PRICE'      => 2412.00,
'VAT_RATE'   => 'Без НДС',
'WEIGHT'     => 1000,
'DIMENSIONS' => [
'LENGTH' => 200,
'WIDTH'  => 100,
'HEIGHT' => 50,
]
],
]);

$order = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('order')->makeModel();
$order->setShipment($shipment);

$order->orderId = 1222;

$order->serviceCode = 'NDY';

$order->senderName = 'Наименование отправителя';
$order->senderFio = 'ФИО отправителя';
$order->senderPhone = 'Телефон отправителя';

// если отправка от двери как минимум необходимо указать улицу
// в поле улица можно указать полный адрес (улица, дом, строение), но тогда есть вероятность
// что заявка попадет в статус OrderPending - требуется проверка со стороны DPD
$order->senderStreet  = 'Ленина 31';

// так же при отправке от двери можно заполнить поля
// senderStreetAbbr - уббревиатура улицы
// senderHouse - номер дома
// senderKorpus - корпус
// senderStr - строение
// senderVlad - владение
// senderOffice - номер оффиса


$order->receiverName = 'Наименование получателя';
$order->receiverFio = 'ФИО получателя';
$order->receiverPhone = 'Телефон получателя';

// если отправка до двери как минимум необходимо указать улицу
// в поле улица можно указать полный адрес (улица, дом, строение), но тогда есть вероятность
// что заявка попадет в статус OrderPending - требуется проверка со стороны DPD
$order->receiverStreet = 'ул Щомыслица, дом 28, корп. 2';

// так же при отправке до двери можно заполнить поля
// receiverStreetAbbr - уббревиатура улицы
// receiverHouse - номер дома
// receiverKorpus - корпус
// receiverStr - строение
// receiverVlad - владение
// receiverOffice - номер оффиса

$order->pickupDate = date('Y-m-d');
$order->pickupTimePeriod = '9-18';
//$order->cargoValue = 2412;
$order->setUnitLoads(2412);

$result = $order->dpd()->create();
pr($result, 14);
//pr($order->dpd(), 14);