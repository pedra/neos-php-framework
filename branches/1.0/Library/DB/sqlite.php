<?php
class Sqlite {	
	private $con;
	public 	$num_rows=0;
	
	function __construct(){
		if(!$this->connect()){trigger_error("Não foi possível conectar ao Banco de Dados: ".$this->error);}
	}
	//CONECTANDO
	function connect(){
		global $cfg;
		$r=sqlite_open($cfg->db->sqlite->database, 0666, $e);
		$this->error=$e;		
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
	function query($sql=''){
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
	function insert($tabela='',$sql=array()){
		unset($this->res);
		$this->num_rows=0;
		if(!is_array($sql) || $tabela==''){trigger_error('Formato inválido para INSERT!');return false;}
		$virgula='';$keys='';$valores='';
		foreach($sql as $key=>$value){
			$keys.=$virgula.$key;
			is_string($value) ? $aspa="'" : $aspa='';
			$valores.=$virgula.$aspa.sqlite_escape_string($value).$aspa;
			$virgula=',';
		}
		$this->query('INSERT INTO '.trim($tabela).' ('.$keys.') VALUES ('.$valores.')');
		return sqlite_last_insert_rowid($this->con);
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
		return sqlite_changes($this->con);
	}	
}
?>