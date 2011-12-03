<?php
/**
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 * @subpackage	NeosTags
 */ 
  if(!function_exists('_neostag_var')){
	
	function _neostag_var($ret){
		global $_neos_vars,$nomeView;
		$vartemp='';
		if(isset($_neos_vars[0][trim($ret['var'])])){$v=$_neos_vars[0][trim($ret['var'])];}else{$v='';}
		
		if(is_string($nomeView)&&$_neos_vars[$nomeView][trim($ret['var'])]!=''){$v=$_neos_vars[$nomeView][trim($ret['var'])];}
		
		unset($ret['var'],$ret['-inicio-'],$ret['-tamanho-'],$ret['-final-'],$ret['-tipo-'],$ret['conteudo']);
		
		if($v!=''){
			$d='';
			foreach($ret as $key=>$value){
				if($d==''){$d='<div';}
				$d.=' '.trim($key).'="'.trim($value).'"';
				unset($ret[$key]);
			}
			if($d!=''){$vartemp=$d.='>'.$v.'</div>';}else{$vartemp=$v;}
		}
	return $vartemp;
	}
}