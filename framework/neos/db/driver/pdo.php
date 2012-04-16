<?php
/** 
 * Driver para PDO.
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Db
 * @subpackage	Model
 * @access 		public
 * @return		object Retorna dados de uma operação no banco de dados com PDO
 * @since		CAN : B4BC
 */
namespace Neos\Db\Driver;

	//Para mais informações sobre as funções desta classe veja os comentários na Interface do Driver.

class Driver_PDO 
	extends Abstract_ {
	
	private $hdl;
	private $stm;
	private $ret		= \PDO::FETCH_BOTH;
	const OBJ 			= \PDO::FETCH_OBJ ;
	const ARR			= \PDO::FETCH_BOTH;
	const ARR_ASSOC		= \PDO::FETCH_ASSOC;
	const ARR_NUM		= \PDO::FETCH_NUM;
	public $num_rows	= 0;
	
	function connect($al = ''){
		$cfg = \_cfg::cfg();
		$u = ''; $p = '';
		if($al == ''){$al = $cfg->db->active;}
		if(isset($cfg->db->{$al}->dsn)){
			$d = $cfg->db->{$al}->dsn;
			if(isset($cfg->db->{$al}->user)){$u = $cfg->db->{$al}->user;}
			if(isset($cfg->db->{$al}->pass)){$p = $cfg->db->{$al}->pass;}
		}else{ trigger_error('DSN indefinido para o Banco de Dados!'); return false;}
		$opt = array(\PDO::ATTR_PERSISTENT => true);
		if(isset($cfg->db->{$al}->options) && is_array($cfg->db->{$al}->options)) $opt = array_merge($opt, $cfg->db->{$al}->options);
		$this->hdl = new \PDO($d,$u,$p,$opt);	
		if(is_object($this->hdl)){return true;}else{return false;}
	}
	//query PDO
	function pquery($ql = ''){
		if($ql==''){return array();}
		if($this->stm=$this->hdl->query($ql)){$this->num_rows=$this->stm->rowCount();return $this;}else{return false;}
	}
	//query simples
	function query($sql = ''){
		if($sql==''){return array();}
		if($this->stm=$this->hdl->query($sql)){
			$this->num_rows=$this->stm->rowCount();
			return $this->all();
		}else{return false;}
	}	
	//insert
	function insert($t = '', $a = array()){
		if(!is_array($a) || $t==''){ return array();}
		$kk='';$y='';$yy=array();
		foreach($a as $k=>$v){$kk.=$k.',';$y.=' ? ,';$yy[]=$v;}
		$k=substr($kk,0,-1);$y=substr($y,0,-1);
		$t='INSERT INTO '.trim($t).' ('.$k.') VALUES ('.$y.')';
		$this->stm=$this->hdl->prepare($t);
		if($this->stm->execute($yy)){return $this->stm->rowCount();}else{return false;}
	}
	//update
	function update($t = '', $a = array(), $w = ''){
		if(!is_array($a) || $t=='' || $w==''){return array();}
		$kk='';$yy=array();
		foreach($a as $k=>$v){$kk.=$k.'=? ,';$yy[]=$v;}
		$k=substr($kk,0,-1);
		$t='UPDATE '.trim($t).' SET '.$k.' WHERE '.$w;
		$this->stm=$this->hdl->prepare($t);
		if($this->stm->execute($yy)){return $this->stm->rowCount();}else{return false;}
	}	
	//retorna tudo...
	function all($ret = self::OBJ){
		if(!is_object($this->stm)){return array();}		
		if(trim($ret)==''){return false;}
		return $this->stm->fetchAll($ret);
	}
	//retorna a proxima linha do resultado
	function row($ret = self::OBJ){
		if(!is_object($this->stm)){return array();}		
		if(trim($ret)==''){return false;}
		return $this->stm->fetch($ret);
	}
	
	/**
	 * Simulando o 'PREPARE', caso o banco de dados não o possua (como em Mysql/Sqlite)
	 * 
	 * @param string $sql Uma 'query' com a sinalização das variaveis a serem substituidas
	 * 						por uma função 'bind'.
	 * @param string $nome Nome da query (podem ser feitos vários 'PREPARE's) 
	 * @return void
	 */
	function prepare($sql = '', $nome = 'sql'){}
	
	/**
	 * Bind (simulação)
	 * 
	 * @param mixed		$var	Nome da variável como definido no 'prepare' ou array (nome=>valor).
	 * @param mixed		$val	Valor.
	 * @param string	$nome	Nome do 'prepare', se foi definido (default = 'sql').
	 * @param bool		$exec	Indica se a query deve ser executada imediatamente.
	 * @return recurse	Retorna o resultado da função 'execute' ou nada, dependendo do estado de '$exec'.
	 */
	function bind($var, $val, $nome = 'sql', $exec = false){}
	
	/**
	 * Simulando o 'EXECUTE', caso o banco de dados não o possua (como em Mysql/Sqlite)
	 * 
	 * @param string $nome Nome da query em 'prepare' (opcional).
	 * @return recurse Retorna um apontador para a consulta slq ou nada em caso de erro.
	 */
	function execute($nome = 'sql'){}
	
	//Não implementado
	function get_file($sql, $file , $download){ return false;}
}