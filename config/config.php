<?php
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'electromax');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('APP_NAME', 'ElectroMax');
define('APP_URL', 'http://localhost/ElectroMax');
define('APP_TIMEZONE', 'America/La_Paz');

date_default_timezone_set(APP_TIMEZONE);
session_start();
