<?php
include 'neos/base.php';

/**SuperClasse Main.
 *
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Neos\Main
 */

class Main
	extends Neos\Base {

	/** Estado da aplicação (test / production)
	*/
	static $varState = '';

	/** Estados possíveis da aplicação
	*/
	static $arrayStates = array('production', 'test', 'ajax', 'static');

	/** nome do arquivo de indice (index.php?!)
	*/
	static $varIndex = '';
	
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


	/**
	* Construtor da SuperClasse
	*/
	function __construct(array $args = null){
		
		//defines iniciais
		self::initDefines();
		
		//ajustando o navegador do usuário
		self::initHeaders();
		
		//preparando o carregador automático de classes
		self::initLoader();			
							
		//carregando as configurações
		class_alias('Neos\Base', 'NEOS');
		Config\Cfg::this();
		
		//ERROS
		self::initError();
		
		//Alias para algumas classes
		self::classAlias();
	}

	/**
	 * Pegando a instância de um objeto do array $THIS, na Library
	 *
	 * @param String Nome (simples) da classe na Library do Neos
	 * @return object
	*/
	final static function lib($class){
		$class = 'Library\\' . ucfirst(strtolower($class));
		if(!isset(static::$THIS[$class])) static::$THIS[$class] = new $class;
		return static::$THIS[$class];	
	}
	
	
	/**
	 * Definindo os parâmetros e constantes iniciais.
	 *
	 * @return bool
	*/
	final static function initDefines() {
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
		if(!defined('PATH_APP')){
			if(file_exists(PATH . '/app.phar')) define('PATH_APP', 'phar://' . PATH . '/app.phar');
			else define('PATH_APP', realpath(PATH . '/app'));
		}
	}
	
	/**
	 * Ajustes no navegador do usuário.
	 *
	 * @return bool
	*/
	final static function initHeaders() {
		//Evitando a produção de cache no navegador
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		//Charset - garantia de compatibilidade com várias linguagens/browsers...
		header('Content-type: text/html; charset=utf-8');
	}
	
	/**
	* Inicialização do carregador automático
	*/
	final static function initLoader(){
		//setando o include_path
		$incpath = trim(get_include_path(), '.');
		$incpath = explode(PATH_SEPARATOR, $incpath);
		array_shift($incpath);		
		set_include_path(implode(PATH_SEPARATOR, array_merge(array(
													str_replace('phar:', 'phar|', PATH_APP),
													str_replace('phar:', 'phar|', PATH_NEOS . DS . 'neos'),
													str_replace('phar:', 'phar|', PATH_NEOS)
													), $incpath)));

		//setando a classe de carregamento automático
		if(!function_exists('spl_autoload_register')) exit("spl_autoload não foi instalado neste sistema (PHP)");
		spl_autoload_register( function ($class){ 		
									$class = DS . strtolower(trim(strtr($class, '_\\', DS . DS), DS . '/ '));
									$pth = explode(PATH_SEPARATOR, get_include_path());
									
									foreach($pth as $f){ 
										$f = str_replace('phar|', 'phar:', $f); 
										if(file_exists($f . $class . NEOS_STATE . '.php')) return require $f . $class . NEOS_STATE . '.php';
										if(file_exists($f . $class . '.php')) return require $f . $class . '.php';
									}
								});
	}

	/**
	 * Fazendo os 'settings' de erros.
	 * Fáz os ajustes iniciais dos reports de erros.
	 *
	 * @return bool
	*/
	final static function initError() {
		//modificando a tela de exibição de erros do PHP
		if(function_exists('ini_set')){
			ini_set('error_prepend_string', file_get_contents(PATH_NEOS . '/neos/error/head.html') . '<p>');
			ini_set('error_append_string', '</p>' . file_get_contents(PATH_NEOS . '/neos/error/footer.html'));
		}

		//Setando a classe de tratamento de erros		
		set_error_handler('Error\Error::error');
		set_exception_handler('Error\Error::exception');
	}
	
	/**
	* Alias para as classes basicas.
	*/
	final static function classAlias(){
		//Alias para algumas classes
		class_alias('Config\Cfg', '_cfg');		
		class_alias('Main', 'o');
		class_alias('Doc\Factory', '_docFactory');
		class_alias('Doc\Factory', '_view');
		class_alias('Db\Conector', '_db');
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
		//_pt( self::$THIS );
		return true;
	}

	/**
	 * Montagem da aplicação
	 */
	final static function run($state = '', $subUri = ''){
		$main = self::mount($state, $subUri)
				->control();
		//CORESERVICE
		if(strpos($_SERVER['REQUEST_URI'], _cfg::this()->admin_url) !== false) include 'help/core.php';
		
		if($main == false) {
			//controller e app não existem...
			if(!is_dir(PATH_APP) && !is_dir(APP_CONTROLLER)) include 'help/firstrun.php';
			else \o::_error('Controller "' . _cfg::this()->ctrl . '" n&atilde;o encontrado.',1);
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
		_cfg::this()->args = 
		trim(urldecode(str_replace(array(self::this()->varSubUri,basename($_SERVER['SCRIPT_FILENAME'])), '', self::this()->varUrl)), '/');		
		_cfg::this()->uri = self::this()->varUrl;

		//start buffer e callback de saída
		ob_start(array(_docFactory::this(), 'outBuffer'));

		//define as constantes URLs
		define('URL_PRE', 'http' . (self::_detectSSL() ? 's' : '') . '://');
		define('URL_DOM', $_SERVER['HTTP_HOST']);
		define('URL_BASE', URL_DOM . '/' . ((self::this()->varSubUri != '') ? self::this()->varSubUri . '/' :  ''));
		define('URL', URL_PRE . URL_BASE);
		define('URL_SEG', _cfg::this()->args);
		$url_link = (self::$varState != self::$arrayStates[0]) ? '' : self::$varIndex . '/';

		define('URL_LINK', URL . $url_link);
		
		//igualando os valores de url para a aplicação
		self::this()->varVars['URL']		= URL;
		self::this()->varVars['URL_LINK']	= URL_LINK;
		_docFactory::value('url_link', URL_LINK);

		//decodifica a solicitação
		_cfg::this()->args = self::this()->decodUrl();

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
		$varUri = explode('/', _cfg::this()->args);

		//GET
		if ($this->varUrlType == '' && isset($_GET[_cfg::this()->get_ctrl])) {
			$varUri[0] = $_GET[_cfg::this()->get_ctrl];
			unset($_GET[_cfg::this()->get_ctrl]);
			if (isset($_GET[_cfg::this()->get_func])) {
				$varUri[1] = $_GET[_cfg::this()->get_func];
				unset($_GET[_cfg::this()->get_func]);
			}
			$varUri = array_merge($varUri, $_GET);
			$this->varUrlType = 'GET';
		}

		//POST
		if ($this->varUrlType == '' && isset($_POST[_cfg::this()->post_ctrl])) {
			$varUri[0] = $_POST[_cfg::this()->post_ctrl];
			unset($_POST[_cfg::this()->post_ctrl]);
			if (isset($_POST[_cfg::this()->post_func])) {
				$varUri[1] = $_POST[_cfg::this()->post_func];
				unset($_POST[_cfg::this()->post_func]);
			}
			$varUri = array_merge($varUri, $_POST);
			$this->varUrlType = 'POST';
		}

		//RELATIVE (URL)
		if ($this->varUrlType == '') {
			isset($varUri[0]) && $varUri[0] != '' 
				? $varUri[0] = trim($varUri[0]) 
				: $varUri[0] = _cfg::this()->ctrl;
			isset($varUri[1]) && $varUri[1] != '' 
				? $varUri[1] = trim($varUri[1]) 
				: '';
			$this->varUrlType = 'URL';
		}

		//Mask
		if (isset(_cfg::this()->mask[$varUri[0]])) {
			$c = trim($varUri[0]);
			unset($varUri[0]);
			foreach (array_reverse(_cfg::this()->mask[$c]) as $v) {
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
		if (_cfg::this()->static_view !== false && isset(_cfg::this()->args[0])) {
			$p = strtolower(str_ireplace(array('.html', '.htm', '.php', '.neos'), '', trim(_cfg::this()->args[0], '/\\')));
			if ($p != '.html' && file_exists(APP_VIEW . 'statics' . DS . $p . '.html')) {
				_cfg::this()->out_filter = false;
				exit(file_get_contents(APP_VIEW . 'statics' . DS . $p . '.html'));
			}
		}
	}

	final public function control(){
		 
		//Definindo o controller
		if (file_exists(APP_CONTROLLER . strtolower(_cfg::this()->args[0]) . EXTCTRL)) {
			_cfg::this()->ctrl = ucfirst(trim(_cfg::this()->args[0]));
			unset(_cfg::this()->args[0]);
		} elseif (_cfg::this()->error_route != '' && strpos(_cfg::this()->error['action'], 'route') !== false) {
			_cfg::this()->ctrl = ucfirst(_cfg::this()->error_route);
			unset(_cfg::this()->args[0]);
		}
		
		if (!file_exists(APP_CONTROLLER . strtolower(_cfg::this()->ctrl) . EXTCTRL)) {
			return false;
			//_cfg::this()->error['cod'] = 1;
			//trigger_error('Controller "' . _cfg::this()->ctrl . '" n&atilde;o encontrado.');
		}
		
		//Definindo a função
		if (isset(_cfg::this()->args[1])) {
			$func = trim(_cfg::this()->args[1], '_');
			if (_cfg::this()->args[1] == _cfg::this()->func) {
				unset(_cfg::this()->args[1]);
			}
		} else $func = _cfg::this()->func;
		

		//Cache
		if (isset(_cfg::this()->cache_time) && _cfg::this()->cache_time > 0) Library\Cache::start($func);

		//Chamando o controller/função
		$ctrl = 'Controller_' . ucfirst(_cfg::this()->ctrl);
		
		//Monta o Controller (carrega com o autoload)
		if (method_exists($ctrl, $func)) {
			if (isset(_cfg::this()->args[1]) && $func == _cfg::this()->args[1]) {
				unset(_cfg::this()->args[1]);
				_cfg::this()->func = $func;
			}
			call_user_func_array(array($ctrl::this(), _cfg::this()->func), _cfg::this()->args);
		} else {			
			call_user_func_array(array($ctrl::this(), _cfg::this()->func), _cfg::this()->args);
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
	
	/*
	 * Dispara o sistema de ERROR do NEOS
	 *
	 * @param $msg String Mensagem de erro a ser exibida
	 * @param $cod Number (se existir) Código da ajuda para o erro 
	 *
	 * @return void 	Gera um erro no sistema!	 
	 */	 
	 static function errorxx($msg, $cod = 0, $class = ''){
		Error\Error::this()->codigo = $cod; exit(_pt(func_get_args()));
		trigger_error($msg);		 
	 }
}