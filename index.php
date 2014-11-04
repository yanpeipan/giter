<?php
define('YII_DEBUG', True);
define('YII_TRACE_LEVEL', 3);

$yii=dirname(__FILE__).'/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
$vendor=dirname(__FILE__).'/vendor/autoload.php';

require_once($vendor);
require_once($yii);
Yii::createWebApplication($config)->run();
