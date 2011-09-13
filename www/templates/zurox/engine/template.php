<?php
/** 
	* Template para manipulação de documentos HTML.
	* @copyright	NEOS PHP Framework - http://neosphp.com
	* @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
	* @author		Paulo R. B. Rocha - prbr@ymail.com 
	* @version		CAN : B4BC
	* @package		Neos\Doc
*/
namespace Neos\Doc;

if(!defined('URL')){exit;};

class Template extends Layout {

	function __construct() {
		parent::__construct();
		
		$seg = explode('/' , URL_SEG);
		$seg = reset($seg);
		
		//Layout para o manual
		if(strpos($seg, 'manual') !== false ) $this->layout = 'manual';
		
		//Layout para o videos
		if(strpos($seg, 'screencast') !== false ) $this->layout = 'manual';
		
		//Layout para o download
		if(strpos($seg, 'download') !== false ) $this->layout = 'download';	
		
		//Layout para users
		if(strpos($seg, 'id') !== false ) $this->layout = 'download';
		//Layout para users
		if(strpos($seg, 'user') !== false ) $this->layout = 'download';
			
	}
}

