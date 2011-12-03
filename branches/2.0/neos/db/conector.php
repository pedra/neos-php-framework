<?php
/** 
 * Objeto para a Modelagem de dados.
 * Base para a transação de fontes de dados diversas (banco de dados, xml. arquivos 'ini', etc). 
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Db
 * @subpackage	Model
 * @since		CAN : B4BC
 */
 
namespace Neos\Db;

class Conector
	extends Abstract_ {
	
	/**
	* Campos da tabela e suas definições
	*/	
	public $fields = array();
	
	/**
	* Nome da tabela/recurso (pode ser um view ou relacionamento entre tabelas).
	*/
	public $table = NULL;
	
	/**
	* Apelido (alias) da configuração do banco de dados
	*/
	public $alias = NULL;
	
	/**
	* Object 'Driver' - instanciamento da classe do driver
	*/
	public $driver = NULL;
	

	/**
	* Conexão ao driver/banco de dados
	*
	* @param string $alias Apelido da conexão (definida na configuração).
	* @return void 			O driver é carregado e conectado.
	*/	
	function connect($alias = '') {
		//checa se a configuração de banco de dados foi carregada
		if(!isset(\_cfg::cfg()->db)) \_cfg::this()->loadConfig(array('database', 'app/database'));
			
		//checa se um driver já está carregado e corresponde ao alias indicado (ou default)
		if($this->alias != NULL && $alias == '' && is_object($this->driver)) return $this->driver;
		
		//registrando o alias
		$alias == '' ? $this->alias = \_cfg::cfg()->db->active: $this->alias = $alias;
	
		//carregando a classe do driver e conectando
		$classe = __NAMESPACE__ . '\\Driver\\' . ucfirst(\_cfg::cfg()->db->{$this->alias}->driver);
		
		$this->driver = $classe::this();
		$this->driver->connect($this->alias);
		
		return $this->driver;
	}
	
	
	/**
	* Fazendo uma consulta neste recurso (tabela)
	*
	* @param string $sql String contendo a consulta SQL.
	* @param string $alias Apelido da conexão (definida na configuração).
	* @return object Objeto contendo os parâmetros da consulta e acesso aos dados
	*/	
	static function query($sql, $alias = '') {
		return self::this()->connect($alias)->query($sql);		
	}	 
	
	/**
	* Inserindo dados em um recurso
	*
	* @param array $fields Array contendo o par "Campo => Valor" a ser(em) inserido(s)
	* @param string $table O nome da tabela ou recurso alvo.
	* @param string $alias Apelido da conexão (definida na configuração).
	* @return object Objeto contendo os parâmetros da consulta
	*/	
	static function insert($fields = array(), $table = NULL, $alias = ''){
		return self::this()->connect($alias)->insert($table,$fields);
	}	
	
	/**
	* Modificando os dados da tabela ou recurso
	*
	* @param array $fields Array contendo o par "Campo => Valor" a ser(em) modificado(s)
	* @param string $where Deve conter uma cláusula "WHERE" para a definição dos campos a serem modificados
	* @param string $table O nome da tabela ou recurso alvo.
	* @param string $alias Apelido da conexão (definida na configuração).
	* @return object Objeto contendo os parâmetros da consulta e acesso aos dados
	*/	
	static function update($fields = array(), $where = '', $table = NULL, $alias = ''){
		return self::this()->connect($alias)->update($table,$fields,$where);		
	}	
	
	/**
	* Apaga a tabela ou recurso (ou arquivo contendo o recurso)
	*
	* @param string $table O nome da tabela ou recurso alvo.
	* @param string $alias Apelido da conexão (definida na configuração).
	* @return bool Falso/True
	*/	
	static function delete($table = NULL, $alias = ''){
		return self::this()->connect($alias)->delete($table);
	}
	
	/**
	* Apaga o conteúdo da tabela ou recurso
	*
	* @param string $table O nome da tabela ou recurso alvo.
	* @param string $alias Apelido da conexão (definida na configuração).
	* @return bool Falso/True
	*/	
	static function clear($table = NULL, $alias = ''){
		return self::this()->connect($alias)->clear($table);
	}
	
	/**
	* Cria uma tabela ou recurso (ou arquivo contendo o recurso)
	*
	* @param array $fields		Array contendo o par "Nome => Tipo" dos campos a serem criados.
	* @param string $table		O nome da tabela ou recurso alvo (depende do tipo de recurso).	
	* @param string $alias Apelido da conexão (definida na configuração).
	* O "Tipo" segue a sintaxe SQL para definição de campos (ex.: "int(10), primary key .... ")
	* @return bool				Falso/True
	*/	
	static function create($fields = array(), $table, $alias = ''){
		return self::this()->connect($alias)->create($table,$fields);		
	}
	
}