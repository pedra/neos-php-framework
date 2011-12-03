<?php
/**
 * Classe de Configuração do Sistema (CORE)
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Neos\Config
 * @subpackage	Test
 * @access 		public
 * @return		void
 */
 
//Definindo o NAMESPACE
namespace Neos\Config; 


class Cfg {
		
	/**
	 * referencia Singleton!
	 */
	static $THIS = null;

	/**
	 * Array contendo TODOS os ítens de configuração carregados
	 */
	public $varConfig = NULL;
	
	/**
	 * Ativa/Desativa a captura de erros
	 */
	public $errorOn = true;


	private function __construct(){
		//modifficando a tela de exibição de erros do PHP
		if(function_exists('ini_set')){
			ini_set('error_prepend_string', file_get_contents(PATH_NEOS . '/Neos/Error/head.html') . '<p>');
			ini_set('error_append_string', '</p>' . file_get_contents(PATH_NEOS . '/Neos/Error/footer.html'));
		}		
		//Pegando os arquivos de configuração
		$this->loadConfig(array('constants', 'geral', 'app\config'));		

		//setando o include_path
		set_include_path(implode(PATH_SEPARATOR, array(PATH_APP, PATH_NEOS, get_include_path())));

		//setando a classe de tratamento de erros
		set_error_handler(array(__CLASS__, 'error'));
		set_exception_handler(array(__CLASS__, 'exception'));

		//setando a classe de carregamento automático
		if(!function_exists('spl_autoload_register')) $this->error("spl_autoload não foi instalado neste sistema (PHP)");
		spl_autoload_register(array(__CLASS__, 'autoload'));
	}
	
	/* Construtor singleton da própria classe.
	 * Acessa o método estático para criar uma instância da classe automáticamente.
	 * @return 'this' instance
	*/
	public static function this(){
		return (!isset(static::$THIS)) 
		? static::$THIS = new static 
		: static::$THIS;
	}

	/**
	* Carregador de arquivos de configuração.
	* Para carregar um arquivo de configuração da aplicação basta prefixar com "app\\" o nome dos arquivos.
	*
	* @param string|array Nome do arquivo de configuração a ser carregado (ou um aray de vários!).
	* @return object
	*/
	function loadConfig($file = NULL){		
		if($file == NULL) return false;
		$cfg = $this->varConfig;
		
		//Caso seja indicado apenas um arquivo (string)...
		if(is_string($file)) $file = array($file);		
		
		//Pegando cada um dos arquivos indicados
		foreach($file as $f){
			$f = strtolower($f);			
			$dir = PATH_NEOS . 'neos/config' . DS . $f . '.php';
			
			//teste
			$dirt = PATH_NEOS . 'neos/config' . DS . $f . '_test.php';
			
			if(strpos($f, 'app\\') !== false) {  //caso seja da Aplicação
				$f = str_replace('app\\', '', $f);
				$dir = APP_CONFIG . $f . '.php';
				//teste
				$dirt = APP_CONFIG . $f . '_test.php';
			}
			//desativando a captura de erros temporariamente
			$this->errorOn = false;
			//teste - carrega primeiro			
			if (NEOS_STATE != \_neos::$arrayStates[0] && file_exists($dirt)) include_once ($dirt);
			elseif (file_exists($dir)) include_once ($dir);
			$this->errorOn = true;	
		}		
		//atualizando a variável config
		$this->varConfig = $cfg;
		unset($cfg);			
	}

	/**
	 * Carregador "automático" de classes
	 *
	 * @param string $class Nome da classe a carregar
	 * @return void Localiza e carrega o arquivo contendo a classe solicitada
	*/
	function autoload($class){
		$class = trim(str_replace('_','\\',$class), '\\ ');		
		return include (file_exists($class . '_' . NEOS_STATE . EXT)) 
		? $class . '_' . NEOS_STATE . EXT 
		: $class . EXT;
	}

	/**
	 * Tratamento de Exceções
	 *
	 * @param $e objeto Exception
	 * @return void
	*/
	static function exception($e){
		restore_error_handler();
		\Neos\Error\Error::exception($e);
	}

	/**
	 * Tratamento de Erros
	 *
	 * @param $n nível de erro
	 * @return void
	*/
	function error($n, $m, $f, $l){
		if(@self::this()->errorOn == false) return true;
		throw new \ErrorException($m, 0, $n, $f, $l);
	}


	/**
	 * Pegando os parâmetros de configuração.
	 * Retorna o objeto da classe Config contendo TODOS os parâmetros de configurações.
	 *
	 * @return object 
	*/
	static function cfg($item = NULL){
		$cfg = self::this();
		if($item == NULL) { return $cfg->varConfig; }
		else { return $cfg->varConfig->{$item}; }
	}
	
	/**
	 * Pegando os parâmetros de configuração.
	 * Retorna o objeto da classe Config contendo TODOS os parâmetros de configurações ou
	 * o parâmetro especificado em "$obj".
	 *
	 * @param string $obj nome do nó (objeto) de configuração (opcional).
	 * @return object 
	*/	
	static function &get($obj = ''){
		if($obj != '') return self::this()->{$obj}->varConfig;
		return self::this()->varConfig;
	}
	
	/**
	 * Setando (e criando) parâmetros de configuração.
	 * Retorna o mesmo conteúdo do $valor indicado.
	 *
	 * @param string	$obj Nome do nó (objeto) de configuração (opcional).
	 * @param string	$item Ìtem a ser modificado.
	 * @param mixed		$valor Um dado a ser armazenado (mixed)
	 * @return mixed
	*/	
	static function set($obj, $item, $valor){
		return self::this()->varConfig->$obj[$item] = $valor;
	}

}

