<?php
/** 
 * Objeto que representa o resultado de uma consulta MYSQL.
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Db
 * @subpackage	Result\Mysql
 * @access 		public
 * @return		object Retorna dados de uma operação no banco de dados MYSQL
 * @since		CAN : B4BC
 */

namespace Neos\Db;

//TODO : CRIAR!!

class mysql {
	
	/**
	* Campos da tabela e suas definições
	*/	
	public $fields = array();
	
	/**
	* Nome da tabela/recurso (pode ser um view ou relacionamento entre tabelas).
	*/
	public $table = NULL;
	
	/**
	* Tipo de recurso (ex.: banco de dados, xml, arquivo "ini", etc)
	*/
	public $engine = 'mysql';
	
	/**
	* Handle da operação
	*/
	public $handle = NULL;
	
	
	
	/**
	* Realiza a operação
	*
	* @return bool True em caso de sucesso
	*/	
	public function commit(){
		
	}	 
	
	/**
	* Pegando um ou mais linhas do resultado
	*
	* @param number $row Número da linha a ser retornada
	* @return array Array com os resultados
	*/	
	public function row($row = 1){
		
	}
		
	/**
	* Pegando todas as linhas do resultado
	*
	* @return array Array com os resultados
	*/	
	public function all(){
		
	}
	
	/**
	* Retorna o número de linhas do resultado da operação
	*
	* @return number Número de linhas retornadas/afetadas pela operação
	*/	
	public function rows(){
		
	}
	
	/**
	* Apaga o conteúdo da consulta
	*
	* @return bool Falso/True
	*/	
	public function clear(){
		
	}
	
}