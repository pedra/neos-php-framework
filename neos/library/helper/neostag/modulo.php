<?php
/**
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 * @subpackage	NeosTags
 */ 
  if(!function_exists('_neostag_modulo')){function _neostag_modulo($r){if(isset($r['name'])){$n=$r['name'];unset($r['name']);}else{$n='';}return _modulo($n,$r,true);}}