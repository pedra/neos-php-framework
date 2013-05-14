<?php

class Outro
	extends Base {

	public $nome = 'controller: Outro.';
	public $email = 'método: ';
	
	function index(){		
		//enviando dados para a viewEngine
		_view::val('nome', $this->nome );
		_view::val('email', $this->email.'index' );
		
		//setando a view usada (arquivo em /view/help.html)
		_view::set('phar');		
	}

	function start($value = ''){
		if($value != '') $value .= ' / ';
		$this->email .= 'start / '.$value;
		return $this->index();	
	}

	function run(){		
		echo '<pre>'.print_r($_POST, true).'</pre>';//retorna os dados do formulário
	}



}
