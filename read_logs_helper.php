<?php
$file = 'storage/logs/laravel.log';
$lines = 20;
$data = shell_exec("powershell -Command \"Get-Content -Path $file -Tail $lines\"");
echo $data;
