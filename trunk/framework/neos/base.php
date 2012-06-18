<?php
/**
 * Base para todos os objetos do sistema.
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Neos\Base
 * @subpackage	Test
 * @access 		public
 */
 
namespace Neos;

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
	 * Gravando (adicionando) dados no arquivo de logs.
	 * Adiciona $content ao final do arquivo de log.
	 *
	 * @param string $content Conteúdo a ser gravado
	 * @param string $file Caminho e nome do arquivo de log
	 * @return void
	*/
	static final function pushToLog($content = NULL, $file = NULL){
		if(\_cfg::this()->logfile == '') return false;
		if($file == NULL) $file = \_cfg::this()->logfile;
		if($content == NULL) $content = _pt($GLOBALS,false,true);
		if(!is_array($content)) $contt = $content;
		else{
			$contt = "\n" . date('Y-m-d H:i:s');
			foreach($content as $v){$contt .= ' | ' . $v;}
		}
		//gravando o log
		file_put_contents($file, $contt, FILE_APPEND);
	}

	/**
	 * Pegando os HELPERS.
	 * Quando uma função não for encontrada pode ser uma chamada para um helper.
	 * Então, esta rotina tenta localizar um helper que corresponda a solicitação.
	 * Retorna o que retornar o helper ou uma mensagem de erro em caso de falha.
	 *
	 * @param string $var Nome da função solicitada
	 * @param array $val valor repassado
	 * @return mixed
	*/
	final function __call($func, $args) {
		$func = trim($func, ' _');
		//Se a função ja foi carregada, retorna o resultado...
		if(function_exists($func)) return call_user_func_array($func, $args);
		//chamando a classe Helper para resolver a parada...
		return Library\Helper::getHelper($func, $args, true);
	}
	
	/*
	 * Dispara o sistema de ERROR do NEOS
	 *
	 * @param $msg String Mensagem de erro a ser exibida
	 * @param $cod Number (se existir) Código da ajuda para o erro 
	 *
	 * @return void 	Gera um erro no sistema!	 
	 */	 
	 static function _error($msg, $cod = 0, $class = null){
		\Error\Error::this()->codigo = $cod;
		\Error\Error::this()->classPath = ($class != null) ? $class : get_called_class(); 
		trigger_error($msg);		 
	 }	
}