<?php
if(!function_exists('_escape')){
	function _escape($string,$len='',$ini=0){ 
		if(is_array($string)){
			foreach($string as $key=>$val){
				$val=addslashes(strip_tags(trim($val)));
				$l=$len;
				if($l==''){$l=strlen($val);};
				$dt[$key]=trim(substr($val,$ini,$l));
			}
			return $dt;
		}else{			
			$string=addslashes(strip_tags(trim($string)));
			if($len==''){$len=strlen($string);};
			return  trim(substr($string,$ini,$len));
		}
	}
}