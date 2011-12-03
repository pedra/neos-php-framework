<?php
class Mysql {
	
	private $con;
	public 	$num_rows=0;
	
	function __construct(){
		if(!$this->connect()){trigger_error("N&atilde;o foi poss&iacute;vel conectar ao Banco de Dados: ".mysql_error());}
	}
	//CONECTANDO
	function connect(){
		global $cfg;
		$r=mysql_pconnect($cfg->db->mysql->host,$cfg->db->mysql->user,$cfg->db->mysql->pass);
		if(isset($cfg->db->mysql->char)){if(!mysql_set_charset($cfg->db->mysql->char)){return false;}}
		if(isset($cfg->db->mysql->database)){if(!mysql_select_db($cfg->db->mysql->database)){return false;}}		
		$this->con=$r;
		return $r;
	}
	//Simulando PREPARE
	function prepare($sql="",$nome="sql"){
		if(isset($this->{$nome})){trigger_error('Error in prepare');return false;}
		$this->{$nome}=$sql;		
	}
	//Simulando BIND
	function bind($nome,$var,$val,$exec=false){
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
	function execute($nome){
		unset($this->res);
		$this->num_rows=0;
		if(!isset($this->{$nome})){trigger_error('Executar oque?!');return false;}
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
	function query($sql=""){
		if($sql==''){trigger_error('Query sem argumentos!');return false;}
		unset($this->res);
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
	function insert($tabela='',$sql=array()){
		unset($this->res);
		$this->num_rows=0;
		if(!is_array($sql) || $tabela==''){trigger_error('Formato inválido para INSERT!');return false;}
		$virgula='';$keys='';$valores='';
		foreach($sql as $key=>$value){
			$keys.=$virgula.$key;
			is_string($value) ? $aspa="'" : $aspa='';
			$valores.=$virgula.$aspa.mysql_real_escape_string($value).$aspa;
			$virgula=',';
		}
		$this->query('INSERT INTO '.trim($tabela).' ('.$keys.') VALUES ('.$valores.')');
		return mysql_affected_rows();
	}
	//UPDATE
	function update($t='',$a=array(),$w=''){
		if(!is_array($a) || $t=='' || $w==''){trigger_error('Formato inválido para UPDATE!');return false;}
		$kk='';$yy=array();
		foreach($a as $k=>$v){
			if(is_string($v)){$v="'".$v."'";}
			$kk.=$k.'='.$v.' ,';$yy[]=$v;}
		$k=substr($kk,0,-1);
		$this->query('UPDATE '.trim($t).' SET '.$k.' WHERE '.trim($w));
		return mysql_affected_rows();
	}
}
?>