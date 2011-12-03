<?php
//return or 'echo' string for Javascript ajax...
//In javascrit: eval(return) / All variables in Array explode in JavaScript.
if(!function_exists('_JsSend')){
	function _JsSend($array='',$ret=false){		
		if(!is_array($array)){return false;}
		$echo = '';
		foreach($array as $key=>$val){
			if(!is_array($val)){if(!is_numeric($val)){$echo.=$key.'="'.$val.'";';}else{$echo.=$key.'='.$val.';';}}
			else{foreach($val as $akey=>$aval){if(!is_numeric($val)){$echo.=$key.'["'.$akey.'"]="'.$aval.'";';}else{$echo.=$key.'["'.$akey.'"]='.$aval.';';}}}
		}
		if($ret){return $echo;}else{echo $echo; return true;}
	}
}