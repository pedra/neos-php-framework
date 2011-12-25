<?php
	/**
	 * Index da aplicação - versão para testes
	 * @copyright	NEOS PHP Framework - http://neosphp.com
	 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
	 * @author		Paulo R. B. Rocha - prbr@ymail.com
	 * @version		CAN : B4BC
	 * @package		Neos\Main
	 * @subpackage	Test
	 */
	 
	//Carregando o framework
	include 'core/main.php';
	
	//Rodando a aplicação -> modo de teste <-
	Main::run('test');	
	
	
?>
	<div style="position:fixed; top:0; left:10px; background:transparent; color:#F00;text-shadow:2px 2px 3px #666; font-weight:100; font-family:'Lucida Console', Monaco, monospace, 'Courier New', Courier; font-size:18px; padding:2 0 0 0; margin:0;">&lt;test mode/&gt;</div>