<?php
//return or 'echo' string for Javascript ajax...
//In javascrit: eval(return) / All variables in Array explode in JavaScript.
if(!function_exists('_startPDO')){
	function _startPDO($um='',$dois='',$func='query',$dtbase=''){
		return call_user_func($func,$um,$dois,$dtbase);	
	}
	
	function _query($sql='',$def='',$dtbase=''){
		$ctrl->_db = new $drive($dtbase);
		return $ctrl->_db->query($sql,$def);
	}
	
	function _insert(){}
	
	function _update(){}
	
	function _delete(){}
	
	function _lstdb(){}
	
	function _lsttables(){}
}