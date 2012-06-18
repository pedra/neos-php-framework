<?php
/**
 * Classe de Configuração do Sistema (CORE)
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
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
	

	private function __construct(){				
		//Pegando os arquivos de configuração
		$this->loadConfig(array('constants', 'geral', 'app\config'));		
	}
	
	/* Construtor singleton da própria classe.
	 * Acessa o método estático para criar uma instância da classe automáticamente.
	 * @return 'this' instance
	*/
	public static function this(){
		return (!isset(static::$THIS)) ? static::$THIS = new static : static::$THIS;
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
		
		//evitando 'Strict Standards' no arquivo de configuração
		ini_set('display_errors', false);
		
		//Pegando cada um dos arquivos indicados
		foreach($file as $f){
			$f = strtolower($f);
			
			if(strpos($f, 'app\\') !== false) {  //caso seja da Aplicação
				$f = str_replace('app\\', '', $f);
				$base = APP_CONFIG . $f;
			}else{$base = PATH_NEOS . '/neos/config' . DS . $f;}
			
			//teste - carrega primeiro
			if (file_exists($base . NEOS_STATE . '.php')) include ($base . NEOS_STATE . '.php');
			elseif (file_exists($base . '.php')) include ($base . '.php');
			elseif (strpos($base, APP_CONFIG) === false) exit ('Arquivo [' . str_replace(array('/','\\'), DS, $base) . '] de configuração não encontrado.');
		}
		//atualizando a variável config
		$this->varConfig = $cfg;
		unset($cfg);
		//habilitando erros...
		ini_set('display_errors', true);					
	}

	/**
	 * Pegando os parâmetros de configuração.
	 * Retorna o objeto da classe Config contendo TODOS os parâmetros de configurações.
	 *
	 * @return object 
	*/
	static function cfg($item = NULL){
		return ($item == NULL) ? self::this()->varConfig : self::this()->varConfig->{$item};
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

