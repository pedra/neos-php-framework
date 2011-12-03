<?php
/**
 * Return or 'echo' string for Javascript ajax...
 * In javascrit: eval(return) / All variables in Array explode in JavaScript.
 *
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 */
if(!function_exists('_startPDO')){
	function _startPDO($um='',$dois='',$func='query',$dtbase=''){
		return call_user_func($func,$um,$dois,$dtbase);	
	}
	
	function _query($sql='',$def='',$dtbase=''){
		$_neos_objects['controller']->_db = new $drive($dtbase);
		return $_neos_objects['controller']->_db->query($sql,$def);
	}
	
	function _insert(){}
	
	function _update(){}
	
	function _delete(){}
	
	function _lstdb(){}
	
	function _lsttables(){}
}