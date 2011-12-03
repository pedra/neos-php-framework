<?php
/**
 * MicroData
 * Gerencia um micro banco de dados. Pode ser usado em substituição a arquivos "ini" e até SQLITE para micro dados.
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Neos\Asset
 */
class MD {
	
/**
 * Singleton object
 */
   static $THIS = null;
   
/**
 * Nome completo do arquivo de dados (microdata.mdata)
 */
   public $dataFile;

/**
 * Array contendo o cabeçalho do MicroData
 */
   public $head;   
   
/**
 * Array contendo os dados do MicroData
 */
   public $data;
	
/**
 * Obtenção de instância a partir de um acesso stático (singleton :P)
 *
 * @access public
 * @return static			Instância do próprio objeto
 */
    public static function this() {
        if (!isset(static::$THIS)) static::$THIS = new static;
		return static::$THIS;
    }
	
/**
 * Retorna o conteúdo de uma linha (array contendo as colunas)
 *
 * @access public
 * @param  integer $row		Número da linha a ser retornada
 * @return array				A linha requisitada ou TODO as linhas 
 */ 
 	static function mount($f = ''){
		$md = self::this();
		//setando o arquivo "microdata"
		$md->dataFile = realpath(($f == '') ? dirname(__FILE__) . '/microdata.mdata' : $f);
		//se não existe: cria um arquivo vazio
        if (!file_exists($md->dataFile)) file_put_contents($md->dataFile, '');
		//pega o arquivo em um array indexado pelas linhas obtidas do arquivo
        $d = file($md->dataFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		//pegando o cabeçalho
		$hd = $d[0];
		$md->head = $hd = explode(' | ', $hd);
		//retirando a primeira linha -> cabeçalho
		array_shift($d);
		//iniciando o array
        $r = array();
		//pegando os dados de cada coluna: ETAG/FILE/TIME
        foreach ($d as $k=>$v) {
            $c = count($r);
            $t = explode(' | ', $v);			
			//adiciona ao array DATA ($r) com as chaves
			foreach($hd as $kk=>$vv){
				//para um novo DATA ($r), com índice do HEAD, carrega o valor da coluna $t[$k]
				$r[$k][$vv] = trim($t[$kk]);
			}
        }
		//carrega a propriedade "data" com os dados (array bidimensional)		
		return $md->data = $r;
	}	

/**
 * Retorna o conteúdo de uma linha (array contendo as colunas)
 *
 * @access public
 * @param  integer $row		Número da linha a ser retornada
 * @return array				A linha requisitada ou TODO as linhas 
 */
    static function getData($row='') {
        $md = self::this();
        if ($row != '' && isset($md->data[$row])) return $md->data[$row];
		return $md->data;
    }

/**
 * Modifica/insere dados no MicroData
 *
 * @access public
 * @param  integer|array $row Número da LINHA a ser modificada
 * 							 Se "$row" for um array o microdata inteiro será substituido
 * @param  string $id		Identificador da COLUNA
 * @param  string $val		Valor a ser inserido/modificado
 * @return array				A linha requisitada ou TODAS as linhas 
 */
    static function setData($row, $id='', $val='') {
        $md = self::this();
        if (is_array($row))			return $md->data = $row; //substitue TODO o MD
		if (isset($md->data[$row]))	return $md->data[$row][$id] = $val; //somente a linha indicada
		return false;
    }

/**
 * Destrutor da classe
 * Salva os dados no arquivo "MicroData"
 *
 * @access public
 * @return void	Salva os dados no arquivo "MicroData" 
 */
    function __destruct() {
        $md = self::this();
        $t  = implode(' | ', $md->head) . "\n";
        foreach ($md->data as $v) $t .= implode(' | ', $v) . "\n";		
		return file_put_contents($md->dataFile, $t);
    }
}