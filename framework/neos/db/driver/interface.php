<?php
/** 
 * Interface para Drivers.
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Db
 * @subpackage	Driver
 * @access 		public
 * @return		object Retorna dados de uma operação no banco de dados
 * @since		CAN : B4BC
 */
namespace Neos\Db\Driver;
 
interface Interface_ {
	
	/**
	 * Conectando ao banco de dados
	 * 
	 * @param string $al alias de conexãoo ao banco de dados
	 * @return recurse aponta para o 'recurse' relativo a conexão ou false em erro
	 */
	function connect($al = '');
	
	/**
	 * Simulando o 'PREPARE', caso o banco de dados não o possua (como em Mysql/Sqlite)
	 * 
	 * @param string $sql Uma 'query' com a sinalização das variaveis a serem substituidas
	 * 						por uma função 'bind'.
	 * @param string $nome Nome da query (podem ser feitos vários 'PREPARE's) 
	 * @return void
	 */
	function prepare($sql = '', $nome = 'sql');
	
	/**
	 * Bind (simulação)
	 * 
	 * @param mixed		$var	Nome da variável como definido no 'prepare' ou array (nome=>valor).
	 * @param mixed		$val	Valor.
	 * @param string	$nome	Nome do 'prepare', se foi definido (default = 'sql').
	 * @param bool		$exec	Indica se a query deve ser executada imediatamente.
	 * @return recurse	Retorna o resultado da função 'execute' ou nada, dependendo do estado de '$exec'.
	 */
	function bind($var, $val, $nome = 'sql', $exec = false);
	
	/**
	 * Simulando o 'EXECUTE', caso o banco de dados não o possua (como em Mysql/Sqlite)
	 * 
	 * @param string $nome Nome da query em 'prepare' (opcional).
	 * @return recurse Retorna um apontador para a consulta slq ou nada em caso de erro.
	 */
	function execute($nome = 'sql');
	
	/**
	 * Executa uma consulta no banco de dados (query)
	 * 
	 * @param string $sql Uma 'query' válida.
	 * @return recurse Apontador para o 'recurse' da consulta.
	 */
	function query($sql = '');
	
	/**
	 * Insere dados no banco de dados
	 * 
	 * @param string	$t A tabela alvo da operação
	 * @param array		$a Array com pares 'campo=>valor' representando os campos a serem inseridos
	 * @return bool		True/False
	 */
	function insert($t = '', $a = array());
	
	/**
	 * Fáz alteração nos campos de uma tabela a partir de uma condição (where)
	 * 
	 * @param string	$t Tabela alvo da operação
	 * @param array		$a Array com pares 'campo=>valor' representando os campos a serem modificados
	 * @param string	$w Uma condição 'WHERE' válida (sem a palavra chave WHERE).
	 * @return boll		True/False
	 */
	function update($t = '', $a = array(), $w = '');
	
	/**
	 * GET_FILE
	 * Função que recupera um arquivo armazenado como BLOB (ou CLOB) no Oracle
	
	Entrada:
	 * @param string	$sql 	= Um SELECT cujo resultado seja o campo onde o arquivo esteja armazenado;
	 * @param string	$file	= Nome e extensão que o arquivo receberá para download;
	 * @param string	$force	= True/false -> força o download do arquivo / retorna o arquivo.
		
	Saída:
	 * @return mixed	O próprio arquivo ou será forçado o download do arquivo automaticamente (force=true).
		
	Exemplo:
		_db()->get_file('SELECT M_ARQUIVO FROM TABELA WHERE ID=1','script_php.pdf',true,'get_file');
		
		Resultado:
			Será forçado o download do arquivo 'script_php.pdf' contendo o resultado do 'SELECT ...' ( campo M_ARQUIVO da TABELA).
	*/
	function get_file($sql, $file , $download);
}