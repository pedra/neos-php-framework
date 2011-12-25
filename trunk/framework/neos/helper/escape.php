<?php
/**
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 */
if(!function_exists('_escape')){
	function _escape($string, $strip = true, $len = '', $ini = 0){
		if(is_array($string)){
			foreach($string as $key=>$val){
				if($strip) $val = strip_tags(trim($val));
				$val = addslashes($val);
				$l = $len;
				if($l == '') $l = strlen($val);
				$dt[$key] = trim(substr($val, $ini, $l));
			}
			return $dt;
		}else{
			if($strip) $val = strip_tags(trim($string));		
			$string = addslashes($val);
			if($len == '') $len = strlen($string);
			return  trim(substr($string, $ini, $len));
		}
	}
}