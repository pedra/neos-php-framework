<?php
ob_clean();
defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('PPATH') || define('PPATH', __DIR__ . DS);
Phar::interceptFileFuncs();
defined('APP_ERROR') || define('APP_ERROR', '');
set_include_path( get_include_path() . PATH_SEPARATOR . PPATH);

//pegando o PATH físico do ARQUIVO PHAR
$x = explode('/', $_SERVER['SCRIPT_FILENAME']);
array_pop($x);
$x = str_replace(array('/', '\\'), DS, implode('/', $x));
define('RPATH', $x . DS); 

//obtendo o que foi requerido depois do script (http://site/scpt.phar/PATH_INFO)
$file = explode('/',$_SERVER['SCRIPT_NAME']);
$file = trim(str_replace($file, '', $_SERVER['REQUEST_URI']), ' /');

//Retirando o ADMIN_URL, do CoreService
$t = defined('NEOS_INIT_TIME') ? strpos($file, _cfg::this()->admin_url) : false;
if($t !== false) $file = substr($file, $t + strlen(_cfg::this()->admin_url) + 1);

//URL_BASE
$arquivo = end(explode('/', $_SERVER['SCRIPT_NAME']));
$path_info = trim(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO']: '/', ' /');
$p = str_replace(array($arquivo, $path_info), '', $_SERVER['REQUEST_URI']);
defined('URL_BASE') || define('URL_BASE', trim(((_detectSSL_xxx()) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . $p, ' /') . '/');

//URL_BASEX
/*$ind = ((_detectSSL_xxx()) ? 'https://' : 'http://');
if(strpos(URL_BASE, $ind) !== false) $ind = '';
$def = ((_detectSSL_xxx()) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . '/' . trim(str_replace($path_info, '', $_SERVER['REQUEST_URI']), ' /') . '/';
$def = defined('NEOS_INIT_TIME') ? ((_detectSSL_xxx()) ? 'https://' : 'http://') . URL_BASE . _cfg::this()->admin_url . '/' : $def;
define('URL_BASEX', $def);*/

//1º achar script file
$temp = explode('/', $_SERVER['SCRIPT_FILENAME']);
$script = end($temp);
$root = explode('/', $_SERVER['DOCUMENT_ROOT']);

//2º descobrir o excedente entre o root e script file
foreach($root as $k=>$d){if($temp[$k] == $d) unset($temp[$k]);}

//3º juntando tudo
$phpself = implode('/', $temp);
$ssl = _detectSSL_xxx() ? 'https://' : 'http://';
define('URL_BASEX', trim($ssl . $_SERVER['SERVER_NAME'] . '/' . $phpself, ' /') . '/' . _cfg::this()->admin_url . '/');



//finalizando o core: include ou download
if(!_download($file)) exit( _controller($file));

//inclue arquivo de controle
	function _controller($file){
		header('X-Powered-By: NEOS PHP Framework');
		if($file == '/' || $file == '' || strpos($file, 'inc/') !== false ) $file = 'index.php';
		foreach(array('','.php','.html') as $ext){
			if(is_file(PPATH . $file . $ext)) return include_once(PPATH . $file . $ext);
		}
		return false;
	}
//detecta se o acesso está sendo feito por SSL (https)
	function _detectSSL_xxx(){
		if (!isset($_SERVER["HTTPS"]))		return false;
		if ($_SERVER["HTTPS"] == "on")		return true;
		if ($_SERVER["HTTPS"] == 1)			return true;
		if ($_SERVER['SERVER_PORT'] == 443) return true;
		return false;
	}
//imprime e sai (exit())
	function _p($a, $ex = true){
		$pt = '<pre>' . print_r( $a , true) . '</pre>';
		if($ex) exit($pt);	
		echo $pt;
	}
//faz o download do arquivo solicitado
	function _download($file){
		if(strpos($file, '.php') !== false || !is_file(PPATH . $file)) return false;
		//procurando o mime type                                             
		include PPATH . '../neos/config/mimes.php';
		$ext = explode('.', $file);
		$ext = end($ext);	//_p($file, false);_p($_mimes);
		if(!isset($_mimes[$ext])){$mime = 'text/css';}
		else{$mime = (is_array($_mimes[$ext])) ? $_mimes[$ext][0] : $_mimes[$ext];}
		//pegando o arquivo
		$dt = file_get_contents(PPATH . $file);
		//enviando
		defined('NEOS_INIT_TIME') || ob_start('ob_gzhandler');
		header('Content-Type: ' . $mime);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
		//header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($file)) . ' GMT');
		header('Cache-Control: max-age=31536000');
		header('X-Powered-By: NEOS PHP Framework');
		header('Content-Length: ' . strlen($dt));                
		//saindo...
		exit($dt);		
	}