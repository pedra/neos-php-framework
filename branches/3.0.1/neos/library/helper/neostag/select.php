<?php
/**
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 * @subpackage	NeosTags
 */ 
  if(!function_exists('_neostag_select')){function _neostag_select($ret){global $vartemp,$_neos_vars,$nomeView;if(isset($ret['var'])){if(isset($_neos_vars[0][trim($ret['var'])])){$v=$_neos_vars[0][trim($ret['var'])];}else{$v='';}if(is_string($nomeView)&&$_neos_vars[$nomeView][trim($ret['var'])]!=''){$v=$_neos_vars[$nomeView][trim($ret['var'])];}unset($ret['var'],$ret['-inicio-'],$ret['-tamanho-'],$ret['-final-'],$ret['-tipo-'],$ret['conteudo']);if($v!=''){$ul='';foreach($ret as $key=>$value){if($ul==''){$ul='<select';}if(trim($key)=='multiple'){$ul.=' '.trim($key);}else{$ul.=' '.trim($key).'="'.trim($value).'"';}unset($ret[$key]);}if($ul!=''){$vartemp=$ul.=">\n";}else{$vartemp="<select>\n";}foreach($v as $k=>$vl){$vartemp.='<option value="'.$k.'" ';if(is_array($vl)){$vartemp.=' selected="selected" >'.$vl[0]."</option>\n";}else{$vartemp.='>'.$vl."</option>\n";}}$vartemp.="</select>\n";}}}}