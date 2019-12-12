<?

use Bitrix\Sale;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (isset($_REQUEST['subscribe_form']) && $_REQUEST['subscribe_form'] == "Y") {
    $GLOBALS['APPLICATION']->RestartBuffer();
    $EMAIL = $_REQUEST['EMAIL_SUBSCRIBE'];
    if (CModule::IncludeModule('subscribe')) {
        global $USER;
        $arFields = Array(
            "USER_ID" => ($USER->IsAuthorized() ? $USER->GetID() : false),
            "FORMAT" => "text",
            "CONFIRMED" => 'N',
            'DATE_INSERT' => date("d.m.Y H:i:s"),
            "EMAIL" => $EMAIL,
            "ACTIVE" => "Y",
            "RUB_ID" => array(2),
        );
        $subscr = new CSubscription;
        //can add without authorization
        $ID = $subscr->Add($arFields);
        if ($ID > 0) {
            CSubscription::Authorize($ID);
            echo '<span style="color: green">Спасибо за подписку!</span>';
        } else {
            $strWarning = "<span style='color: red'>Ошибка при добавлении подписки: " . $subscr->LAST_ERROR . "</span>";
            echo $strWarning;
        }

    }
    die();
}

$this->IncludeComponentTemplate();

?>