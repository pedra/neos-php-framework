<?php
/** 
 * Configuração de Banco de Dados 
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Config
 */


//DEFAULTS
$cfg->db->active				= 'mysql';		//alias para conexão de BD default

//DB::SQLITE
$cfg->db->sqlite->driver		= 'sqlite';
$cfg->db->sqlite->database		= PATH_APP . 'neos.db';

//DB::MYSQL
$cfg->db->mysql->driver		= 'mysql';
$cfg->db->mysql->host		= 'localhost';
$cfg->db->mysql->user		= 'neos2';
$cfg->db->mysql->pass		= 'a123456';
$cfg->db->mysql->database	= 'neos2';
$cfg->db->mysql->charset	= 'utf8';

//DB::MYSQL_PDO
$cfg->db->mysql_pdo->driver		= 'pdo';
$cfg->db->mysql_pdo->dsn		= 'mysql:host=localhost;dbname=neos2';
$cfg->db->mysql_pdo->user		= 'neos2';
$cfg->db->mysql_pdo->pass		= 'a123456';
$cfg->db->mysql_pdo->options	= array('charset'=>'utf8');

/**
 * Vários exemplos de conexões BD

//DB::SQLITE_PDO
$cfg->db->sqlite_pdo->driver	= 'pdo';
$cfg->db->sqlite_pdo->dsn		= 'sqlite2:' . PATH_APP . 'neos.db';

//DB::ORACLE
$cfg->db->oracle->driver		= 'oracle';
$cfg->db->oracle->host			= '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST = 10.160.1.66)(PORT=1521))(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME=mxgmx)))';
$cfg->db->oracle->user			= 'neos';
$cfg->db->oracle->pass			= '123456';
$cfg->db->oracle->database		= 'site';
$cfg->db->oracle->charset		= 'utf8';

//DB::ORACLE_PDO
$cfg->db->oracle_pdo->driver	= 'pdo';
$cfg->db->oracle_pdo->dsn		= 'oci:dbname=(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST = 10.160.1.66)(PORT=1521))(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME=mxgmx)))';
$cfg->db->oracle_pdo->user		= '';
$cfg->db->oracle_pdo->pass		= '';

//DB::POSTGRE_PDO
$cfg->db->postgres_pdo->driver	= 'pdo';
$cfg->db->postgres_pdo->dsn		= 'pgsql:host=localhost port=5432 dbname=testdb user=bruce password=mypass';
$cfg->db->postgres_pdo->user	= '';
$cfg->db->postgres_pdo->pass	= '';
*/