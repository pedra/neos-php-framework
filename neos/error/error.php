<?php
namespace Neos\Error;
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
		//\Neos\Doc\Factory::this()->setBuffer
		

		ob_clean();
		
		exit(	self::head().
				'<p>' . $m . '</p>
				<p>Arquivo : ' . $f . ' [linha: ' . $l . ']</p>
				<p>Código do erro : ' . $n . '</p>'.
				self::this()->_errorGetTrace().  
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
		$tp = '		
    <div class="t">
        <table width="100%" border="0" cellspacing="3" cellpadding="3">
            <tr>
                <th>Class</th>
                <th>File [line]</th>
            </tr>';
		
		$x = $e->getTrace();
		
		//diferente em Error/Exception		
		if(!$isErro){ 
			array_shift($x);
			array_shift($x);
			array_shift($x);
		}
		
		//invertendo a ordem cronológica dos eventos	
		$x = array_reverse($x);
		
		//lendo o registro (trace)		
		foreach($x as $tc){
				$tp .= '<tr><td>' . ((isset($tc['class'])) ? $tc['class'] : '&nbsp;').
									((isset($tc['type'])) ? $tc['type'] : '&nbsp;').
									((isset($tc['function'])) ? $tc['function'].'()' : '&nbsp;').'</td><td>'.
									((isset($tc['file'])) ? $tc['file'] : '&nbsp;').' ['.((isset($tc['line'])) ? $tc['line'] : '&nbsp;').']</td></tr>';			
			}
		
		return $tp . '
		</table>		
	</div>';
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