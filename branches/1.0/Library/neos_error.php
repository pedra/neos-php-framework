<?php
/**
 * NFerros - Tratamento de erros do NEOS
 * @package S_erros
 * @author Paulo Rocha (http://neophp.tk)
 * @copyright 2009 - 2010 Paulo R. B. Rocha
 */
class NEOS_Error extends Exception
{
    function __construct($num=0,$msg='',$file='',$line='',$vars=''){
		@error_reporting(0);
		global $cfg;
		
		$this->_num=$num;
		$this->_msg=$msg;
		$this->_file=$file;
		$this->_line=$line;	
		$this->_vars=$vars;	
		
		$this->errorType();
		$this->_data=date('Y-m-d');
		$this->_hora=date('H:i:s');
		$this->_ip=gethostbyname($_SERVER['REMOTE_ADDR']);
						
		if(strpos(strtolower($cfg->error['action']),'display')!==false && strpos(strtolower($cfg->error['action']),'route')===false){$this->display();}
		if(($cfg->logfile!='') && (strpos(strtolower($cfg->error['action']),'file')!==false)){$this->log();}
		if(strpos(strtolower($cfg->error['action']),'mail')!==false){$this->mail();}
		//JUMP para o controller de tratamento de erros de usuarios
		if(strpos(strtolower($cfg->error['action']),'route')!==false){if((strtolower($cfg->default->ctrl)!=strtolower($cfg->error_route)) && trim($cfg->error_route)!=''){_goto(strtolower($cfg->error_route));}else{exit();}
		}
		return true;		
	}
	function errorType(){
		global $cfg;
		if(!isset($cfg->error['cod'])){$cfg->error['cod']=0;}
		switch($cfg->error['cod']){
			case 1: $this->_view='ctrls';break;
			case 2: $this->_view='views';break;
			case 3: $this->_view='models';break;
			case 4: $this->_view='helpers';break;
			case 5: $this->_view='classes';break;
			case 6: $this->_view='mods';break;
			default: $this->_view='erro';	
		}
		if(!isset($cfg->error['class'])){$cfg->error['class']='anyclass';}
		if(!isset($cfg->error['function'])){$cfg->error['function']='anyFunction';}
		if(!isset($cfg->error['description'])){$cfg->error['description']='...';}
		switch($this->_num){
		case 1: $cfg->error['type']='FATAL ERROR';break;	
		case 2: $cfg->error['type']='WARNING';break;
		case 4: $cfg->error['type']='PARSE';break;
		case 8: $cfg->error['type']='NOTICE';break;
		case 16: $cfg->error['type']='CORE ERROR';break;
		case 32: $cfg->error['type']='CORE WARNING';break;
		case 64: $cfg->error['type']='COMPILE ERROR';break;	
		case 128: $cfg->error['type']='COMPILE WARNING';break;
		case 256: $cfg->error['type']='USER ERROR';break;
		case 512: $cfg->error['type']='USER WARNING';break;	
		case 1024: $cfg->error['type']='USER NOTICE';break;	
		case 2048: $cfg->error['type']='STRICT';break;
		case 4096: $cfg->error['type']='RECOVERABLE_ERROR';break;
		case 8192: $cfg->error['type']='DEPRECATED';break;
		case 16384: $cfg->error['type']='USER DEPRECATED';break;
		case E_ALL: $cfg->error['type']='INDEFINIDO (E_ALL)';break;
		}
	} 
	
	function display(){
		global $cfg;
				
		$msg='<p><b>Mensagem:</b> '.$this->_msg.'</p><p><b>Tipo:</b> '.$cfg->error['type'].' (cod.: '.$this->_num.')</p><p><b>Arquivo:</b> '.$this->_file.'</p><p><b>Linha:</b> '.$this->_line.'</p>';

		$x=$this->getTrace();
		
		array_shift($x);
		$x=array_reverse($x);
		$content='';
		$count=count($x)-1;	
		foreach($x as $k=>$tc){
			if($count==$k){$content.='<tr class="final">';}else{$content.='<tr>';}
			$content.= '<td align="center">'.$k.'</td><td>';
			if(isset($tc['class'])){$content.= $tc['class'];}else{$content.='&nbsp;';}
			$content.= '</td><td align="center">';
			if(isset($tc['type'])){$content.= $tc['type'];}else{$content.='&nbsp;';}
			$content.= '</td><td>';
			if(isset($tc['function'])){$content.= $tc['function'];}else{$content.='&nbsp;';}
			$content.= '</td><td>';
			if(isset($tc['file'])){$content.= $tc['file'];}else{$content.='&nbsp;';}
			$content.= '</td><td align="center">';
			if(isset($tc['line'])){$content.= $tc['line'];}else{$content.='&nbsp;';}
			$content.= '</td></tr>
			';			
		}
		
		if(!defined('NEOS_EXCEPT')){
			include $cfg->core.'Views/head.php';
			include $cfg->core.'Views/'.$this->_view.'.php';
			include $cfg->core.'Views/footer.php';
			define('NEOS_EXCEPT',1);
			}
		else{
		echo '<p style=\'text-align:right; padding:1px 20px; margin:0; background:#F00; position:fixed; bottom:0; right:20px; color:#FFF; font-size:9px; font-family:Verdana,Tahoma;\'>ERRO='.'('.$this->_num.') | '.$this->_msg.' | '.$this->_file.' - ('.$this->_line.')('.$this->_ip.')</p>';
			}
	}
		
	function log(){
		global $cfg;
		$log1="\nERR|".$this->_data.' '.$this->_hora.'|'.$this->_view.'|'.$this->_num.'|'.strtr($this->_msg,"|\n\r",'!  ').'|'.$this->_file.'|'.$this->_line.'|'.$this->_ip; 
		if($cfg->logfile!=''){file_put_contents($cfg->app.$cfg->logfile,$log1,FILE_APPEND);}
	}
	//Envia email - - - >
	function mail(){
		global $cfg;
		$elog='
		<html><head><title>Relatório de Erro</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style>
		* {font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#039}
		body {background:#345; margin:0 auto; width:600px; text-align:center; padding:20px;}
		div {background:#FFF; padding:20px; border:1px solid #EEE; margin:0 auto; width:600px; text-align:left}
		table { border:1px solid #CCC}
		table tr th { background:#039; color:#FFF; text-align:left; padding:2px 0 2px 5px}
		table tr td { border:1px solid #DDD; padding:2px 0 2px 5px; cursor:pointer;}
		table tr td.subtitulo {color:#FFF; font-weight:bold; background:#ABC}
		table tr:hover { background-color:#FFC}
		h1,h2 { font-size:18px;color:#036;}
		h2 { font-size:15px;color:#666}
		p {color:#E04;font-size:13px;}
		pre { color:#369}</style></head><body>
		<div>
		<h1>Relatório de Ocorrência de Erro</h1>
		<h2>Log da Ocorrência</h2><p>';
		$elog.="\nERR|".$this->_data.' '.$this->_hora.'|'.$this->_view.'|'.$this->_num.'|'.strtr($this->_msg,"|\n\r",'!  ').'|'.$this->_file.'|'.$this->_line.'|'.$this->_ip;
		$log1.='</p>
		<h2>Application Scope</h2>
		<table width="600" border="0" cellspacing="3" cellpadding="3">
		<tr><th width="100">Name</th><th>Value</th></tr>
		<tr><td colspan="2" class="subtitulo">CONFIG</td></tr>';			
		foreach($cfg as $k=>$v){if($k=='db'){continue;};$elog.='<tr><td>'.$k.'</td><td>'.print_r($v,true).'</td></tr>';}
		$elog.='<tr><td colspan="2" class="subtitulo">POST</td></tr>';			
		foreach($_POST as $k=>$v){$elog.='<tr><td>'.$k.'</td><td>'.print_r($v,true).'</td></tr>';}
		$elog.='<tr><td colspan="2" class="subtitulo">GET</td></tr>';			
		foreach($_GET as $k=>$v){$elog.='<tr><td>'.$k.'</td><td>'.print_r($v,true).'</td></tr>';}
		$elog.='<tr><td colspan="2" class="subtitulo">SERVER</td></tr>';			
		foreach($_SERVER as $k=>$v){$elog.='<tr><td>'.$k.'</td><td>'.print_r($v,true).'</td></tr>';}
		$elog.='</table></div></body></html>';
		//Configurações do EMAIL
		$mail=new mail();
		$mail->Subject = 'ERRO - Minha Aplicação';
		$mail->From ='contato@paulorocha.net76.net';
		$mail->FromName='Site NEOS';
		$mail->Host = 'mx.000webhost.com';
		$mail->Mailer = 'mail';
		$mail->ContentType='text/html';
		$mail->Body= $elog;
		$mail->AddAddress('prbr@ymail.com','Paulo R. B. Rocha');
		$mail->AddAddress('prb_rocha@yahoo.com.br','Outro Desenvolvedor/Gerente de Projeto');
		if(!$mail->Send() || (strpos(strtolower($cfg->error['action']),'display')!==false && isset($_SESSION['admin']))){echo '<div class="msg"><b>Atenção:</b> Não foi possível enviar este report por e-mail!</div>';}
	}
}