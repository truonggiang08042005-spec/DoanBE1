<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'football_booking');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/';

$publicIndex = '/public/index.php';
if (substr($scriptName, -strlen($publicIndex)) === $publicIndex) {
    $basePath = substr($scriptName, 0, -strlen($publicIndex));
} else {
    $basePath = rtrim(dirname($scriptName), '/\\');
}

$basePath = rtrim($basePath, '/');
define('BASE_URL', $protocol . $host . ($basePath === '' ? '/' : $basePath . '/') );


