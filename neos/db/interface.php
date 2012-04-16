<?php
/** 
 * Interface para o Conector de Banco de Dados.
 * Base para a transação de fontes de dados diversas (banco de dados, xml. arquivos 'ini', etc). 
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Db
 * @subpackage	Conector
 * @since		CAN : B4BC
 */
 
namespace Neos\Db;

interface Interface_ {

	/**
	* Conexão ao driver/banco de dados
	*
	* @param string $alias Apelido da conexão (definida na configuração).
	* @return void 			O driver é carregado e conectado.
	*/	
	function connect($alias = '');
	
	
	/**
	* Fazendo uma consulta neste recurso (tabela)
	*
	* @param string $sql String contendo a consulta SQL.
	* @param string $alias Apelido da conexão (definida na configuração).
	* @return object Objeto contendo os parâmetros da consulta e acesso aos dados
	*/	
	static function query($sql, $alias = '');
	
	/**
	* Inserindo dados em um recurso
	*
	* @param array $fields Array contendo o par "Campo => Valor" a ser(em) inserido(s)
	* @param string $table O nome da tabela ou recurso alvo.
	* @param string $alias Apelido da conexão (definida na configuração).
	* @return object Objeto contendo os parâmetros da consulta
	*/	
	static function insert($fields = array(), $table = NULL, $alias = '');
	
	/**
	* Modificando os dados da tabela ou recurso
	*
	* @param array $fields Array contendo o par "Campo => Valor" a ser(em) modificado(s)
	* @param string $where Deve conter uma cláusula "WHERE" para a definição dos campos a serem modificados
	* @param string $table O nome da tabela ou recurso alvo.
	* @param string $alias Apelido da conexão (definida na configuração).
	* @return object Objeto contendo os parâmetros da consulta e acesso aos dados
	*/	
	static function update($fields = array(), $where = '', $table = NULL, $alias = '');
	
	/**
	* Apaga a tabela ou recurso (ou arquivo contendo o recurso)
	*
	* @param string $table O nome da tabela ou recurso alvo.
	* @param string $alias Apelido da conexão (definida na configuração).
	* @return bool Falso/True
	*/	
	static function delete($table = NULL, $alias = '');
	
	/**
	* Apaga o conteúdo da tabela ou recurso
	*
	* @param string $table O nome da tabela ou recurso alvo.
	* @param string $alias Apelido da conexão (definida na configuração).
	* @return bool Falso/True
	*/	
	static function clear($table = NULL, $alias = '');
	
	/**
	* Cria uma tabela ou recurso (ou arquivo contendo o recurso)
	*
	* @param array $fields		Array contendo o par "Nome => Tipo" dos campos a serem criados.
	* @param string $table		O nome da tabela ou recurso alvo (depende do tipo de recurso).	
	* @param string $alias Apelido da conexão (definida na configuração).
	* O "Tipo" segue a sintaxe SQL para definição de campos (ex.: "int(10), primary key .... ")
	* @return bool				Falso/True
	*/	
	static function create($fields = array(), $table, $alias = '');
	
}