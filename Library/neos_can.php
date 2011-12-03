<?php
class NEOS_CAN extends NEOS_class {
	
	//função para transformar codigo numerico em CAN (de 0 à 46655 - CAN de 3 dígitos)
	// $codigo 	= código numérico a ser transformado em CAN
	// $t		= tamanho do CAN (máximo/default 3 dígitos)
	function geraCan($num=0,$t=3){
		if(!is_numeric($num)){return false;}
		return $this->_geraCan($num,$t);
	}
	
	//função para transformar codigo CAN em decimal (CAN de 3 dígitos)
	// $can 	= código CAN
	function decodCan($can='0'){
		return $this->_geraDec($can);		
	}
	
	//Funções PRIVADAS ---------------------------------------------------------------------
	
	//função para transformar codigo numerico em CAN (de 0 à 46655 - CAN de 3 dígitos)
	private function _geraCan($codigo,$t=3){
		$final='';
		if($codigo > 1295 && $codigo < 46656){
			$div = intval($codigo / 1296);
			$final = $this->_decCan($div);
			$codigo=$codigo-($div*1296);
		}else{if($t>2){$final='0';}}	
		if($codigo > 35 && $codigo < 1296){
			$div = intval($codigo / 36);
			$final .= $this->_decCan($div);
			$codigo=$codigo-($div*36);
		}else{if($t>1){$final.='0';}}
		if($codigo <= 35){$final .= $this->_decCan($codigo);}else{if($t>0){$final.='0';}}
		return $final;
	}
	//função para transformar codigo numerico em CAN	
	private function _decCan($codigo=34){
		if($codigo > 35 || $codigo < 0 ){return false;}
		$codigo = $codigo + 48;
		if($codigo > 57 ) {$codigo = $codigo + 7;}		
		return chr($codigo);	
	}
	
	
	//função para decodificar CAN em numero
	private function _geraDec($codigo){
		if(strlen($codigo)>3){return false;}
		if(strlen($codigo)==1){$codigo='00'.$codigo;}
		if(strlen($codigo)==2){$codigo='0'.$codigo;}
		$selector = array(1296,36,1);
		$codigo=str_split($codigo);
		$valor = 0;
		foreach($codigo as $key=>$can){			
			$valor += ($this->_canDec($can) * $selector[$key]);	
		}
		return $valor;
	}	
	//função para decodificar CAN em numero
	private function _canDec($codigo){
		$num=ord(strtoupper($codigo));
		if($num > 64 && $num<91){$num=$num-7;}
		$num=$num-48;
		if($num < 0 || $num > 35){return false;}
		return $num;
	}
}