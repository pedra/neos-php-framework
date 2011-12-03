<?php
class Oracle {
	
	private $con;
	private $parse;
	
	function __construct(){
		if(!extension_loaded('oci8') || !extension_loaded('oracle')){exit('Não foi possível conectar ao Banco de Dados!');}
		if(!$this->con=$this->connect()){trigger_error('N&atilde;o foi poss&iacute;vel conectar ao Banco de Dados: '.oci_error());}	
	}
	//CONECTANDO
	function connect(){	
		global $cfg;
		return @oci_pconnect($cfg->db->oci->user,
							$cfg->db->oci->pass,
							$cfg->db->oci->host,
							$cfg->db->oci->char);
	}
	//Simulando PREPARE
	function prepare($sql=""){
		if(isset($this->parse)){echo "erro - prepare";return false;}
		$this->parse=oci_parse($this->con,$sql);		
	}
	//Simulando BIND
	function bind($var,$val,$exec=false){
		if(!isset($this->parse)){echo "erro - bind";return false;}		
		if(!is_numeric($val)){$val="'".$val."'";} //acrescenta aspas automaticamente		
		if(!is_array($var)){oci_bind_by_name($this->parse, $var, $val);}
		else{
			$ci=0;
			foreach($var as $key=>$val){
				${"val$ci"}=$val;
				oci_bind_by_name($this->parse, $key, ${"val$ci"}); 
				$ci++;				
				}		
			}
    	if($exec==true){return $this->execute();}
		}
	//Simulando EXECUTE - PREPARE+BIND
	function execute(){
		unset($this->res);
		if(!isset($this->parse)){return false;}
		$tipo=oci_statement_type($this->parse);
		$r=oci_execute($this->parse);
		if($tipo == "SELECT"){
			while($x=oci_fetch_object($this->parse)){$this->res[]=$x;}
			$this->num_rows=oci_num_rows($this->parse);
			if(!isset($this->res)){return array();}else{return $this->res;}
		}else{
			$this->num_rows=oci_num_rows($this->parse);
			return $r;}
	}
	//QUERY	
	function query($sql=""){
		unset($this->res);
		$y=oci_parse($this->con,$sql);
		$tipo=oci_statement_type($y);
		$r=oci_execute($y);
		$this->parse=$y;
		if($tipo == "SELECT"){	
			while($x=oci_fetch_object($y)){
				$this->res[]=$x;		
				}
				$this->num_rows=oci_num_rows($y);
			if(!isset($this->res)){return array();}else{return $this->res;}
		}else{
			$this->num_rows=oci_num_rows($y);
			return $r;}
	}
	//INSERT
	function insert($tabela='',$sql=array()){
		unset($this->res);
		$this->num_rows=0;
		if(!is_array($sql) || $tabela==''){ return false;}
		$virgula='';
		foreach($sql as $key=>$value){
			$keys.=$virgula.$key;
			is_string($value) ? $aspa="'" : $aspa='';
			$valores.=$virgula.$aspa.$value.$aspa;
			$virgula=',';
		}
		$this->query('INSERT INTO '.trim($tabela).' ('.$keys.') VALUES ('.$valores.')');
		return $this->num_rows;
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
		return $this->num_rows;
	}	
	/*
	GET_FILE
	Função que recupera um arquivo armazenado como BLOB (ou CLOB) no Oracle
	
	Entrada:
		$sql 	= Um SELECT cujo resultado seja o campo onde o arquivo esteja armazenado;
		$file	= nome e extensão que o arquivo receberá para download;
		$force	= true/false -> força o download do arquivo / retorna o arquivo.
		
	Saída:
		O próprio arquivo ou será forçado o download do arquivo automaticamente (force=true).
		
	Exemplo:
		_db('SELECT M_ARQUIVO FROM TABELA WHERE ID=1','script_php.pdf',true,'get_file');
		
		Resultado:
			Será forçado o download do arquivo 'script_php.pdf' contendo o resultado do 'SELECT ...' ( campo M_ARQUIVO da TABELA).
	*/
	function get_file($sql='',$file,$force){
		if($sql=='' || count($data)==0){return false;}
		$a="";	
		$this->query($sql);
		if($this->parse){foreach($this->parse as $row){$a.= $row->{$row_name}->load();}}
		if(!$force){return $a;}
		else{
			if($file==''){$file='arquivo.zip';}
			global $ctrl;
			$ctrl->_download('',$file,$a);
		}
	}
}
?>