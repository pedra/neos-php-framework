<?php
/**
 * Funções comuns para extends de FORMULARIOS
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Neos\Library
 */
 
namespace Neos\Library;
 
class Form
{
    function __call($f,$a){
		global $cfg;
		$cfg->error['cod']=5;//TODO: criar uma ajuda dinâmica para este tipo ( form ).
		if(isset($this->form)){$cfg->error['class']=$this->form;}else{$cfg->error['class']=get_class($this);}
		$cfg->error['function']=$f;
		$cfg->error['description']='Function "'.$cfg->error['class'].'->'.$cfg->error['function'].'" not exists!';
		trigger_error($cfg->error['description']);
	}
	public static function __callStatic ($f,$a){
		global $cfg;
		$cfg->error['cod']=5;
		$cfg->error['function']=$f;
		$cfg->error['description']='Static function "'.$cfg->error['function'].'" not exists!';
		trigger_error($cfg->error['description']);
	}
}