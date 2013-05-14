<?php
/**
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 * @subpackage	NeosTags
 */ 
  if(!function_exists('_neostag_list')){function _neostag_list($ret){global $vartemp,$nomeView;if(isset($ret['var'])){if(isset($_neos_vars[0][trim($ret['var'])])){$v=$_neos_vars[0][trim($ret['var'])];}else{$v='';}if(is_string($nomeView)&&$_neos_vars[$nomeView][trim($ret['var'])]!=''){$v=$_neos_vars[$nomeView][trim($ret['var'])];}unset($ret['var'],$ret['-inicio-'],$ret['-tamanho-'],$ret['-final-'],$ret['-tipo-'],$ret['conteudo']);if($v!='' && is_array($v)){$ul='';foreach($ret as $key=>$value){if($ul==''){$ul='<ul';}$ul.=' '.trim($key).'="'.trim($value).'"';unset($ret[$key]);}if($ul!=''){$vartemp=$ul.=">\n";}else{$vartemp="<ul>\n";}foreach($v as $vl=>$x){if(!is_numeric($vl)){$vartemp.='<li><a href="'._app('URL').$vl.'">'.$x."</a></li>\n";}else{$vartemp.="<li>$x</li>\n";}}$vartemp.="</ul>\n";}}}}