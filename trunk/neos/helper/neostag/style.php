<?php
/**
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 * @subpackage	NeosTags
 */ 
if(!function_exists('_neostag_style')){
	function _neostag_style($ret){
		$m = 'all';
		if(!isset($ret['href'])) return false;
		if(isset($ret['media'])) $m = $ret['media'];
		_addcss($ret['href'],$m);
		return '';
	}
}