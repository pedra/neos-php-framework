<?php
/** 
 * Driver para Mysql.
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Db
 * @subpackage	Model
 * @access 		public
 * @return		object Retorna dados de uma operação no banco de dados MYSQL
 * @since		CAN : B4BC
 */
namespace Db\Driver;

 //Para mais informações sobre as funções desta classe veja os comentários na Interface Driver.
 
class Mysql
	extends Abstract_ {
	
	private $con;
	public 	$num_rows=0;
	public 	$erro = '';
	public	$query = '';
	
	//CONECTANDO
	function connect($al = ''){
		$cfg = \_cfg::this();
		if($al == ''){$al = $cfg->db->active;}
		$r = mysql_pconnect($cfg->db->{$al}->host, $cfg->db->{$al}->user, $cfg->db->{$al}->pass);
		if(isset($cfg->db->{$al}->charset)){ if(!mysql_set_charset($cfg->db->{$al}->charset)){ return false; }}
		if(isset($cfg->db->{$al}->database)){ if(!mysql_select_db($cfg->db->{$al}->database)){ return false; }}		
		$this->con = $r;
		if(!$this->con) self::_error("N&atilde;o foi poss&iacute;vel conectar ao Banco de Dados: ".mysql_error());
		return $r;
	}
	//Simulando PREPARE
	function prepare($sql='',$nome='sql'){
		if(isset($this->{$nome})) self::_error('Error in prepare',2);
		$this->{$nome}=$sql;		
	}
	//Simulando BIND
	function bind($var, $val, $nome = 'sql', $exec = false){
		if(!isset($this->{$nome})) self::_error('Bind sem Prepare!?',3);		
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
		if(!isset($this->{$nome})) self::_error('Executar oque?!',4);
		$r=mysql_query($this->{$nome});
		if(is_resource($r)){
			while($x=mysql_fetch_object($r)){$this->res[]=$x;}
			$this->num_rows=mysql_num_rows($r);
			if(!isset($this->res)){return array();}else{return $this->res;}
		}else{
			$this->num_rows= mysql_affected_rows();
			return $r;}
	}
	//QUERY	
	function query($sql = ''){
		if($sql=='') self::_error('Query sem argumentos!',5);
		unset($this->res);
		$this->query = $sql;
		$r=mysql_query($sql);
		if(is_resource($r)){		
			while($x=mysql_fetch_object($r)){$this->res[]=$x;}
			$this->num_rows=mysql_num_rows($r);
			mysql_free_result($r);
			if(!isset($this->res)){return array();}else{return $this->res;}
		}else{
			$this->num_rows=mysql_affected_rows();
			return $r;}
				
	}	
	//INSERT
	function insert($t = '', $a = array()){
		unset($this->res);
		$this->num_rows=0;
		if(!is_array($a) || $t=='') self::_error('Formato inválido para INSERT!',6);
		$virgula='';$keys='';$valores='';
		foreach($a as $key=>$value){
			$keys.=$virgula.$key;
			is_string($value) ? $aspa="'" : $aspa='';
			$valores.=$virgula.$aspa.mysql_real_escape_string($value).$aspa;
			$virgula=',';
		}
		$this->query = 'INSERT INTO '.trim($t).' ('.$keys.') VALUES ('.$valores.')';
		$ret = mysql_query('INSERT INTO '.trim($t).' ('.$keys.') VALUES ('.$valores.')');
		if(!$ret) $this->erro = mysql_error() . '<br />Query: ' . $this->query;
		$this->num_rows = mysql_affected_rows();
		return $this->num_rows;
	}
	//UPDATE
	function update($t = '', $a = array(), $w = ''){
		if(!is_array($a) || $t=='' || $w=='') self::_error('Formato inválido para UPDATE!',7);
		$kk='';$yy=array();
		foreach($a as $k=>$v){
			if(is_string($v)){$v="'".mysql_real_escape_string($v)."'";}
			$kk.=$k.'='.$v.' ,';$yy[]=$v;}
		$k=substr($kk,0,-1);
		$this->query = 'UPDATE '.trim($t).' SET '.$k.' WHERE '.trim($w);
		mysql_query('UPDATE '.trim($t).' SET '.$k.' WHERE '.trim($w));

		$this->num_rows = mysql_affected_rows();
		return $this->num_rows;
	}
	
	//Não implementado
	function get_file($sql, $file , $download){return false;}
	
	//retorna a mensagem de erro
	function getErro(){
		if($this->erro == '') return $this->erro = 	mysql_error();
		else return $this->erro;
	}
}
?>