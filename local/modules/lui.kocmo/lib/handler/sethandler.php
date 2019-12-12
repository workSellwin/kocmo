<?php

namespace Lui\Kocmo\Handler;

class SetHandler
{
    public static function SetModuleHandler()
    {
        $dh = opendir(__DIR__);
        $arFiles = [];
        while (false !== ($filename = readdir($dh))) {
            if (!in_array($filename, ['.', '..', 'sethandler.php', 'basehandler.php'])) {
                $arFiles[] = $filename;
            }
        }
        $arEvent = [];
        foreach ($arFiles as $file) {
            $className = str_replace('.php', '', __NAMESPACE__ . "\\" . $file);
            if (class_exists($className)) {
                $arVars = get_class_vars($className);
                $module = $arVars['module'];
                $arMethods = get_class_methods($className);
                foreach ($arMethods as $method) {
                    $arEvent[$module][] = [
                        'class' => $className,
                        'name' => $method
                    ];
                }
            }
        }
        if ($arEvent) {
            foreach ($arEvent as $module => $event) {
                foreach ($event as $e) {
                    \Bitrix\Main\EventManager::getInstance()->addEventHandler(
                        $module,
                        $e['name'],
                        [$e['class'], $e['name']]
                    );
                }
            }
        }


    }


}
