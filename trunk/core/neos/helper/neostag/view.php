<?php
/**
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 * @subpackage	NeosTags
 */ 
  if(!function_exists('_neostag_view')){function _neostag_view($ret){if(isset($ret['name'])){if(file_exists(APP_VIEW .$ret['name'].'.html')){return file_get_contents(APP_VIEW .$ret['name'].'.html');}}}}