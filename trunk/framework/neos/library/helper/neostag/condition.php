<?php
/**
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 * @subpackage	NeosTags
 */ 
 
 
 if(!function_exists('_neostag_condition')){	
	function _neostag_condition($ret){
		global $_neos_vars,$nomeView;$v=false;echo $ret['conteudo'];
		if(!isset($ret['var'])){return '';}
		if(isset($_neos_vars[0][trim($ret['var'])])){$v=$_neos_vars[0][trim($ret['var'])];}else{$v='';}		
		if(is_string($nomeView)&&$_neos_vars[$nomeView][trim($ret['var'])]!=''){$v=$_neos_vars[$nomeView][trim($ret['var'])];}		
		if($v){return $ret['conteudo'];}else{return '';}
	}
}