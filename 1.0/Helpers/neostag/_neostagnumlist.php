<?php
if(!function_exists('_neostagnumlist')){
	function _neostagnumlist(){
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
				if(trim($key)=='style'||trim($key)=='class'||trim($key)=='id'||trim($key)=='align'||trim($key)=='value'||trim($key)=='src'||trim($key)=='title'||trim($key)=='name' || strpos(trim($key),'on')===0){
					if($ul==''){$ul='<ol';}
					$ul.=' '.trim($key).'="'.trim($value).'"';
					unset($ret[$key]);
				}
			}
			if($ul!=''){$vartemp=$ul.=">\n";}else{$vartemp="<ol>\n";}
				foreach($v as $vl=>$x){if(!is_numeric($vl)){$vartemp.='<li><a href="'.URL.$vl.'">'.$x."</a></li>\n";}else{$vartemp.="<li>$x</li>\n";}}
				$vartemp.="</ol>\n";				
				}			
		}
	}
}