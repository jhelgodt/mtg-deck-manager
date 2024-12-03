<?php
require_once __DIR__ . '/src/functions/env_loader.php';
loadEnv(__DIR__ . '/.env');

define('BASE_PATH', __DIR__);
define('DB_PATH', BASE_PATH . '/src/db.php');