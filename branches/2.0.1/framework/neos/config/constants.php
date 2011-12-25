<?php
/** 
 * Definições de Constantes do Sistema 
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
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
 * APP
 */
define( 'APP_CONTROLLER' ,					PATH_APP . 'controller' . DS);
define( 'APP_VIEW' , 						PATH_APP . 'view' . DS);
define( 'APP_MODEL' ,						PATH_APP . 'model' . DS);
define( 'APP_LIBRARY' ,						PATH_APP . 'library' . DS);
define( 'APP_HELPER' ,						PATH_APP . 'helper' . DS);
define( 'APP_MODULE' ,						PATH_APP . 'modulo' . DS);
define( 'APP_CACHE' ,						PATH_APP . 'cache' . DS);
define( 'APP_REPORT' ,						PATH_APP . 'report' . DS);
define( 'APP_CONFIG' ,						PATH_APP . 'config' . DS);

/**
 * WEB
 */
define( 'PATH_WWW' ,						PATH . 'www' . DS);