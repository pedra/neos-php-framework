<?php
//TODO: PASSAR o conteudo do '__construct' para a função 'mount' ou criar sub-funções para isso

/**SuperClasse Main.
 *
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
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
	public static $THIS = null;
	//static $THIS = array();
	
	/** Url decodificada (sem a sub_uri e outros ruídos)
	 */
	public $varUrl = '';
	
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
		define('NEOS_CAN', 'C4GE');
		header('X-Powered-By: NEOS PHP Framework / ' . NEOS_CAN);

		//Definindo o PATH principal
		define('DS', DIRECTORY_SEPARATOR);
		define('PATH_NEOS', dirname(__FILE__));
		//Estes podem ser pré-definidos no arquivo 'index.php'
		defined('PATH') || define('PATH', realpath(str_replace('phar://','', dirname(__DIR__)))); 
		defined('PATH_APP') || define('PATH_APP', realpath(PATH . '/app'));

		//Evitando a produção de cache no navegador
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		//Charset - garantia de compatibilidade com várias linguagens/browsers...
		header('Content-type: text/html; charset=utf-8');
		
		//setando o include_path
		$incpath = trim(get_include_path(), '.');
		$incpath = explode(PATH_SEPARATOR, $incpath);
		array_shift($incpath);		
		set_include_path(implode(PATH_SEPARATOR, array_merge(array(PATH_APP, str_replace('phar:', 'phar|',PATH_NEOS)), $incpath)));

		//setando a classe de carregamento automático
		if(!function_exists('spl_autoload_register')) exit("spl_autoload não foi instalado neste sistema (PHP)");
		spl_autoload_register( function ($class){ 		
									$class = DS . strtolower(trim(strtr($class, '_\\', DS . DS), DS . '/ '));
									$pth = explode(PATH_SEPARATOR, get_include_path());
									
									foreach($pth as $f){ 
										$f = str_replace('phar|', 'phar:', $f);
										//echo '<br>' . $f . $class . '.php || ' . get_include_path();
										//file_put_contents(PATH . DS . 'logs.txt', $f . $class . NEOS_STATE . ".php \n", FILE_APPEND);
										if(file_exists($f . $class . NEOS_STATE . '.php')) return require $f . $class . NEOS_STATE . '.php';
										if(file_exists($f . $class . '.php')) return require $f . $class . '.php';
									}
								});
								
		//-------- CONFIGURAÇÂO
		Neos\Config\Cfg::this();
		
		//incluindo as funções de compatibilidade com as versões antigas
		include (PATH_NEOS . '/neos/helper/functions.php'); //funçoes globais do núcleo
		
		//-------- ERROS
		//modificando a tela de exibição de erros do PHP
		if(function_exists('ini_set')){
			ini_set('error_prepend_string', file_get_contents(PATH_NEOS . '/neos/error/head.html') . '<p>');
			ini_set('error_append_string', '</p>' . file_get_contents(PATH_NEOS . '/neos/error/footer.html'));
		}

		//Setando a classe de tratamento de erros		
		set_error_handler('\Neos\Error\Error::error');
		set_exception_handler('\Neos\Error\Error::exception');
		
		//Alias para algumas classes
		class_alias('Neos\Config\Cfg', '_cfg');		
		class_alias('Main', '_neos');		
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
		return (!isset(static::$THIS)) ? static::$THIS = new static : static::$THIS;
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
	final static function run($state = '', $subUri = ''){
		$main = self::mount($state, $subUri)
				->control();
		//CORESERVICE
		if(isset(_cfg::get()->args[0]) && _cfg::get()->args[0] == _cfg::get()->admin_url) {
			include 'phar://' . PATH . '/neos.phar/help/core.php'; 
			exit();	
		}
		$main->produce()
				->dismount();
	}

	/**
	 * Montagem da aplicação
	 */
	final static function mount($state = '', $subUri = ''){

		//Estado da Aplicação (test/production)
		if($state == '') $state = self::$arrayStates[0];
		self::$varState = (in_array($state, self::$arrayStates)) 
							? '_' . $state 
							: '';
		self::$varIndex = (self::$varState != self::$arrayStates[0]) 
							? trim(basename($_SERVER['SCRIPT_FILENAME']), '/ ') 
							: '';
		define('NEOS_STATE', self::$varState); 

		//evitando uma sobremontagem
		if(defined('URL_PRE')) return false;  	

		//resolve a URL
		self::this()->varSubUri = trim($subUri, '/\\ ');
		self::this()->varUrl = trim(str_replace($subUri, '', $_SERVER['REQUEST_URI']), '/ ');
		
		//gravando na configuração do sistema
		_cfg::get()->args = trim(urldecode(str_replace(array(self::this()->varSubUri,basename($_SERVER['SCRIPT_FILENAME'])), '', self::this()->varUrl)), '/');		
		_cfg::get()->uri = self::this()->varUrl;

		//start buffer e callback de saída
		ob_start(array(_docFactory::this(), 'outBuffer'));

		//define as constantes URLs
		define('URL_PRE', 'http' . (self::_detectSSL() ? 's' : '') . '://');
		define('URL_DOM', $_SERVER['HTTP_HOST']);
		define('URL_BASE', URL_DOM . '/' . ((self::this()->varSubUri != '') ? self::this()->varSubUri . '/' :  ''));
		define('URL', URL_PRE . URL_BASE);
		define('URL_SEG', _cfg::get()->args);
		$url_link = (self::$varState != self::$arrayStates[0]) ? '' : self::$varIndex . '/';

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
		$ctrl = 'Controller_' . ucfirst(_cfg::get()->ctrl);
		
		//Monta o Controller (carrega com o autoload)
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