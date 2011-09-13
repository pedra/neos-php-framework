<?php
namespace Neos\Error;
/** 
 * Classe para tratamento de erros e exceções.
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Error
 * @access 		public
 * @return		mixed Error/Exception display
 * @since		CAN : B4BC
 */

class Error extends \Exception {
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
	 * @param $var nome da variável requerida
	 * @param $val valor a ser inserido
	 * @return mixed conteudo da variável requerida
	*/
	public function error($n=0, $m='', $f='', $l='', $v=''){			
		$d = (count($v)>0) ? '<pre>Dados : ' . print_r($v,true) . '</pre>':'';
		\_docFactory::this()->setBuffer(
										self::head().
										'<p>' . $m . '</p>
										<p>Arquivo : ' . $f . ' [linha: ' . $l . ']</p>
										<p>Código do erro : ' . $n . '</p>'.
										$this->_errorGetTrace().  
										self::footer()
										);
		exit;
	}
	
	/**
	 * Controle de exceção do Framework
	 * 
	 * @param $e 
	 * @return void
	*/
	public static function exception($e){
		$m = $e->getMessage();
		$f = $e->getFile();
		$l = $e->getLine();
		$n = $e->getCode();		
		if($n == 0 && method_exists($e, 'getSeverity')) $n = $e->getSeverity();
		\_docFactory::this()->setBuffer(
										self::head().
										'<p>' . $m . '</p>
										<p>Arquivo : ' . $f . ' [linha: ' . $l . ']</p>
										<p>Código  : ' . $n . '</p>'.
										self::this()->_errorGetTrace($e).  
										self::footer()
										);print_r(\_cfg::this());
										
		exit;
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
		$content = '
		<h3>Trace:</h3>
    <div class="t">
        <table cellspacing="0">
            <tr>
                <th>Class</th>
                <th>&nbsp;</th>
                <th>Function</th>
                <th>File</th>
                <th>Line</th>
            </tr>';
		
		$x=$e->getTrace();
		
		//diferente em Error/Exception		
		if(!$isErro) {array_shift($x);array_shift($x);array_shift($x);}		
		$x=array_reverse($x);		
		foreach($x as $k=>$tc){
			$content.='<tr><td>';
			if(isset($tc['class'])){$content.= $tc['class'];}else{$content.='&nbsp;';}
			$content.= '</td><td align="center">';
			if(isset($tc['type'])){$content.= $tc['type'];}else{$content.='&nbsp;';}
			$content.= '</td><td>';			
			if(isset($tc['function'])){
				$content.= $tc['function'].'()';
			}else{$content.='&nbsp;';}			
			$content.= '</td><td>';			

			if(isset($tc['file'])){$content.= $tc['file'];}else{$content.='&nbsp;';}
			$content.= '</td><td align="center">';
			if(isset($tc['line'])){$content.= $tc['line'];}else{$content.='&nbsp;';}
			$content.= '</td></tr>
			';			
		}
		return $content . '
			<tr><td colspan="6">&nbsp;</td></tr>
		</table>
		<address>Desative esta mensagem se a sua aplicação estiver em "produção". Crie um controlador para o tratamento de erros da aplicação!<br />Você pode encontrar mais informações no <b><a href="">manual</a></b> do NEOS.</address>		
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