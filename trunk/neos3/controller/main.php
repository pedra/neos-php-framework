<?php

class Main
	extends Base {

	public $nome = 'Paulo R. B. Rocha';
	public $email = 'prbr@ymail.com';
	
	function index(){		
		//enviando dados para a viewEngine
		_view::val('nome', $this->nome );
		_view::val('email', $this->email );
		
		//setando a view usada (arquivo em /view/help.html)
		_view::set('phar');		
	}

	function run(){		
		echo '<pre>'.print_r($_POST, true).'</pre>';//retorna os dados do formulário		
	}



}
