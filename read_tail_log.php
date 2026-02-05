<?php
$log = 'storage/logs/laravel.log';
if (file_exists($log)) {
    $content = file_get_contents($log);
    echo substr($content, -2000);
} else {
    echo "Log file not found at " . realpath($log);
}
