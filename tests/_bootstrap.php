<?php

define('YII_ENV', 'dev');

// Use the current installation of Craft
define('CRAFT_BASE_PATH', __DIR__.'/src/');
define('CRAFT_STORAGE_PATH', __DIR__.'/_craft/storage');
define('CRAFT_TEMPLATES_PATH', __DIR__.'/_craft/templates');
define('CRAFT_CONFIG_PATH', __DIR__.'/_craft/config');
define('CRAFT_VENDOR_PATH', __DIR__.'/../vendor');

$devMode = true;

$vendorPath = realpath(CRAFT_VENDOR_PATH);
$craftPath = realpath(CRAFT_BASE_PATH);

$configPath = realpath($craftPath.'/config');
$contentMigrationsPath = realpath($craftPath.'/migrations');
$pluginsPath = realpath($craftPath.'/plugins');
$storagePath = realpath($craftPath.'/storage');
$templatesPath = realpath($craftPath.'/templates');
$translationsPath = realpath($craftPath.'/translations');

// Log errors to craft/storage/logs/phperrors.log

ini_set('log_errors', 1);
ini_set('error_log', $storagePath.'/logs/phperrors.log');

error_reporting(E_ALL);
ini_set('display_errors', 1);
defined('YII_DEBUG') || define('YII_DEBUG', true);
defined('YII_ENV') || define('YII_ENV', 'dev');
defined('CRAFT_ENVIRONMENT') || define('CRAFT_ENVIRONMENT', '');

defined('CURLOPT_TIMEOUT_MS') || define('CURLOPT_TIMEOUT_MS', 155);
defined('CURLOPT_CONNECTTIMEOUT_MS') || define('CURLOPT_CONNECTTIMEOUT_MS', 156);

// Load the files
$srcPath = __DIR__.'/../src';
require $vendorPath.'/yiisoft/yii2/Yii.php';
require $srcPath.'/Craft.php';

// Set aliases
Craft::setAlias('@config', $configPath);
Craft::setAlias('@contentMigrations', $contentMigrationsPath);
Craft::setAlias('@plugins', $pluginsPath);
Craft::setAlias('@storage', $storagePath);
Craft::setAlias('@templates', $templatesPath);
Craft::setAlias('@translations', $translationsPath);
