<?php
/**
 * Module Login/Out - gerenciamento de conexão de usuários / administradores
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Module
 */
class Module_Login_Module
    extends  \Neos\Base {

	function get($p){	
		if(!_user()->login){
			return '<div class="fastlogin">FastLogin : <input type="password" id="fastlogin" value=""/><input type="submit" id="bt_login" value="login"/></div>';}
		else{return '<div class="fastlogin">Logado como : <b>'._user()->get(_user()->col_name).'</b> <input type="submit" id="logout" value="sair"/></div>';}
	}
}