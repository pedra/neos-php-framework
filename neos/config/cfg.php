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
namespace Config;

class Cfg
	extends \NEOS {
		
	/**
	 * Array contendo TODOS os ítens de configuração carregados
	 */
	public $varConfig = NULL;
	

	function __construct(){				
		//Pegando os arquivos de configuração
		$this->loadConfig(array('app\constants', 'constants', 'geral', 'app\config'));
		//TimeZone
		date_default_timezone_set($this->timezone);
		//incluindo as funções globais do núcleo
		include (PATH_NEOS . '/neos/library/helper/functions.php');
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
		
		$cfg = $this;
		
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

		unset($cfg);
		//habilitando erros...
		ini_set('display_errors', true);					
	}

}

