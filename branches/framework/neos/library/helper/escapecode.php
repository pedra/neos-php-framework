<?php
/**
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 */
if(!function_exists('_escapecode')){
	function _escapecode($string,$len='',$ini=0){		
		$order   	= array("\r\n", "\n", "\r");
		$replace 	= '';
		$order1		= array("\t");
		$replace1 	= '<span style=\"padding:0 20px\"/>';
		$string		= trim($string);
		if($string!=''){
			$string=addslashes(highlight_string($string,true));
			$string=str_replace($order, $replace,$string);			
			}else{$string='';}		
		if($len==''){$len=strlen($string);};
		return trim(substr($string,$ini,$len));
	}
}