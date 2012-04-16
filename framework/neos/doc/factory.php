<?php
namespace Neos\Doc;
/**
 * Sub-framework para a Renderização de saída
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
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
	static function push($t, $v){
		self::this()->varPush[][$t]=_pt($v,false);
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
		
		//limpando o buffer...
		if(_cfg()->out_restricted) ob_clean();		
		
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
			$this->objRender = Type\Html::this();
			$this->objRender->produce();
		}	
		return \_neos::this();
		
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

        //Adicionando os links de CSS/JavaScript no HEAD
		$app = _app();
		$head = '<script type="text/javascript">';
		foreach($app as $k=>$v){
			$head .= 'var neos_' . $k . '="' . $v . '";';   	
		}
        $head .= '</script>';
		$head .= (!empty($this->varCss)) ? $this->_addLinkCss() : '';
		$head .= (!empty($this->varJs['h'])) ? $this->_addLinkJsHead() : '';		
			
		$out = str_replace('</head>', $head . "\n</head>", $out);

		//Adicionando a barra de status e links de Javascript antes do BODY
		$body = (!empty($this->varJs['b'])) ? $this->_addLinkJsBody() : '';
		$body .= (strpos(_cfg('status'),'display') !== false) ? $this->statusBar((strpos(_cfg('status'),'extended') !== false) ? true : false) : '';		
		
		if($body != '') $out = str_replace('</body>', $body . "\n</body>", $out);

		//Comprimindo se necessário (Gzip!)
		if($this->varCompress 
			&& isset($_SERVER['HTTP_ACCEPT_ENCODING']) 
			&& strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)
			return ob_gzhandler($out, 5);
		return $out;
	}
	

/** - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
@TODO
	1 - Passar os métodos seguintes para a classe Type/Html
	2 - Sintonizar os métodos outBuffer e produce para trabalhar com os vários tipos de documentos (html, xhtml, html5, pdf, xml, zip, etc)
	
- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

	/**
	* Gera a barra de status
	* TODO : Criar o carregamento e compressão de arquivos CSS/JS para incluir os da barra de status.
	*
	* @return string Html para a barra de status
	*/

	function statusBar($extended = true){
		$sb = ($extended) ? '<script type="text/javascript">var neos_="none";function neostatus(){var dv=document.getElementById("neostatus").style;if(neos_=="none"){neos_="block";dv.top="10px";dv.width="360px"}else{neos_="none";dv.top="";dv.width=""};document.getElementById("neostatustable").style.display=neos_}</script>' : '';
		$sb .= '<style>#neostatus{position:fixed;bottom:10px;right:10px;z-index:200;background:#000 url(http://neosphp.org/img/slg.png) 2px 2px no-repeat;background-color:rgba(0,0,0,0.7);cursor:pointer;font-size:10px;color:#FFF;font-family:Helvetica,Tahoma,monospace,\'Courier New\',Courier,serif;margin:0;padding:2px 10px 2px 25px;border:2px solid #FFF;border-radius:10px;box-shadow:0 0 80px #555;text-align:right;overflow:auto}'.(($extended)?'#neostatustable{display:none;width:350px;margin:0;padding:0}#neostatustable tr td{background:transparent !important;padding:0;margin:0}#neostatustable tr th{font-size:12px;font-weight:bold}#neostatustable pre{white-space:pre-wrap}.neostatuslg td{border-bottom:1px dashed #999}.xright{text-align:right}':'').'</style><div id="neostatus"'.(($extended)?' onClick="neostatus()"':'').'>';		
		if($extended){
			$sb .= '<table id="neostatustable" title="click para esconder!"><tr><th colspan="2">NEOS PHP Framework - ver. '.NEOS_CAN.'</th></tr><tr><th colspan="2">Arquivos Incluidos</th></tr>';
			$ct = $cf = 0;
			foreach(get_included_files() as $f){
					$fz = filesize($f);
					$sb .= '<tr><td>'.$f.'</td><td class="xright">'.number_format($fz/1000,2,',','.').'&nbsp;kb</td></tr>';
					$ct += $fz;$cf++;
			}
			$sb .= '<tr><td><b>Total</b> ('.$cf.' arquivos )</td><td>'.number_format($ct/1000,2,',','.').'&nbsp;kb</td></tr>';
			//mostrando os ítens do "push"
			if(count($this->varPush) > 0){
				$sb .= '<tr><th>Mark</th><th>&nbsp;</th></tr>';
				foreach($this->varPush as $x){foreach($x as $k=>$v){$sb .= '<tr class="neostatuslg"><td>'.$v.'</td><td>'.$k.' ms</td></tr>';}}
			}
			$sb .= '</table>';			
		}
		return $sb.number_format(round(((memory_get_usage()+memory_get_peak_usage())/2000),0),0,',','.').' kb | '.number_format((microtime(true)-NEOS_INIT_TIME)*1000,0,',','.').' ms</div>';
	}


	
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	
	/**
	 * Adiciona as tags de link JavaScript à aplicação - região do HEAD.
	 *
	 * @return String Os links JavaScript em formato html
	*/	
	static function _addLinkJsHead() { 
		//Pegando o array varCss	
		$js = &Factory::this()->varJs;
		$out = '';
		
		//Pegando os links JS
		if(isset($js['h'])){
			foreach($js['h'] as $k=>$v){
				foreach($v as $k1=>$v1){ $out .= "\n\t" . '<script type="text/javascript" src="' . $v1 . '"></script>'; }			
			}
		}
		return $out;
	}

	/**
	 * Adiciona as tags de link JavaScript à aplicação - região do BODY (default).
	 *
	 * @return String Os links JavaScript em formato html
	*/	
	static function _addLinkJsBody() { 
		//Pegando o array varCss	
		$js = &Factory::this()->varJs;
		$out = '';
		
		//Pegando os links JS
		if(isset($js['b'])){
			foreach($js['b'] as $k=>$v){
				foreach($v as $k1=>$v1){ $out .= "\n\t" . '<script type="text/javascript" src="' . $v1 . '"></script>'; }			
			}
		}
		return $out;
	}

	/**
	 * Adiciona as tags de link CSS à aplicação.
	 *
	 * @return String Os links CSS em formato html
	*/	
	static function _addLinkCss() { 
		//Pegando o array varCss	
		$css = &Factory::this()->varCss;
		$out = '';
		foreach($css as $media=>$t){
			foreach($t as $url){
				$out .= "\n\t" . '<link href="' . $url . '" media="' . $media . '" rel="stylesheet" type="text/css" />'; 
			}
		}
		return $out;
	}
	
	/**
	 * Adiciona um arquivo Javascript à Aplicação.
	 *
	 * @param string $f Nome do arquivo localizado na pasta configurada em "$cfg->app->pathJs".
	 * @param string $g Grupo - Cria um arquivo único para cada grupo (all, editor, etc - "link" cria um link individual).
	 * @param string $b Body - se TRUE o link será criado antes do fechamento da tag "body". Caso contrário será na tag "head".
	 * @return void
	*/	
	static function _addJs($f, $g = 'all', $b = true, $url = NULL) {
		//Pegando o array varJs	
		$js = &Factory::this()->varJs;
		$f = ($url == NULL) ? URL . _cfg('urlJs') . $f : $url . $f;
		
		//Conformando o indicador da tag de destino
		$b = ($b == true) ? 'b' : 'h';
		
		//Se já existir ignora.		
		if (isset($js[$b][$g]) && in_array($f, $js[$b][$g])) return false;
		
		//Gravando os valores...
		$js[$b][$g][] = $f;
	}
	
	/**
	 * Adiciona um arquivo CSS à Aplicação.
	 *
	 * @param string $f Nome do arquivo localizado na pasta configurada em "$cfg->app->pathCss".
	 * @param string $m Media - Cria um arquivo único para cada media (all, print, tv, etc - "link" cria um link individual). 
	 * @return void
	*/
	static function _addCss($f, $m='all', $url = NULL) { 
		//Pegando o array varCss
		$css = &Factory::this()->varCss;
		$f = ($url == NULL) ? URL . _cfg('urlCss') . $f : $url . $f;
		
		//Se já existir ignora.
		if (isset($css[$m]) && in_array($f, $css[$m])) return false;
		
		//Gravando os valores...
		$css[$m][] = $f;
	}

}

