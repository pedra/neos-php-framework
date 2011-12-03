<?php
/**
 * NEOS_form - Funções comuns para extends de FORMULARIOS.
 * @package NEOS
 * @author Paulo Rocha (http://neophp.tk)
 * @copyright 2009 - 2010 Paulo R. B. Rocha
 */
class NEOS_form
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