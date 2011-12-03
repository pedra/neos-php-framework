<?php
class NEOS_DB_TABLE extends Neos_class {
	public $table_name;
	public $id=0;
	//Pega um valor de um campo na classe:NEOS_DB_FIELD
	public function __call($name,$args){
		if(!function_exists($name)){
			if(isset($args[0])){if(!is_numeric($args[0])){return false;}else{$this->id=$args[0];}}			
			global $neos_db;
			if(!isset($args[1])){
				$r=sqlite_query($neos_db->conn,'SELECT '.$name.' FROM '.$this->table_name.' LIMIT '.($this->id - 1).',1');
				return sqlite_fetch_single($r);}
			else{
				echo "UPDATE '$this->table_name' SET '$name'='$args[1]' WHERE ID=$this->id";
				if(sqlite_unbuffered_query($neos_db->conn,"UPDATE '$this->table_name' SET '$name'='$args[1]' WHERE ID=$this->id")){return true;}else{return false;}
			}
		}
	}
	public function _insert($d){
		if(!is_array($d)){return false;}
		global $neos_db;$c='';$v='';
		foreach($d as $k=>$l){$c.="'$k',";$v.="'$l',";}
		$c=substr($c,0,-1);$v=substr($v,0,-1);
		$a=@sqlite_query($neos_db->conn,"INSERT INTO '$this->table_name' ($c) VALUES ($v)");
		$this->id=sqlite_last_insert_rowid($neos_db->conn);
		if($a){return $this->id;}else{return false;}	
	}
	public function _list($l=0,$st=30){
		global $neos_db;
		$r=sqlite_unbuffered_query($neos_db->conn,"SELECT * FROM $this->table_name LIMIT $l,$st",SQLITE_ASSOC);
		if($r){$b=array();while($a=sqlite_fetch_object($r)){$b[]=$a;}return $b;}else{return false;}		
	}
	public function _delete($id=''){
		global $neos_db;
		if($id==''){$id=$this->id;}else{$this->id=$id;}
		$r=sqlite_unbuffered_query($neos_db->conn,"DELETE FROM '$this->table_name' WHERE ID=$id");
		if($r){return $this->id;}else{return false;}
	}
	public function _clear(){
		global $neos_db;
		if(sqlite_unbuffered_query($neos_db->conn,'DELETE FROM '.$this->table_name)){return true;}else{return false;}
	}
}
class NEOS_DB extends Neos_class {	
	public $conn;
	public $tables=array();
	public $db;
	
	public function _start($file=''){
		global $cfg;
		if($this->conn=sqlite_popen($cfg->app.'neos_app.db', 0666, $error)){
  			$r=sqlite_query($this->conn,'SELECT name FROM sqlite_master WHERE type="table"');
			if(is_resource($r)){while($y=sqlite_fetch_array($r)){$this->tables[]=$y[0];}}else{return 'noselect';}
			foreach($this->tables as $k=>$a){
				$this->{$a}=new NEOS_DB_TABLE();
				$this->{$a}->table_name=$a;
			}
		}else{return 'nodb:'.sqlite_error_string($error);}		
	}
	public function _error(){return sqlite_error_string(sqlite_last_error($this->conn));}
	public function _destroy($table){
		$r=sqlite_query($this->conn,"DROP TABLE '$table'");
		return $r;
	}
	public function _create($t,$a){
		if(!is_array($a)){return false;}
		
		$t="CREATE TABLE $t (ID integer primary key,";
		foreach($a as $k=>$v){$t.=" $k  $v,";}
		echo $t=substr($t,0,-1).' )';
		$r=sqlite_query($this->conn,$t);
		if($r){return $this->_start();}else{return false;}	
	}
	public function _query($sql){
		if($sql==''){return false;}
		$r=sqlite_query($this->conn,$sql);
		if($r){$b=array();while($a=sqlite_fetch_object($r)){$b[]=$a;}return $b;}else{return false;}			
	}
}