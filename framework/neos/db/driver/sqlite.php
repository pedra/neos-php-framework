<?php
/** 
 * Driver para Sqlite.
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Db
 * @subpackage	Model
 * @access 		public
 * @return		object Retorna dados de uma operação no banco de dados SQLITE
 * @since		CAN : B4BC
 */
namespace Db\Driver;

	//Para mais informações sobre as funções desta classe veja os comentários na Interface do Driver.

class Sqlite 
	extends Abstract_ {

	private $con;
	public 	$num_rows=0;
	
	//CONECTANDO
	function connect($al = ''){
		$cfg = \_cfg::this();
		if($al == ''){$al = $cfg->db->active;}
		$r = sqlite_open($cfg->db->{$al}->database, 0666, $e);
		$this->error = $e;		
		$this->con = $r;
		if(!$this->con){trigger_error("Não foi possível conectar ao Banco de Dados: " . $e);}
		return $r;
	}
	//Fechando a conexão
	function close(){
		sqlite_close($this->con);	
	}
	//Simulando PREPARE
	function prepare($sql = '', $nome = 'sql'){
		if(isset($this->{$nome})){trigger_error('Error in prepare');return false;}
		$this->{$nome}=$sql;		
	}
	//Simulando BIND
	function bind($var, $val, $nome = 'sql', $exec = false){
		if(!isset($this->{$nome})){trigger_error('Bind sem Prepare!?');return false;}		
		if(!is_numeric($val)){$val="'".$val."'";} //acrescenta aspas automaticamente		
		if(!is_array($var)){$this->{$nome}=str_ireplace($var,$val,$this->{$nome});}
		else{
			$ci=0;
			foreach($var as $key=>$val){
				${"val$ci"}=$val;
				$this->{$nome}=str_ireplace($key,${"val$ci"},$this->{$nome});
				$ci++;				
				}		
			}
    	if($exec==true){return $this->execute($nome);}
	}
	//Simulando EXECUTE - PREPARE+BIND
	function execute($nome = 'sql'){
		unset($this->res);
		$this->num_rows=0;
		if(!isset($this->{$nome})){trigger_error('Executar oque?!');return false;}
		$r=sqlite_query($this->con,$this->{$nome});
		if(is_resource($r)){
			while($x=sqlite_fetch_object($r)){$this->res[]=$x;}
			$this->num_rows=sqlite_num_rows($r);
			if(!isset($this->res)){return array();}else{return $this->res;}
		}else{
			$this->num_rows=sqlite_changes($this->con);
			return $r;}
	}
	//QUERY
	function query($sql = ''){
		if($sql==''){trigger_error('Query sem argumentos!');return false;}
		unset($this->res);
		$r=sqlite_query($this->con,$sql);
		if(is_resource($r)){		
			while($x=sqlite_fetch_object($r)){$this->res[]=$x;}
			$this->num_rows=sqlite_num_rows($r);
			if(!isset($this->res)){return array();}else{return $this->res;}
		}else{
			$this->num_rows=sqlite_changes($this->con);
			return $r;}				
	}	
	//INSERT
	function insert($t='', $a = array()){
		unset($this->res);
		$this->num_rows=0;
		if(!is_array($sql) || $t == ''){trigger_error('Formato inválido para INSERT!');return false;}
		$virgula='';$keys='';$valores='';
		foreach($a as $key=>$value){
			$keys.=$virgula.$key;
			is_string($value) ? $aspa="'" : $aspa='';
			$valores.=$virgula.$aspa.sqlite_escape_string($value).$aspa;
			$virgula=',';
		}
		$this->query('INSERT INTO '.trim($t).' ('.$keys.') VALUES ('.$valores.')');
		return sqlite_last_insert_rowid($this->con);
	}
	//UPDATE
	function update($t = '', $a = array(), $w = ''){
		if(!is_array($a) || $t == '' || $w == ''){trigger_error('Formato inválido para UPDATE!');return false;}
		$kk='';$yy=array();
		foreach($a as $k=>$v){
			if(is_string($v)){$v="'".$v."'";}
			$kk.=$k.'='.$v.' ,';$yy[]=$v;}
		$k=substr($kk,0,-1);
		$this->query('UPDATE '.trim($t).' SET '.$k.' WHERE '.trim($w));
		return sqlite_changes($this->con);
	}
	
	//Não implementado
	function get_file($sql, $file , $download){ return false;}
	
}
?>