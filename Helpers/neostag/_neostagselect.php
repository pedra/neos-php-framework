<?php
if(!function_exists('_neostagselect')){
	function _neostagselect(){
		global $vartemp;
		global $ctrl;
		global $ret;
		global $nomeView;
		
		if(isset($ret['var'])){
			if(isset($ctrl->_neosVars[0][trim($ret['var'])])){$v=$ctrl->_neosVars[0][trim($ret['var'])];}else{$v='';}
			if(is_string($nomeView)&&$ctrl->_neosVars[$nomeView][trim($ret['var'])]!=''){$v=$ctrl->_neosVars[$nomeView][trim($ret['var'])];}			
			if($v!=''){				
			$ul='';
			foreach($ret as $key=>$value){
				if(trim($key)=='style'||trim($key)=='class'||trim($key)=='id'||trim($key)=='align'||trim($key)=='value'||trim($key)=='src'||trim($key)=='title'||trim($key)=='size'||trim($key)=='multiple'||trim($key)=='name' || strpos(trim($key),'on')===0){
					if($ul==''){$ul='<select';}
					if(trim($key)=='multiple'){$ul.=' '.trim($key);}else{$ul.=' '.trim($key).'="'.trim($value).'"';}
					unset($ret[$key]);
				}
			}
			if($ul!=''){$vartemp=$ul.=">\n";}else{$vartemp="<select>\n";}
				foreach($v as $k=>$vl){$vartemp.='<option value="'.$k.'" ';if(is_array($vl)){$vartemp.=' selected="selected" >'.$vl[0]."</option>\n";}else{$vartemp.='>'.$vl."</option>\n";}}
				$vartemp.="</select>\n";				
				}			
		}
	}
}