<?php
class NEOS_Status {	
	function __construct(){	
		global $cfg;
		set_error_handler(array('NEOS_Status','erros'));//trigger_error();	
		if(strpos(strtolower($cfg->status),'display')!==false){$d=$this->display();}
		if(strpos(strtolower($cfg->status),'file')!==false){$this->log();}
	}
	//Display status bar...
	function display(){
		global $cfg;
		global $neos_benchmark;
		$a='<style>.neos_status{padding:4px 10px;margin:0;position:fixed;*position:absolute;bottom:0;right:0;background:#890;z-index:9999;}.neos_status p{font-family:Arial,Verdana,Tahoma,Helvetica;font-size:9px;text-align:left;padding:0;margin:0;color:#FFF}</style><div align="right" class="neos_status">';
		foreach($neos_benchmark as $k=>$v){
			$b=round(($v['mem']/1000), 1).' Kb | '.round(($v['peak']/1000), 1).' Kb | '.round((microtime(true) - $v['time'])*1000,1).' ms. | '.$k;
			if($v['name']!=''){$b.=' | '.$v['name'];}
			$a.='<p align="left">'.$b.'</p>';
		}
		echo $a.='</div>';
	}
	//Save status log
	function log(){
		global $cfg;
		global $neos_benchmark;
		$b='';
		foreach($neos_benchmark as $k=>$v){
			$b.="\nST|".date("Y-m-d H:i:s").'|'.round(($v['mem']/1000), 1).'Kb|'.round(($v['peak']/1000), 1).'Kb|'.round((microtime(true) - $v['time'])*1000,1).' ms.|'.$k;
			if($v['name']!=''){$b.='|'.$v['name'];}
			if(isset($v['vars'])){
				if(!is_array($v['vars'])){$v['vars']=array($v['vars']);}
				foreach($v['vars'] as $n=>$val){
					if(is_object($val)||is_array($val)){
						foreach($val as $kk=>$vv){if(!is_object($vv)&&!is_array($vv)){$b.="\nSTV|".$n.'->'.$kk.'='.$vv;}}
					}else{$b.="\nSTV|".$n.'='.$val;}
				}
			}
			if(isset($v['files'])){foreach($v['files'] as $val){$b.="\nSTF|".$val;};}
		}
		if(trim($cfg->logfile)!=''){file_put_contents($cfg->app.$cfg->logfile,"\n".$b,FILE_APPEND);}
	}
	//Error handler...(Because NEOS_ERROR is disabled in this point!)
	static function erros($n,$m,$f,$l){
		if($n>1024){return;}		
		echo '<div align="right" style="padding:4px 10px;margin:0;position:absolute;bottom:0;right:0;background:#967;z-index:9999;color:#FFF">'.' | '.$n.' | '.$m.' | '.$f.' | '.$l.'<div>';exit();		
	}
}
?>