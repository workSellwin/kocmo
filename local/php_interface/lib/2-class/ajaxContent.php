<?php

use Bitrix\Main\Context;

class AjaxContent
{

    static function Start($CONTENT_ID)
    {
        $request = Context::getCurrent()->getRequest();
        if ($request->get('CONTENT_ID') == $CONTENT_ID && $request->get('ACTION') == 'ajax') {
            $GLOBALS['APPLICATION']->RestartBuffer();
        } else {
            echo "<div id='{$CONTENT_ID}'>";
        }
    }

    static function Finish($CONTENT_ID)
    {
        $request = Context::getCurrent()->getRequest();
        if ($request->get('CONTENT_ID') == $CONTENT_ID && $request->get('ACTION') == 'ajax') {
            die();
        } else {
            echo '</div>';
        }
    }
}
