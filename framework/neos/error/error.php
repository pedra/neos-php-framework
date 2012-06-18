<?php
namespace Error;
/** 
 * Classe para tratamento de erros e exceções.
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Error
 * @access 		public
 * @return		mixed Error/Exception display
 * @since		CAN : B4BC
 */

class Error 
	extends \Exception {
		
	/**
	 * referencia estática a própria classe!
	 */
	public static $THIS = null;

	/**
	 * Código do erro atual e referência para HELP! 
	 */
	public $codigo = 0;

	/**
	 * Path para a classe que originou o erro 
	 */
	public $classPath = null;

	
	
	
	/**
	 * Construtor da classe Exception (parent)
	 * 
	 * @return void
	*/	
	
	public function __construct($m = '',$c = 0){
		parent::__construct($m, $c);
	}
	
	/**
	 * Construtor singleton da própria classe
	 * acesso ao método estático para criar uma instância da classe automáticamente
	 * 
	 * @return this instance
	*/
	public static function this(){
		return (!isset(static::$THIS)) ? static::$THIS = new static : static::$THIS;
	}
	
	/**
	 * Controle de erros do Framework
	 * 
	 * @param $n 	código do erro
	 * @param $m	mensagem de erro
	 * @param $f	arquivo onde ocorreu o erro
	 * @param $l	número da linha onde ocorreu o erro
	 * @param $v	array com variáveis disponíveis no contexto
	 *
	 * @return html|void	mostra uma mensagem de erro; toma uma decisão programada ou retorna sem ação. 
	*/
	public static function error($n=0, $m='', $f='', $l='', $v=''){					
		$d = (count($v)>0) ? '<pre>Dados : ' . print_r($v,true) . '</pre>':'';
		ob_clean();
		
//		exit(	self::head().
//				'<h2>' . $m . '</h2>
//				<p>Arquivo : ' . $f . ' [linha: ' . $l . ']</p>
//				<p>Código do erro : ' . $n . '</p>'.
//				self::this()->_errorGetHelp() .
//				self::this()->_errorGetTrace().  
//				self::footer()
//			);
		$trace = self::this()->_errorGetTrace();
		exit(	self::head().
				'<div id="msg"><h2>' . $m . '</h2>'.
				$trace['dt']. '</div>' .
				self::this()->_errorGetHelp() .
				$trace['table'] .
				self::footer()
				);
					
	}
	
	/**
	 * Controle de exceção do Framework
	 * 
	 * @param $e objeto Exception 
	 * @return void O retorno depende da função 'self::error' (acima).
	*/
	public static function exception($e){ 
		$m = $e->getMessage();
		$f = $e->getFile();
		$l = $e->getLine();
		$n = $e->getCode();		
		if($n == 0 && method_exists($e, 'getSeverity')) $n = $e->getSeverity();
		self::error($n, $m, $f, $l);
	}

	/**
	 * Procura por uma ajuda sobre o erro
	 * 
	 * @param $nHelpCod number	Código de erro interno 
	 * @return html		Retorna uma ajuda sobre o erro atual ou uma string vazia se não existir
	*/
	
	protected function _errorGetHelp(){
		if($this->classPath == null) return '';
		
		$x = explode('\\', strtolower($this->classPath));
		for($i = count($x); $i > 0; $i--){
			array_pop($x);
		 	$path = PATH_NEOS.DS.'neos'.DS.implode(DS, $x).DS.'error.php';
			if (file_exists($path) && include($path)) break;		 	
		 }
		//checando se o arquivo foi encontrado - [não -> carrega o default]
		if(!isset($ehelp[$this->codigo])) include(PATH_NEOS . '/neos/error/report.php');
		if(isset($ehelp[$this->codigo])) {
			$out = '<div id="ajuda"><h2>Ajuda</h2>
			<p>';	
			$out .= $ehelp[$this->codigo];
			$out .= '</p></div>';
			return $out;
		}
		return '';		
	}
		
	/**
	 * Controle de exceção do Framework
	 * 
	 * @param $e 
	 * @return html
	*/
	protected function _errorGetTrace($e=''){
		$isErro = true;
		if(!is_object($e)) {
			$e = $this; 
			$isErro = false;
		}
		$tp = '<div id="trace">
		<h2>Rastro (trace)</h2>
		<small>Listagem do que aconteceu com o framework antes de parar por causa do erro.</small>
        <table id="neoserrortrace" cellspacing="0" cellpadding="0">
            ';
		
		$x = $e->getTrace();

		//diferente em Error/Exception		
		if(!$isErro){ 
			array_shift($x);
			//array_shift($x);
			//array_shift($x);
		}
		
		//invertendo a ordem cronológica dos eventos	
		$x = array_reverse($x);
		
		//lendo o registro (trace)		
		foreach($x as $tc){
				$tp .= "\n" . '<tr><td>' . ((isset($tc['class'])) ? $tc['class'] : '&nbsp;').
									((isset($tc['type'])) ? $tc['type'] : '&nbsp;').
									((isset($tc['function'])) ? $tc['function'].'()' : '&nbsp;').
						'</td><td>'.((isset($tc['file'])) ? $tc['file'] : '&nbsp;').((isset($tc['line'])) ? ' [' .$tc['line'] . ']' : '').'</td></tr>';			
			}
		
		$tp .= '
		</table></div>';
		
		$dt = (($this->classPath != null) ? '<p><b>Recurso: </b>' . $this->classPath . '</p>' : '') .'
				<p><b>Arquivo: </b>' . ((isset($tc['file'])) ? $tc['file'] : '&nbsp;').((isset($tc['line'])) ? ' [' .$tc['line'] . ']' : '') . '</p>';
		return array('table' => $tp, 'dt'=>$dt);
	}	
	
	/**
	 * Pega o head html
	 * 
	 * @return html
	*/
	public static function head(){
		return file_get_contents( dirname(__FILE__) . '/head.html' );
	}
	
	/**
	 * Pega o footer html
	 * 
	 * @return html
	*/
	public static function footer(){
		return file_get_contents( dirname(__FILE__) . '/footer.html' );
	}
	
}