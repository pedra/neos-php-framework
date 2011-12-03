<?php
if(!function_exists('_neostagvar')){
	function _neostagvar(){
		global $vartemp;
		global $ctrl;
		global $nomeView;
		global $ret;
		if(isset($ctrl->_neosVars[0][trim($ret['var'])])){$v=$ctrl->_neosVars[0][trim($ret['var'])];}else{$v='';}
		if(is_string($nomeView)&&$ctrl->_neosVars[$nomeView][trim($ret['var'])]!=''){$v=$ctrl->_neosVars[$nomeView][trim($ret['var'])];}
		
		if($v!=''){				
			$d='';
			foreach($ret as $key=>$value){
				if(trim($key)=='style'||trim($key)=='class'||trim($key)=='id'||trim($key)=='align'||trim($key)=='value'||trim($key)=='src'||trim($key)=='title' || strpos(trim($key),'on')===0){
					if($d==''){$d='<div';}
					$d.=' '.trim($key).'="'.trim($value).'"';
					unset($ret[$key]);
				}
			}
			if($d!=''){$vartemp=$d.='>'.$v.'</div>';}else{$vartemp=$v;}
		}
	}
}