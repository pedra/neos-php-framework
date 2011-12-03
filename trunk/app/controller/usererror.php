<?php
/**
 * Controller Error - helper para erros no site
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Controller
 */
	
class Controller_Usererror extends NEOS
{
	
	function index()
	{	
		//_view::set('error404', '', 'error404');
		_cfg()->template = '';
		//header("HTTP/1.0 404 Not Found");
		echo 'SubUri' . _pt( Main::this()->varSubUri ) . '<br>uri:' . _cfg::get()->uri;
		_pt($_SERVER);		
	}
}