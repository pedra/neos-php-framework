<?php
	/**
	 * Index da aplicação
	 * @copyright	NEOS PHP Framework - http://neosphp.com
	 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
	 * @author		Paulo R. B. Rocha - prbr@ymail.com
	 * @version		CAN : B4BC
	 * @package		Neos\Main
	 */


	//Carregando o framework
	include 'framework/main.php';
	
	//Rodando a aplicação
	Main::run();
	
	
/*	
	//Outra forma de rodar o NEOS -------------------------------------------------
	
	Main::mount() 					//monta a aplicação;
		->control()					//executa o controller "do cliente";
		->produce()					//produz o documento de saída (view, pdf, download, etc);
		->dismount();				//desmonta o sistema (fechando arquivos, bd, gerando logs, etc).
		
	//Metodo mais completo ----------------------------------------------------------
	
	$state = 'test'; 				//estado da aplicação (test, ajax, production, etc);
	$preController = 'PreCtrl';		//um précontroller a ser rodado antes do controller principal;
	$posController = 'PosCtrl';		//um póscontroller a ser rodado depois do controller principal;
	$type = 'xhtml';				//força o 'type' do documento de saída (sempre renderizará como "xhtml" mesmo que seja em ajax, texto, etc);
	$app = null;					//ponto de montagem da aplicação (ponto de "ancoragem" para a classe Main).
 	
	
	
	
	//Montando a aplicação
	$app = Main::mount($state);
	
	// >>> Aqui pode ter códigos extras antes de carregar o controller
	
	//Chamando o controller
	$app->control($preController, $posController);
	
	// >>> Aqui pode ter códigos extras antes de produzir a saída
	
	//Fazendo a renderização (docFactory)
	$app->produce($type);
	
	// >>> Aqui pode ter códigos extras antes de finalizar a aplicação
	
	//desmontando a aplicação ( fechando arquivos, bd, fazendo limpezas, logs, etc)
	$app->dismount();
	
	
*/