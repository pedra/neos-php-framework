<?php  /* Mini framework WEB/PHAR compativel */
	namespace Lib;
	
	
	ob_start('ob_gzhandler');

	//Constantes
	define('INITIME', strstr(microtime(), ' ', true)*1000);
	define('VERSION', '0.4');
	defined('PATH') || define('PATH', dirname($_SERVER['DOCUMENT_ROOT'].$_SERVER['SCRIPT_NAME']).'/');
	define('RPATH', ((strpos(PATH,'phar://') === false) ? PATH : str_replace('phar://', '',dirname(PATH).'/')));
	define('LIB', dirname(dirname(__FILE__)).'/');
	define('VIEW', PATH.'view/');
	define('EXTVW', '.html'); //extensão de arquivo view
	require_once 'utils/functions.php'; //carregando as funções de apoio

	//iniciando o carregador automático de classes (autoLoader)		
	set_include_path('.'.PATH_SEPARATOR.str_replace('phar:', 'phar|', LIB).PATH_SEPARATOR.str_replace('phar:', 'phar|', PATH).trim(get_include_path(), ' .'));

	//setando o carregamento automático - autoLoader
	spl_autoload_register(
		function ($class){
			$class = ltrim('/'.strtolower(trim(strtr($class, '_\\', '//'), '/ ')),' /\\').'.php';							
			$pth = explode(PATH_SEPARATOR, ltrim(get_include_path(), '.'));
			array_shift($pth);
			foreach($pth as $f){if(file_exists($f = str_replace('phar|', 'phar:', $f).$class)) return require_once $f;}
		}
	);
	//carregando a configuração
	Loader::this()->loadConfig(); 
	
	//alias para algumas classes		
	class_alias('\Lib\Loader', '_cfg');
	class_alias('\Lib\Base', 'Base');

	//alias setados no CONFIG
	if(isset(_cfg()->output->manager)) class_alias(_cfg()->output->manager, '_view');
	if(isset(_cfg()->db->manager)) class_alias(_cfg()->db->manager, '_db');	
	
	//Chamando o controller
	Decurl::this()->runController();

	//finalizando o sistema - mostrando a view já processada
	header('Expires: '.gmdate('D, d M Y H:i:s', time() + 31536000).' GMT');
	header('Cache-Control: max-age=290304000');
	header('X-Powered-By: itbras.com/neos/fw');
	\_view::this()->produce();
		
//*********************** BASE *****************************
abstract class Base {

	/**
	 * referencia estática a própria classe!
	 * Todas as classes que "extends" essa BASE armazenam sua instância singleton neste array.
	 */
	static $THIS = array();


	/**
	 * Construtor singleton da própria classe.
	 * Acessa o método estático para criar uma instância da classe automáticamente.
	 *
	 * @param string $class Classe invocada.
	 * @return object this instance
	*/
	final public static function this(){
		$class = get_called_class();
		if (!isset(static::$THIS[$class])) static::$THIS[$class] = new static;
		return static::$THIS[$class];
	}
	
	/**
	 * Simples setter!.
	 * Acessa e modifica um atributo privado ou público da classe.
	 *
	 * @param string $var nome do atributo.
	 * @param mixed $val novo valor do atributo.
	 * @return mixed|null retorna o valor modificado ou null se o atributo não for acessível (não existir).
	*/
	static function set($var, $val){
		return self::this()->$var = $val;		
	}
	
	/**
	 * Simples getter!.
	 * Retorna o valor de um atributo privado ou público da classe.
	 *
	 * @param string $var nome do atributo.
	 * @return mixed|null retorna o valor ou null se o atributo não for acessível (não existir).
	*/	
	static function get($var = null){
		if($var == null) return self::this();//retorna TODOS os argumentos da classe
		if(isset(self::this()->$var)) return self::this()->$var;
		return null;		
	}

	/*
	 * Dispara o sistema de ERRORs
	 *
	 * @param $msg String Mensagem de erro a ser exibida
	 * @param $cod Number (se existir) Código da ajuda para o erro
	 *
	 * @return void 	Gera um erro no sistema!
	 */
	 static function _error($msg, $cod = 0, $class = null){
		\Lib\Error\Error::this()->codigo = $cod;
		\Lib\Error\Error::this()->classPath = ($class != null) ? $class : get_called_class();
		trigger_error($msg);
	 }
	 
}		
//*********************** DECURL ***************************
class Decurl
	extends Base {
		
	/*
	* detecta se o acesso está sendo feito por SSL (https)
	*/
	private static function _detectSSL(){
		if (!isset($_SERVER["HTTPS"]))		return false;
		if ($_SERVER["HTTPS"] == "on")		return true;
		if ($_SERVER["HTTPS"] == 1)			return true;
		if ($_SERVER['SERVER_PORT'] == 443) return true;
		return false;
	}

 /*
	* Decodifica a solicitação da URL (usuário) - se for um arquivo da pasta "public" envia e sai
	*/	
	private function decodeUrl(){
		$req = urldecode((strpos($_SERVER['REQUEST_URI'], '.phar') === false)
					? str_replace(basename(__FILE__),'',$_SERVER['REQUEST_URI'])
					: $_SERVER['REQUEST_URI']);
		$e = explode('/', str_replace('\\', '/', trim($_SERVER['SCRIPT_NAME'], '\\/ ')));
		$u = explode('/', str_replace('\\', '/', trim($req, '\\/ ')));		
		$rq = '';
		$ur = '/';
		
		foreach($u as $k=>$v){
			if($v != ''){
				if(!isset($e[$k]) || $v != $e[$k]) $rq .= $v.'/';
				else $ur .= $v.'/';
			}
		}
		define('BASE', 'http'.(self::_detectSSL() ? 's' : '').'://'.trim($_SERVER['HTTP_HOST'],' /').rtrim($ur, '/ ').'/');
		$rt = trim($rq, '/ ');
		
		//retirando variáveis GET
		$tp = strpos($rt, '?');
		if($tp !== false) $rt = substr($rt, 0, $tp);
		//se for uma solicitação de arquivo - entrega e sai
		if(strpos($rt,'public/') !== false) return $this->assets($rt);
		return $rt;
	}
	
	/* Tratando requisições da pasta 'public' (assets: js, css, img, etc)
	 *
	 */
	private function assets($rt){
		if(_cfg('intPublic') && file_exists(PATH.'_'.$rt)) {
			//gerando header apropriado
			include PATH.'lib/mimes.php';
			$ext = explode('.', $rt);
			if(isset($_mimes[end($ext)]))
				header('Content-type: '.((is_array($_mimes[end($ext)])) ? $_mimes[end($ext)][0] : $_mimes[end($ext)]));
				header('Expires: '.gmdate('D, d M Y H:i:s', time() + 31536000).' GMT');
				header('Cache-Control: max-age=290304000, public');
				header('X-Powered-By: itbras.com/neos/fw');
			//enviando o arquivo solicitado
			ob_end_clean();
			ob_start('ob_gzhandler');					
			exit(file_get_contents(PATH.'_'.$rt));
			} else header('Location: '.BASE.$rt, TRUE, 301);		
	}

   /*
	* Pega o controlador/método/argumentos da solicitação
	*/
	function runController(){		
		$args = explode('/', $this->decodeUrl());
		
		//controller encontrado
		if(isset($args[0]) && file_exists(PATH.'controller/'.strtolower($args[0]).'.php')){
			$ctrl = ucfirst(strtolower(array_shift($args)));
			$func = (isset($args[0]))? strtolower(array_shift($args)):'index';//pegando o método
			$args = (isset($args[0]))? $args : array();//pegando os dados		
		} else { //default
			$ctrl = 'Main';
			$func = 'index';			
		}
		//carregando o arquivo & instanciando o controller
		include PATH.'controller/'.strtolower($ctrl).'.php';
		$ctrl = new $ctrl($ctrl,$func,$_REQUEST);

		//definindo e chamando o método do controller
		if(is_callable(array($ctrl, $func))) call_user_func_array(array($ctrl, $func), $args);
		elseif(is_callable(array($ctrl, 'index'))) {
			$func = 'index'; 
			call_user_func_array(array($ctrl, $func), $args);}
		else exit('FATAL ERROR :: Método "'.$func.'" não existe');

		//retornando a instância do controller
		return $ctrl;
	}
}
//*********************** LOADER ***************************		
class Loader
	extends Base {

	/**
	* Carregando arquivo ".ini"
	*/
	final static function loadConfig($file = ''){
		//atualiza o arquivo ini da classe
		if($file != '' && file_exists($file)) self::this()->fileIni = $file;
		else self::this()->fileIni = PATH.'default.ini';
			
		//pega os dados do arquivo
		$a = parse_ini_file(self::this()->fileIni, true);
		//carrega os dados na classe Loader
		foreach($a as $k=>$v){
			if(is_array($v)){
				 foreach($v as $kk=>$vv){
					 if(is_array($vv)){
						 foreach($vv as $kkk=>$vvv){
							 if(!isset(self::this()->$k->$kk)) self::this()->$k->$kk = new \stdClass();
							 self::this()->$k->$kk->$kkk = $vvv;
							}
					 } else {
						if(!isset(self::this()->$k)) self::this()->$k = new \stdClass();				
						self::this()->$k->$kk = $vv;
					}
				}
			}else{self::this()->$k = $v;}
		}
	}

	/**
	* Salvando arquivo ".ini"
	*/
	final static function saveConfig($file, $var = null){
		if(is_null($var)) $var = get_object_vars(self::this());
		//pegando os ítens sem seção e colocando como "geral"
		foreach($var as $k=>$v){
				if(!is_array($v)) $x['geral'][$k] = $v;
				else $x[$k]=$v;
		}
		return self::toIniFile($x, $file);
		
		$o = '';
		foreach($x as $k=>$v){
			$o .= '['.$k."]\r\n";
			//segundo nó
			if(is_array($v)){
				foreach($v as $_k=>$_v){
					//terceiro nó
					if(is_array($_v)){
						foreach($_v as $__k=>$__v){
							if(is_array($__v)) $__v = print_r($__v, true);
							$o .= "\t".$_k.'['.$__k.'] = '.(is_numeric($__v)? $__v : '"'.$__v.'"')."\r\n";
						}
					}else $o .= "\t".$_k.' = '.(is_numeric($_v)? $_v : '"'.$_v.'"')."\r\n";
				}
			}
		}
		if($file != null) return file_put_contents($file, $o);
		else return $o;		
	}
	
	/**
	* Cria um arquivo ".ini"
	*
	* @param Array $ini		Array contendo os dados a serem convertidos
	* @param String $file	Caminho e nome do arquivo ".ini"
	*
	* @return Bool|String	Se $file for indicado retorna o status da criação/grvação do arquivo
				Se $file não for indicado retorna uma string com os dados convertidos
	*/
	private static function toIniFile($ini, $file = null){
		if(!(is_array($ini) || is_object($ini))) return false;
		$o = '';
		foreach($ini as $k=>$v){
			$o .= '['.$k."]\r\n";
			//segundo nó
			if(is_array($v)){
				foreach($v as $_k=>$_v){
					//terceiro nó
					if(is_array($_v)){
						foreach($_v as $__k=>$__v){
							if(is_array($__v)) $__v = print_r($__v, true);
							$o .= "\t".$_k.'['.$__k.'] = '.(is_numeric($__v)? $__v : '"'.$__v.'"')."\r\n";
						}
					}else $o .= "\t".$_k.' = '.(is_numeric($_v)? $_v : '"'.$_v.'"')."\r\n";
				}
			}
		}
		if($file != null) return file_put_contents($file, $o);
		else return $o;
	}
}