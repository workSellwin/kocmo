<?php

\Lui\Kocmo\Handler\SetHandler::SetModuleHandler();

AddEventHandler("main", "OnBeforeUserRegister", ['Lui\Kocmo\Handler', 'OnBeforeUserRegister']);

//AddEventHandler("main", "OnBeforeUserRegister", ['Lui\Kocmo\Handler', 'OnAfterUserRegister']);

AddEventHandler("main", "OnBeforeEventSend", ['Lui\Kocmo\Handler', 'OnBeforeEventSend']);


AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array('Lui\Kocmo\Handler', "OnBeforeIBlockElementUpdateHandler"));
