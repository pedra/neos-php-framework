<?php
if(!function_exists('_neostagview')){
	function _neostagview(){
		global $vartemp;
		global $cfg;
		global $ret;
		if(isset($ret['name'])){if(file_exists($cfg->view.$ret['name'].'.html')){$vartemp=file_get_contents($cfg->view.$ret['name'].'.html');}}
	}
}