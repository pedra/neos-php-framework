<?php
/**
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @package		Neos\Helper
 */
if(!function_exists('_form')){
/*
OBJETIVO: 
	Este helper pega as variáveis POST (implementar outras) e retorna um objeto da classe NEOS_form(), cujas propriedades são os campos do formulário enviado.
SINTAXE:
	$variavel_formulario=$this->_form();
	Teste:
	echo $variavel_formulario->campo_do_formulario;
TODO:
	A idéia é ver o formulario como um objeto e criar uma relação para gravar os dados no BD, através de um model-tabela.
	Deve ser previsto um filtro para validar e filtrar os dados ( como a função do PHP : filter_var_array() );
*/	
	function _form()
	{
		if(count($_POST)>0){
			global $cfg;		
			$obj=new neos_form;			
			foreach($_POST as $key=>$val){if($key==$cfg->post_ctrl || $key==$cfg->post_func){continue;}$obj->{$key} = $val;}			
			return $obj;
		}else{
			return false;	
		}		
	}
}