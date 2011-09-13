<?php

//TODO: PASSAR o conteudo do '__construct' para a função 'mount' ou criar sub-funções para isso

/**SuperClasse Main.
 *
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Neos\Main
 */

class Main {

	/** Estado da aplicação (test / production)
	*/
	static $varState = '';

	/** Estados possíveis da aplicação
	*/
	static $arrayStates = array('production', 'test', 'ajax', 'static');

	/** nome do arquivo de indice (index.php?!)
	*/
	static $varIndex = '';

	/** Referencia Singleton!
	 */
	static $THIS = array();

	/** Partição da url que não pertence ao caminho (veja manual)
	 */
	public $varSubUri = '';

	/** Tipo de seleção na URL - Post/Get/Seg.Url
	*/
	public $varUrlType = '';

	/** Variáveis de trabalho da aplicação (pode ser lida/escrita de qualquer lugar)
	 */
	public $varVars	= array();


	/**Construtor da SuperClasse
	*/
	private function __construct(array $args = null){

		//Contagem inicial do temporizador
		define('NEOS_INIT_TIME', microtime(true));

		//Esta versão somente roda no PHP 5.3 ou superior
		if(version_compare(PHP_VERSION, '5.3') < 0) exit('Não há suporte para a versão ' . PHP_VERSION . ' do PHP!');

		//Versão do Framework
		define('NEOS_CAN', 'B5OM');
		header('X-Powered-By: NEOS PHP Framework / ' . NEOS_CAN);

		//Definindo o PATH principal
		define('DS', DIRECTORY_SEPARATOR);
		define('PATH_NEOS', dirname(__FILE__) . DS);
		//Estes podem ser pré-definidos no arquivo 'index.php'
		defined('PATH') || define('PATH', realpath(dirname(__FILE__) . '/../') . DS);
		defined('PATH_APP') || define('PATH_APP', realpath(PATH . 'app') . DS);

		//Evitando a produção de cache no navegador
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		//Charset - garantia de compatibilidade com várias linguagens/browsers...
		header('Content-type: text/html; charset=utf-8');

		//Alguns includes extremamente necessários
		include (PATH_NEOS . 'neos/config' . DS . 'cfg.php'); //classe config
		include_once (PATH_NEOS . 'neos/helper/functions.php'); //funçoes globais do núcleo

		//Alias para as classes BASE
		class_alias('Neos\Config\Cfg', '_cfg');
		class_alias('Main', '_neos');		

		//Carregando imediatamente as configurações
		_cfg::this();

		//Alias para algumas outras classes
		class_alias('Neos\Base', 'NEOS');
		class_alias('Neos\Doc\Factory', '_docFactory');
		class_alias('Neos\Doc\Factory', '_view');
		class_alias('Neos\Db\Conector', '_db');
	}

	/* Construtor singleton da própria classe.
	 * Acessa o método estático para criar uma instância da classe automáticamente.
	 * @return 'this' instance
	*/
	final public static function this(){
		$name = get_called_class();
		if (!isset(static::$THIS[$name])) static::$THIS[$name] = new static;
		return static::$THIS[$name];
	}

	/**
	 * Pegando os HELPERS.
	 * Quando uma função não for encontrada pode ser uma chamada para um helper.
	 * Então, esta rotina tenta localizar um helper que corresponda a solicitação.
	 * Retorna o que retornar o helper ou uma mensagem de erro em caso de falha.
	 *
	 * @param string $var Nome da função solicitada
	 * @param array $val valor repassado
	 * @return mixed
	*/
	final function __call($var, $val) {
		return _helper($var, $val);
	}

	/*
	* Carrega o renderizador (classe 'Doc\Factory')
	* para a geração da saída (html, xml, script, text, etc).
	* "OutputSubFramework"
	*/
	public function produce() {
		return _docFactory::this()->produce();
	}
	
	/*
	* Finaliza a aplicação
	* fecha arquivos abertos, bancos de dados, executa limpezas, geração de logs, etc.
	*/
	public function dismount() {
		return true;
	}

	/**
	 * Montagem da aplicação
	 */
	final static function run($state = 'production'){
		self::mount($state)
			->control()
			->produce()
			->dismount();
	}

	/**
	 * Montagem da aplicação
	 */
	final static function mount($state = 'production'){

		//Estado da Aplicação (text/production)
		self::$varState = (in_array($state, self::$arrayStates)) 
							? $state 
							: 'production';
		self::$varIndex = (self::$varState != self::$arrayStates[0]) 
							? trim($_SERVER['SCRIPT_NAME'], '/') 
							: '';
		define('NEOS_STATE', self::$varState);

		//evitando uma sobremontagem
		if(defined('URL_PRE')) return false;

		//resolve a URL
		self::this()->varSubUri = self::_defSubURi();
		_cfg::get()->args = trim(urldecode(str_replace(basename($_SERVER['SCRIPT_NAME']), '', str_replace(self::this()->varSubUri, '', $_SERVER['REQUEST_URI']))), '/');
		_cfg::get()->uri = str_replace('/' . basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']);

		//start buffer e callback de saída
		ob_start(array(_docFactory::this(), 'outBuffer'));

		//define as constantes URLs
		define('URL_PRE', 'http' . (self::_detectSSL() ? 's' : '') . '://');
		define('URL_DOM', $_SERVER['HTTP_HOST']);
		define('URL_BASE', URL_DOM.'/'.self::this()->varSubUri);
		define('URL', URL_PRE . URL_BASE);
		define('URL_SEG', _cfg::get()->args);
		$url_link = (self::$varIndex == '') 
					? '' 
					: self::$varIndex. '/';

		define('URL_LINK', URL . $url_link);

		//igualando os valores de url para a aplicação
		self::this()->varVars['URL']		= URL;
		self::this()->varVars['URL_LINK']	= URL_LINK;
		_docFactory::value('url_link', URL_LINK);

		//decodifica a solicitação
		_cfg::get()->args = self::this()->decodUrl();

		//Chama uma view estática se existir e morre!
		self::this()->isStaticView();

		//retornando o objeto
		return self::this();
	}

	/**
	 * Fáz a decodificação da URL para descobrir o método de seleção e definir Controller e função a carregar
	 * Retorna: o conteúdo de varUri será tratado.
	 * @return array $array
	*/
	private function decodUrl(){
		//explode a url
		$varUri = explode('/', _cfg::get()->args);

		//GET
		if ($this->varUrlType == '' && isset($_GET[_cfg::get()->get_ctrl])) {
			$varUri[0] = $_GET[_cfg::get()->get_ctrl];
			unset($_GET[_cfg::get()->get_ctrl]);
			if (isset($_GET[_cfg::get()->get_func])) {
				$varUri[1] = $_GET[_cfg::get()->get_func];
				unset($_GET[_cfg::get()->get_func]);
			}
			$varUri = array_merge($varUri, $_GET);
			$this->varUrlType = 'GET';
		}

		//POST
		if ($this->varUrlType == '' && isset($_POST[_cfg::get()->post_ctrl])) {
			$varUri[0] = $_POST[_cfg::get()->post_ctrl];
			unset($_POST[_cfg::get()->post_ctrl]);
			if (isset($_POST[_cfg::get()->post_func])) {
				$varUri[1] = $_POST[_cfg::get()->post_func];
				unset($_POST[_cfg::get()->post_func]);
			}
			$varUri = array_merge($varUri, $_POST);
			$this->varUrlType = 'POST';
		}

		//RELATIVE (URL)
		if ($this->varUrlType == '') {
			isset($varUri[0]) && $varUri[0] != '' 
				? $varUri[0] = trim($varUri[0]) 
				: $varUri[0] = _cfg::get()->ctrl;
			isset($varUri[1]) && $varUri[1] != '' 
				? $varUri[1] = trim($varUri[1]) 
				: '';
			$this->varUrlType = 'URL';
		}

		//Mask
		if (isset(_cfg::get()->mask[$varUri[0]])) {
			$c = trim($varUri[0]);
			unset($varUri[0]);
			foreach (array_reverse(_cfg::get()->mask[$c]) as $v) {
				array_unshift($varUri, $v);
				unset($c);
			}
		}
		return $varUri;
	}

	/**
	* Chama uma view estática e morre !!!
	* Morre! - termina o sistema se encontrar uma view estática.
	*
	* @return void
	*/
	private function isStaticView(){
		if (_cfg::get()->static_view !== false && isset(_cfg::get()->args[0])) {
			$p = strtolower(str_ireplace(array('.html', '.htm', '.php', '.neos'), '', trim(_cfg::get()->args[0], '/\\')));
			if ($p != '.html' && file_exists(APP_VIEW . 'statics' . DS . $p . '.html')) {
				_cfg::get()->out_filter = false;
				exit(file_get_contents(APP_VIEW . 'statics' . DS . $p . '.html'));
			}
		}
	}

	final public function control(){ 

		//Definindo o controller
		if (file_exists(APP_CONTROLLER . strtolower(_cfg::get()->args[0]) . EXTCTRL)) {
			_cfg::get()->ctrl = ucfirst(trim(_cfg::get()->args[0]));
			unset(_cfg::get()->args[0]);
		} elseif (_cfg::get()->error_route != '' && strpos(_cfg::get()->error['action'], 'route') !== false) {
			_cfg::get()->ctrl = ucfirst(_cfg::get()->error_route);
			unset(_cfg::get()->args[0]);
		}
		if (!file_exists(APP_CONTROLLER . strtolower(_cfg::get()->ctrl) . EXTCTRL)) {
			_cfg::get()->error['cod'] = 1;
			trigger_error('Controller "' . _cfg::get()->ctrl . '" n&atilde;o encontrado.');
		}

		//Definindo a função
		if (isset(_cfg::get()->args[1])) {
			$func = trim(_cfg::get()->args[1], '_');
			if (_cfg::get()->args[1] == _cfg::get()->func) {
				unset(_cfg::get()->args[1]);
			}
		} else $func = _cfg::get()->func;

		//Cache
		if (isset(_cfg::get()->cache_time) && _cfg::get()->cache_time > 0) Library\Cache::start($func);

		//Chamando o controller/função
		$ctrl = 'Controller_' . _cfg::get()->ctrl;

		//_cfg::get()->args = &_cfg::get()->args;
		if (method_exists($ctrl, $func)) {
			if (isset(_cfg::get()->args[1]) && $func == _cfg::get()->args[1]) {
				unset(_cfg::get()->args[1]);
				_cfg::get()->func = $func;
			}
			call_user_func_array(array($ctrl::this(), _cfg::get()->func), _cfg::get()->args);
		} else {
			call_user_func_array(array($ctrl::this(), _cfg::get()->func), _cfg::get()->args);
		}

		//retornando o objeto
		return self::this();
	}

	/*
	* Verifica a existencia de uma camada extra na URL
	* ex.: quando precisa ser indicado um sub-diretório além do domínio ( http://localhost/sub-diretório/....)
	*/
	public static function _defSubURi() {
		$path = explode('/', trim(dirname($_SERVER['SCRIPT_FILENAME']), '/ '));		
		$uri = explode('/',trim(str_replace('/' . basename($_SERVER['SCRIPT_FILENAME']),'',$_SERVER['REQUEST_URI']), '/ '));
		$sub_uri = '';
		
		foreach($path as $p){
			if($path[0] == $uri[0]) {
				$sub_uri .= $uri[0] . '/';
				array_shift($uri);
			}
			array_shift($path);		
		}
		//é possível pegar a url limpa: $URL = implode('/', $uri);
		return $sub_uri;		
	}

	/*
	* detecta se o acesso está sendo feito por SSL (https)
	*/
	private static function _detectSSL(){
		if (!isset($_SERVER["HTTPS"]))		return false;
		if ($_SERVER["HTTPS"] == "on")		return true;
		if ($_SERVER["HTTPS"] == 1)			return true;
		if ($_SERVER['SERVER_PORT'] == 443) return true;
		return false;
	}
}