<?php
/** 
 * Helper extendido pelos Models (geral).
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Db
 * @subpackage	Model
 * @access 		public
 * @since		CAN : B4BC
 */

namespace Db;

//TODO : CRIAR!!

class Model
	extends \NEOS {
	
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
	 * Retorna o resultado em um Array 
	 *
	 * @param array $q array de objetos retornado de uma consulta SQL.
	 * @return bool|array False se $q não contiver dados e um Array composto, com os dados
	*/
	public static function toArray($q){
		if($q){
			foreach($q as $l=>$lv){foreach($lv as $k=>$v){$o[$l][$k] = $v;}}
			return $o;
		} else return false;
	}
	 
	
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