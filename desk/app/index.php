<?php

/** Desk Web Bootstrap File */

/*
|----------------------------------------------------------------
| Make sure this is PHP 5.4 or later
|----------------------------------------------------------------
*/
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50400) {
    exit('Desk requires minimum PHP 5.4.0 or later, but your running ' . PHP_VERSION . '. Please upgrade to PHP 5.4.0!');
}

/*
|----------------------------------------------------------------
| Set Default Time Zone
|----------------------------------------------------------------
*/
date_default_timezone_set('Asia/Kolkata');

/*
|----------------------------------------------------------------
| Define Methods
|----------------------------------------------------------------
*/
$createFolder = function ($path) {
    if (!is_dir($path)) {
        $oldumask = umask(0);

        if (!@mkdir($path, 0755, TRUE)) {
            http_response_code(503);
            exit('Could not create a folder at ' . $path);
        }

        chmod($path, 0755);
        umask($oldumask);
    }
};

$ensureDirReadable = function ($path, $writable = FALSE) {
    $realpath = realpath($path);

    if ($realpath === FALSE || !is_dir($realpath) || !@file_exists($realpath . '/.')) {
        http_response_code(503);

        exit(($realpath !== FALSE ? $realpath : $path) . ' doesn\'t exist or isn\'t writable by PHP.');
    }

    if ($writable) {
        if (!is_writable($realpath)) {
            http_response_code(503);

            exit ($realpath . ' isn\'t writable by PHP.');
        }
    }
};

/*
|----------------------------------------------------------------
| Determine the Paths
|----------------------------------------------------------------
*/

// App path - we are already in here
$appPath = __DIR__;

// By default the desk/ folder will be one level up
$deskPath = realpath(dirname($appPath));
// By default all the remaining folders will be in desk/ folder
$configPath = ($deskPath . '/config');
$storagePath = ($deskPath . '/storage');
$licensePath = ($configPath . '/license.key');

/*
|----------------------------------------------------------------
| Create necessary folders
|----------------------------------------------------------------
*/
$createFolder($storagePath);
$createFolder($storagePath . '/runtime');
$createFolder($storagePath . '/logs');

/*
|----------------------------------------------------------------
| Validate Paths
|----------------------------------------------------------------
*/

// Validate permissions on desk/config/
$ensureDirReadable($configPath);
// Validate License Key file
if (!file_exists($licensePath)) {
    exit($licensePath . ' doesn\'t exist.');
}

// Validate permissions on desk/storage/
$ensureDirReadable($storagePath, TRUE);
$ensureDirReadable($storagePath . '/runtime', TRUE);
$ensureDirReadable($storagePath . '/logs', TRUE);

// Log errors to desk/storage/logs/errors.log
ini_set('log_errors', 1);
ini_set('error_log', $storagePath . '/logs/errors.log');

/*
|----------------------------------------------------------------
| Setup Yii debug variables as early as possible
|----------------------------------------------------------------
*/

if (DESK_DEV_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    defined('YII_DEBUG') || define('YII_DEBUG', TRUE);
    defined('YII_ENV') || define('YII_ENV', 'dev');
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    defined('YII_DEBUG') || define('YII_DEBUG', FALSE);
    defined('YII_ENV') || define('YII_ENV', 'prod');
}

/*
|----------------------------------------------------------------
| Load the composer dependencies
|----------------------------------------------------------------
*/
require $appPath . '/vendor/autoload.php';
require $appPath . '/vendor/yiisoft/yii2/Yii.php';
require $appPath . '/Desk.php';