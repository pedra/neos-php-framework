<?php
/**
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 * @subpackage	NeosTags
 */ 
function neostag_view($ret){
	if(isset($ret['name'])){
		if(file_exists(APP_VIEW . 'html'. DS . $ret['name'].'.html'))
			return file_get_contents(APP_VIEW . 'html'. DS .$ret['name'].'.html');
	}
}