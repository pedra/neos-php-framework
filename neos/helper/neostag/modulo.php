<?php
/**
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 * @subpackage	NeosTags
 */ 
  if(!function_exists('_neostag_modulo')){function _neostag_modulo($r){if(isset($r['name'])){$n=$r['name'];unset($r['name']);}else{$n='';}return _modulo($n,$r,true);}}