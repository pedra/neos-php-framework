<?php
/*
 * NFException - Tratamento de exceções do NEOS
 * @package NFException
 * @author Paulo Rocha (http://neophp.tk)
 * @copyright 2009 - 2010 Paulo R. B. Rocha
 */
class NEOS_Exception extends Exception
{
    function __construct($msg=''){
		@error_reporting(1);
		global $cfg;		

		$msg=explode('#',$msg);			
		$this->_msg=trim(str_ireplace('Stack trace:','',$msg[0]));
		$this->_data=date('Y-m-d');
		$this->_hora=date('H:i:s');
		$this->_ip=gethostbyname($_SERVER['REMOTE_ADDR']);
		
		if(strpos(strtolower($cfg->error['action']),'display')!==false && strpos(strtolower($cfg->error['action']),'route')===false){$this->display();}
		if($cfg->logfile!='' && strpos(strtolower($cfg->error['action']),'file')!==false){$this->log();}
		if(strpos(strtolower($cfg->error['action']),'mail')!==false){$this->mail();}
		//JUMP para o controller de tratamento de erros de usuarios
		if(strpos(strtolower($cfg->error['action']),'route')!==false){if((strtolower($cfg->default->ctrl)!=strtolower($cfg->error_route)) && trim($cfg->error_route)!=''){_goto(strtolower($cfg->error_route));}else{exit();}
		}
		return true;
	}	
	function display(){
		global $cfg;			
		$msg='<p><b>Mensagem:</b> '.$this->_msg.'</p>';			
				
		$x=$this->getTrace();
		$x=$x[0]['args'][0]->getTrace();
		$x=array_reverse($x);
		$count=count($x)-1;
		$content='';	
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
			include $cfg->core.'Views/exception.php';
			include $cfg->core.'Views/footer.php';
			define('NEOS_EXCEPT',1);
		}else{
			echo '<p style=\'text-align:right; padding:1px 20px; margin:0; background:#F00; position:fixed; bottom:0; right:20px; color:#FFF; font-size:9px; font-family:Verdana,Tahoma;\'>EXCEPTION='.$this->_msg.' - ('.$this->_ip.')</p>';
		}
	}
	//Gravando no arquivo de log = se estiver configurado
	function log(){
		global $cfg;
		$log1="\nEXP|".$this->_data.' '.$this->_hora.'|'.strtr($this->_msg,"|\n\r",'!  ').'|'.$this->_ip;
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
		$elog="\nEXP|".$this->_data.' '.$this->_hora.'|'.strtr($this->_msg,"|\n\r",'!  ').'|'.$this->_ip;
		$elog.='</p>
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