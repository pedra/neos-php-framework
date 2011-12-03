<?php
/** 
 * Configuração Geral 
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Config
 */

$cfg->mask['manual']	= array('inicial','manual');
$cfg->mask['id']		= array('user','id');

$cfg->status				= '';    //mostra a barra de status - opções: 'displayfile'
$cfg->error['action']		= 'routefile'; //ação em caso de erro - opções: 'displayfileroutemail'