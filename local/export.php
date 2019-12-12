<?php
//https://documenter.getpostman.com/view/155604/SVtZwmvs?version=latest
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('kocmo.exchange');

use Bitrix\Main\Context,
    Kocmo\Exchange,
    Kocmo\Exchange\StaticFactory;

file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/export-log.txt', $_GET['step'] . "\n" . "time - " . date("H:i:s") . "\n");
$request = Context::getCurrent()->getRequest();
$uri = $request->getRequestedPage();

if( empty($_GET['step']) ){
    $step = 0;
}
else{
    $step = $_GET['step'];
}

$bx = StaticFactory::factory($step);
//echo '<pre>' . print_r($bx, true) . '</pre>';
//die('ff');
if($bx) {
    $flag = $bx->update();
echo $step;

if($step > 30) die();
    if ($bx->getStatus() == 'run') {
        header('Location: ' . $uri . '?step=' . $step);
        exit;
    } elseif ($bx->getStatus() == 'end') {
        $step = StaticFactory::nextStep($step);
        header('Location: ' . $uri . '?step=' . $step);
        exit;
    }
}