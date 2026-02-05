<?php
$content = file_get_contents('c:\xampp\htdocs\hopitals\app\Models\Patient.php');
$lines = explode("\n", $content);
foreach ($lines as $i => $line) {
    if (stripos($line, 'boot') !== false) {
        echo ($i+1) . ": " . trim($line) . "\n";
    }
}
