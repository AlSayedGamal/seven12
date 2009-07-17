<?php
define('DB', 0);

$production['dbHost'] = 'localhost';
$production['dbName'] = '';
$production['dbUser'] = '';
$production['dbPass'] = '';

$development['dbHost'] = 'localhost';
$development['dbName'] = '';
$development['dbUser'] = '';
$development['dbPass'] = '';

// deploy what? $production OR $development
$db = ($_SERVER["REMOTE_ADDR"] == "127.0.0.1") ? $development : $production;

define('DB_HOST', $db['dbHost']);
define('DB_NAME', $db['dbName']);
define('DB_USER', $db['dbUser']);
define('DB_PASS', $db['dbPass']);
?>
