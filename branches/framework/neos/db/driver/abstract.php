<?php
/** 
 * Abstract Driver For Extends.
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Db
 * @subpackage	Driver
 * @access 		public
 */
namespace Db\Driver;

 //Para mais informações sobre as funções desta classe veja os comentários na Interface Driver.
 
abstract class Abstract_
	extends \NEOS
	implements Interface_ {
	
	private $con;
	public 	$num_rows=0;
	
	/**
	 * referencia estática a própria classe!
	 */
	//static $THIS = array();
	

	/**
	 * Construtor singleton da própria classe.
	 * Acessa o método estático para criar uma instância da classe automáticamente.
	 *
	 * @param string $var Método invocado.
	 * @param string $val Parâmetros do método invocado (ignorado!).
	 * @return this instance
	*/
//	public static function this(){
//		$name = get_called_class();
//		if (!isset(static::$THIS[$name])) static::$THIS[$name] = new static;
//		return static::$THIS[$name];
//	}
	
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
//	final function __call($var, $val) {
//		return _helper($var, $val);		
//	}

}