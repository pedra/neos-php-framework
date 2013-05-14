<?php
namespace Lib\Db;

class Sdba
	extends \Base {
	
	
	private $driver = null;
	
	
	function __construct($alias = null){
		if($alias == null){
			if($db = _cfg('db') != null) $alias = $db[$db['default']];
			else trigger_error('Banco de dados não configurado');
		}
		//carregando o driver
		$driver = $db[$alias];
		$this->driver = new $driver($db[$alias]);
	}
	
	function query($sql, $inicio = null, $final = null){
		return $this->driver->query($sql, $inicio, $final);		
	}
	
	
}