<?php
$dir = str_replace('.php', '/', __FILE__);
foreach (scandir($dir) as $d) {
    $dirD = $dir . $d . '/';
    if (strlen($d) >= 3 and is_dir($dirD)) {
        $catalogD = opendir($dirD);
        while ($filename = readdir($catalogD)) {
            $file = $dirD . $filename;
            if (is_file($file)) {
                 include_once ($file);
            }
        }
    }
}
