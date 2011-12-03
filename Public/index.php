<?php
define('SEP',DIRECTORY_SEPARATOR);
define('_CAN','A93H001');
$cfg->app=dirname(dirname(__FILE__)).SEP;
$cfg->core=$cfg->app;
include	'../Config/config.php';
include '../Views/splash.php';