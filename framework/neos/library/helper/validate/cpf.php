<?php
/**
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 * @subpackage	Validate
 */ 
 
function validate_cpf($cpf){
	$cpf=preg_replace("/[^0-9]/",'',$cpf);
	$d1=0;$d2=0;
	for($i=0,$x1=10,$x2=11;$i<=9;$i++,$x1--,$x2--){
		if(str_repeat($i,11)==$cpf){return false;}
		$d2+=$cpf[$i]*$x2;
		if($i<=8){$d1+=
		$cpf[$i]
		*$x1;}			
	}
	$c1=(($d1%11) < 2 ) ? 0 : (11-($d1%11));
	$c2=(($d2%11) < 2 ) ? 0 : (11-($d2%11));		
	if($c1!=$cpf[9] || $c2!=$cpf[10]){return false;}
	return true;		
}		
