<?php
namespace Neos\Doc;
/**
 * Sub-framework para a Renderização de saída
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Neos\Doc
 * @access 		public
 * @return		string Documento enviado ao browser - html/xhtml/javascript/text/jason/xml/etc.
 * @since		CAN : B4BC
 */

class Factory
	extends \Neos\Base {

	/**
	 * Array de logs (push log)
	 */
	public $varPush = array();

	/**
	 * TimeStamp da inicialização do sistema (microsegundos)
	 */
	public $varTime = 0;

	/**
	 * buffer de saída
	 */
	public $varBuffer = '';

	/**
	 * Ativa o tratamento do buffer de saída
	 */
	public $varBufferOn = true;

	/**
	 * Ativa a compressão de saída
	 */
	public $varCompress = true;

	/**
	 * Tipo de renderização
	 */
	public $varType = 'html';

	/**
	 * Buffer contendo views a serem renderizadas
	 */
	public $varViews = array();

	/**
	 * Buffer contendo variáveis para as views
	 */
	public $varViewVar = array();

	/**
	 * Layout a ser usado durante a renderização
	 */
	public $varLayout = '';

	/**
	 * Classe de renderização
	 */
	public $objRender = NULL;
	
	/**
	 * Localização dos arquivos de CSS da aplicação
	 */
	public $varPathCss = NULL;
	
	/**
	 * URL dos arquivos de CSS da aplicação
	 */
	public $varUrlCss = NULL;
	
	/**
	 * Localização dos arquivos de JavaScript da aplicação
	 */
	public $varPathJs = NULL;
	
	/**
	 * URL dos arquivos de JavaScript da aplicação
	 */
	public $varUrlJs = NULL;
	
	/**
	 * Array com os arquivos de CSS a serem carregados pela classe de renderização de html
	 */
	public $varCss = array();
	
	/**
	 * Array com os arquivos de JS a serem carregados pela classe de renderização de html
	 */
	public $varJs = array();
	
	
	function __construct(){
		//pegando os PATHs para CSS
		$this->varPathCss = \_cfg::this()->varConfig->pathCss;
		$this->varUrlCss = \_cfg::this()->varConfig->urlCss;
		
		//pegando os PATHs para JavaScript
		$this->varPathJs = \_cfg::this()->varConfig->pathJs;
		$this->varUrlJs = \_cfg::this()->varConfig->urlJs;	
	}


	/**
	 * Setando uma View
	 *
	 * @param string $view nome do arquivo contendo a view
	 * @param array $data variáveis para a view
	 * @param string $nome nome de referencia para a view
	 *
	 * @return void adiciona a view (e variáveis) para a renderização
	*/
	static function set($view='index', $data='', $nome=''){
		//eliminando extensões, se existirem
		$view = str_ireplace(array('.html', '.htm', '.php', '.neos'), '', trim($view));

		//gravando no buffer de views
		$nome != '' ? self::this()->varViews[$nome] = $view : self::this()->varViews[] = $view;

		//repassando variáveis
		if (is_array($data)) \_view::value($data, $nome, $nome);
	}

	/**
	 * Carregando uma variável para as views
	 *
	 * @param string $var nome da variável
	 * @param mixed $var valor da variável
	 * @param string $view nome da view a que pertence
	 * @return void conteudo da variável será armazenada para a renderização da view
	*/
	
	//alias para o método 'value'
	static function val($var, $val='', $view = null){self::value($var, $val, $view);}
	static function value($var, $val='', $view = null){
		//se for enviado um array de variáveis...
		if(is_array($var)){
			foreach ($var as $k=>$v){
				if($view == null) $view = 0;
				if(is_numeric($k)) $k = 'default';
				self::this()->varViewVar[$view][$k] = $v;
			}
		} else {
		//caso seja um para $var->$val
			if($view == null) $view = 0;
			self::this()->varViewVar[$view][$var] = $val;
		}
	}

	/**
	 * Listagem de "bookmarks"
	 *
	 * @param $v valor a ser mostrado (string)
	 * @return void
	*/
	static function push($v){
		self::this()->varPush[]=_pt($v,false);
	}

	/**
	 * Carrega o buffer de saída
	 *
	 * @return void
	*/
	function setBuffer($val){
		$this->varBuffer = $val;
	}

	/**
	 * Renderiza a saída
	 *
	 * @return void
	*/
	function produce($content = ''){
		
		if (_cfg()->template != '') {
		//Se deve usar um template...
			if (file_exists(_cfg()->template_path . _cfg()->template . DS . 'engine' . DS . 'template.php')) {
				include _cfg()->template_path . _cfg()->template . DS . 'engine' . DS . 'template.php';				
				$this->objRender = Template::this();
			//Caso não for encontrada a classe de template na pasta do template, usa a classe Layout. 
			} else { $this->objRender = Layout::this(); }
			
			$this->objRender->process();
		//se não usar template chama a classe de processamento de views normal
		} else {		
			$this->objRender = Html::this();
			$this->objRender->produce();
		}
		
		return \_neos::this();
		
	}


	/**
	* Gera a barra de status
	* TODO : Criar o carregamento e compressão de arquivos CSS/JS para incluir os da barra de status.
	*
	* @return string Html para a barra de status
	*/

	function statusBar($extended = false){
		$sb = '<style>#neos_status{margin:0;padding:20px;position:fixed;bottom:0;right:-430px;top:0;z-index:2;font-size:10px;color:#000;background:#DEEFEF;border-left:1px solid #DDD;cursor:pointer;width:400px;overflow:auto}#neos_status *{cursor:default;font-size:10px}#neos_status h5,#neos_status h6{color:#000;font-weight:bold;padding:0;margin:0;text-shadow:none}#neos_status h5{font-size:28px;color:#FFF}#neos_status h6{font-size:16px;text-shadow:2px 2px 3px #AAA;}#neos_status p{font-family:"Lucida Console",Monaco,monospace,"Courier New",Courier,Verdana,Arial;padding:0}#neos_status ul{padding:5px 0 0 0}#neos_status ul li{text-align:left;list-style:none}#neos_status ul li span{float:right;}</style><script>$(document).ready(function(){$("#neos_status").animate({opacity:0.5},0).mouseenter(function(){if($(this).css("right")=="-430px"){$(this).animate({right:0,opacity:1},400)}});$("#neos_status").mouseleave(function(){$(this).animate({right:"-430px",opacity:0.2},400)})})</script><div id="neos_status"><h5>NEOS PHP Framework</h5><h5>modo: "'.NEOS_STATE.'"</h5><p><b>Mem.: '.round((memory_get_usage()/1000), 0).' Kb | Peak: '.round((memory_get_peak_usage()/1000), 0).' Kb | Time: '.number_format((microtime(true) - NEOS_INIT_TIME)*1000,3,',','.').' ms</b></p>';
		if(!$extended) :
			//mostrando os arquivos incluídos
			$ct = 0;
			$cf = 0;
			$sb .= '<h6>Arquivos incluídos</h6><ul>';
			foreach(get_included_files() as $f):
				$fz = filesize($f);
				$sb .= "\n".'<li>'.$f.'<span>'.number_format($fz/1000,3,',','.').' Kb.</span></li>';
				$ct += $fz;
				$cf++;
			endforeach;
			$sb .= '<li><b>Total ('.$cf.' Files )<span>'.number_format($ct/1000,3,',','.').' Kb.</span></b></li></ul>';

			//mostrando os ítens do "push"
			if(count($this->varPush) > 0):
				$sb .= '<h6>Logs</h6><ul>';
				foreach($this->varPush as $p):
					$sb .= '<li>'.$p.'</li>';
				endforeach;
				$sb .= '</ul>';
			endif;
		endif;

		return $sb . '</div>';
	}

	/**
	 * Função (callback) de finalização do boofer de saida do framework
	 * Disparado automaticamente na finalização de todos os scripts
	 *
	 * @param $out 	todo o conteudo gerado durante a execução (echo, print, etc)
	 * @param $md 	parametro da função ob_gzhandler() - nivel de compactação
	 *
	 * @return string envia o conteudo (tratado) para o browser do usuário
	*/
	function outBuffer($out,$md){
		//evitando exibição de erros da classe de Erros
		restore_error_handler();

		//retorna somente o conteudo do buffer - descarta tudo mais
		if(!$this->varBuffer == '') return $this->varBuffer;

		//retorna o conteudo (out) - não processa o restante desta função!
		if(!$this->varBufferOn) return $out;

        //Adicionando a barra de status
		if(strpos(_cfg('status'),'display') !== false) $out = str_replace('</body>', $this->statusBar() . "\n</body>", $out);

		//Comprimindo se necessário (Gzip!)
		if($this->varCompress) return ob_gzhandler($out, 5);
		return $out;
	}

}

