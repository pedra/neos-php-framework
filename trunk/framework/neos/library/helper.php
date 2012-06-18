<?php 
namespace Library;

class Helper{
	
	/**
	 * Construtor singleton da própria classe.
	 * Acessa o método estático para criar uma instância da classe automáticamente.
	 *
	 * @return object this instance
	*/
	final public static function this(){
		$class = __CLASS__;
		if (!isset(\NEOS::$THIS[$class])) \NEOS::$THIS[$class] = new $class;
		return \NEOS::$THIS[$class];
	}
		
	public static function __callStatic($func, $args){
		return static::getHelper($func, $args);
	}
	
	//acesso direto para a classe BASE / NEOS
	public static function getHelper($func, $args, $dir = false){
		//ajustando o array de argumentos
		if($dir && is_array($args[0])) $args = $args[0];
		
		//Se a função ja foi carregada, retorna o resultado...
		if(function_exists($func)) return call_user_func_array($func, $args);
		
		//Caso contrário, tenta carregar
		if(file_exists( APP_HELPER . str_replace('_', DS, trim($func, ' _')) . EXTHLP ))
			include_once APP_HELPER . str_replace('_', DS, trim($func, ' _')) . EXTHLP;
		elseif(file_exists(PATH_NEOS . '/neos/library/helper/' . str_replace('_', '/', trim($func, ' _')) . EXTHLP))
			include_once PATH_NEOS . '/neos/library/helper/' . str_replace('_', '/', trim($func, ' _')) . EXTHLP;		
		else {			
			return false;
		}
		if(function_exists($func)) return call_user_func_array($func, $args);
		else return false;
	}
	
	public function __call($func, $args){
		return static::getHelper($func, $args);
	}
}