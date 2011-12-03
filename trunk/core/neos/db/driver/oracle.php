<?php
/** 
 * Driver para Oracle.
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Db
 * @subpackage	Model
 * @access 		public
 * @return		object Retorna dados de uma operação no banco de dados ORACLE
 * @since		CAN : B4BC
 */
namespace Neos\Db\Driver; 
 
 //Para mais informações sobre as funções desta classe veja os comentários na Interface do Driver.
 
class Oracle 
	extends Abstract_ {
	
	private $con;
	private $parse;
	
	//CONECTANDO
	function connect($al = ''){
		if(!extension_loaded('oci8') || !extension_loaded('oracle')){exit('Não foi possível achar o drive do Banco de Dados!');}	
		$cfg = \_cfg::cfg();
		if($al == ''){$al = $cfg->db->active;}
		$this->con = @oci_pconnect(	$cfg->db->{$al}->user,
									$cfg->db->{$al}->pass,
									$cfg->db->{$al}->host,
									$cfg->db->{$al}->charset);
		if(!$this->con){trigger_error('N&atilde;o foi poss&iacute;vel conectar ao Banco de Dados: '.oci_error());}
	}
	//Simulando PREPARE
	function prepare($sql='', $nome = 'sql'){
		if(isset($this->parse)){echo "erro - prepare";return false;}
		$this->parse=oci_parse($this->con,$sql);		
	}
	//Simulando BIND
	function bind($var, $val, $nome = 'sql', $exec=false){
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
	function execute($nome = 'sql'){
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
	function query($sql = ''){
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
	function insert($t = '', $a = array()){
		unset($this->res);
		$this->num_rows=0;
		if(!is_array($a) || $t == ''){ return false;}
		$virgula='';
		foreach($a as $key=>$value){
			$keys.=$virgula.$key;
			is_string($value) ? $aspa="'" : $aspa='';
			$valores.=$virgula.$aspa.$value.$aspa;
			$virgula=',';
		}
		$this->query('INSERT INTO '.trim($t).' ('.$keys.') VALUES ('.$valores.')');
		return $this->num_rows;
	}
	//UPDATE
	function update($t = '', $a = array(), $w = ''){
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
	function get_file($sql, $file, $force){
		if($sql=='' || count($data)==0){return false;}
		$a="";	
		$this->query($sql);
		if($this->parse){foreach($this->parse as $row){$a.= $row->{$row_name}->load();}}
		if(!$force){return $a;}
		else{
			if($file==''){$file='arquivo.zip';}
			global $_neos_objects;
			$_neos_objects['controller']->_download('',$file,$a);
		}
	}
}