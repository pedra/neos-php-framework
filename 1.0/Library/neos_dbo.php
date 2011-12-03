<?php
class NEOS_DBO extends NEOS_class {
	
	private $hdl;
	private $stm;
	private $ret	=	PDO::FETCH_BOTH;
	const OBJ 		=	PDO::FETCH_OBJ ;
	const ARR		=	PDO::FETCH_BOTH;
	const ARR_ASSOC	=	PDO::FETCH_ASSOC;
	const ARR_NUM	=	PDO::FETCH_NUM;
	public 	$num_rows=0;
	
	
	function __construct($name=''){
		if(!$this->connect($name)){
			trigger_error('A conexão ao Banco de Dados ('.$name.') não pode ser feita!');
			return false;
		}else{return $this->hdl;}
	}
	function connect($name){
		global $cfg;
		$u='';$p='';
		if($name==''){$name=$cfg->default->db;}
		if(isset($cfg->db->{$name}->dsn)){
			$d=$cfg->db->{$name}->dsn;
			if(isset($cfg->db->{$name}->user)){$u=$cfg->db->{$name}->user;}
			if(isset($cfg->db->{$name}->pass)){$p=$cfg->db->{$name}->pass;}
		}else{ trigger_error('DSN indefinido para o Banco de Dados!'); return false;}
		$this->hdl=new PDO($d,$u,$p,array(PDO::ATTR_PERSISTENT => true));		
		if(is_object($this->hdl)){return true;}else{return false;}
	}
	//query simples
	function query($ql=''){
		if($ql==''){return array();}
		if($this->stm=$this->hdl->query($ql)){return $this;}else{return false;}
	}	
	//insert
	function insert($t='',$a=array()){
		if(!is_array($a) || $t==''){ return array();}
		$kk='';$y='';$yy=array();
		foreach($a as $k=>$v){$kk.=$k.',';$y.=' ? ,';$yy[]=$v;}
		$k=substr($kk,0,-1);$y=substr($y,0,-1);
		$t='INSERT INTO '.trim($t).' ('.$k.') VALUES ('.$y.')';
		$this->stm=$this->hdl->prepare($t);
		if($this->stm->execute($yy)){return $this->stm->rowCount();}else{return false;}
	}
	//update
	function update($t='',$a=array(),$w=''){
		if(!is_array($a) || $t=='' || $w==''){return array();}
		$kk='';$yy=array();
		foreach($a as $k=>$v){$kk.=$k.'=? ,';$yy[]=$v;}
		$k=substr($kk,0,-1);
		$t='UPDATE '.trim($t).' SET '.$k.' WHERE '.$w;
		$this->stm=$this->hdl->prepare($t);
		if($this->stm->execute($yy)){return $this->stm->rowCount();}else{return false;}
	}	
	//retorna tudo...
	function all($ret=NEOS_DBO::OBJ){
		if(!is_object($this->stm)){return array();}		
		if(trim($ret)==''){return false;}
		return $this->stm->fetchAll($ret);
	}
	//retorna a proxima linha do resultado
	function row($ret=NEOS_DBO::OBJ){
		if(!is_object($this->stm)){return array();}		
		if(trim($ret)==''){return false;}
		return $this->stm->fetch($ret);
	}
}