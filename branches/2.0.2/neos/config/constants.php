<?php
/** 
 * Definições de Constantes do Sistema 
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Config
 */

/**
 * EXTENSIONS
 */
define('EXT',								'.php');
define('EXTVW',								'.html');
define('EXTMOD',							EXT);
define('EXTMDL',							EXT);
define('EXTCTRL',							EXT);
define('EXTLIB',							EXT);
define('EXTHLP',							EXT);

/**
 * CORE
 */
defined('PATH_XLIB') || define('PATH_XLIB',	PATH . DS . 'framework' . DS . 'tools' . DS); //extended librarys

/**
 * APP
 */
define('APP_CONTROLLER',					PATH_APP . DS. 'controller' . DS);
define('APP_VIEW', 							PATH_APP . DS. 'view' . DS);
define('APP_MODEL',							PATH_APP . DS. 'model' . DS);
define('APP_LIBRARY',						PATH_APP . DS. 'library' . DS);
define('APP_HELPER',						PATH_APP . DS. 'helper' . DS);
define('APP_MODULE',						PATH_APP . DS. 'modulo' . DS);
define('APP_CACHE',							PATH_APP . DS. 'cache' . DS);
define('APP_REPORT',						PATH_APP . DS. 'report' . DS);
define('APP_CONFIG',						PATH_APP . DS);

/**
 * WEB
 */
defined('PATH_WWW') || define('PATH_WWW',	PATH . DS);