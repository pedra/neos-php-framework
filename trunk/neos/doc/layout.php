<?php
namespace Neos\Doc;
/** 
	* Template para manipulação de documentos HTML.
	* @copyright	NEOS PHP Framework - http://neosphp.org
	* @license		http://neosphp.org/license 
	* @author		Paulo R. B. Rocha - prbr@ymail.com 
	* @version		CAN : B4BC
	* @package		Neos\Doc
	* @access 		public
	* @return		mixed Documento HTML formatado
	* @since		CAN : B4BC
*/

class Layout
	extends Type\Html {
	
	/**
	* Layout padrão usado para a renderização
	*/
	public	$layout	= 'layout';
	
	/**
	* array contendo as views já renderizadas
	*/
	public	$views = array();
	
	/**
	* template
	*/
	public	$template = NULL;
	
	function __construct(){
		//define as constantes URLT e PATH_TEMPLATE usadas neste estágio
		define('URLT', _app('URL') . _cfg()->template_url . '/' . _cfg()->template . '/' );
		define('PATH_TEMPLATE',	_cfg()->template_path . _cfg()->template . DS . 'engine' . DS );
		
		//Grava como uma variável geral para as views
		_viewVar('urlt', URLT);
		
	}
	
	function process(){
		//Renderiza e guarda o resultado num array, propriedade desta classe		
		$this->renderViews();
		
		//Renderiza o layout
		$this->renderLayout();
	}
	
/*	TODO:
	A classe deve analizar o arquivo 'layout.html' do template atual
	Na região 'body' (<neos:area name="body" />) deve carregar todas as views setadas no controller
	Se outras áreas existirem, serão carregadas em seus respectivos lugares
	Toda view deve ser chamada checando-se primeiro o diretório 'views' do template; em seguida a 'views' normal (app/views)
	
	A classe 'NEOS_Views' contém os métodos necessários para a análize das views e 'neosTags engine'.
	
	A classe "Template' do template atual recebe o controle do sistema - se não existir ou não precisar controlar, devolve o controle a esta classe.
	
*/	
	
	//Renderizando todas as views carregadas
	function renderViews() {
		$this->views = $this->produce(true);
	}
	
	//Renderizando o Layout do Template
	function renderLayout($get = false) {
		
		if(file_exists(PATH_TEMPLATE . $this->layout . '.html')){
			$neosarquivo = file_get_contents(PATH_TEMPLATE . $this->layout . '.html');
			
			//Buscando e resolvendo a tag NEOS
			$ret = true; 
			$ponteiro = 0;
			
			while($ret = $this->_pegatag($neosarquivo, $ponteiro)){
				@$ponteiro = 0 + $ret['-final-'];
				$vartemp = '';
				
				//ajustando os dados para o tipo 'variável (var)'
				if (isset(Factory::this()->varViewVar[0][$ret['-tipo-']])){$ret['var'] = $ret['-tipo-']; $ret['-tipo-'] = 'var';}

				//neosTags
				if(isset($ret['-tipo-'])){					
					if ($ret['-tipo-'] == 'url'){ $vartemp = _app('URL'); }
					elseif (isset($ret['var']) && $ret['var'] == 'urlt'){ $vartemp = URLT; }
					elseif ($ret['-tipo-'] == 'charset'){ $vartemp = _cfg()->charset; }
					else {
						//procurando o método (neosTag)
						$vartemp = (method_exists(__CLASS__, '_' . $ret['-tipo-'])) ? 
							$this->{'_' . $ret['-tipo-']}($ret, 0) : 
							$this->_neostag( $ret['-tipo-'], array($ret));
					}
					if($vartemp == false){$vartemp = '';}
				}
				//Incluindo o bloco gerado pelas NeosTags
				$neosarquivo = substr_replace($neosarquivo, $vartemp, $ret['-inicio-'], $ret['-tamanho-']);
				//RE-setando o ponteiro depois de adicionar os dados acima
				$ponteiro = strlen($vartemp) + $ret['-inicio-'];
			}//while
			
			//"Avaliando" o PHP contido no HTML
			if(isset(Factory::this()->varViewVar[0])) extract(Factory::this()->varViewVar[0]);
			
			ob_start();
			eval('?>' . $neosarquivo);
			
			//pegando o conteúdo processado
			$out = trim(ob_get_contents());
			ob_end_clean();
			
			//Retornando...
			if($get){ return $out; } else { echo $out; }
			
		//Caso o arquivo não exista...	
		} else {
			trigger_error('Não encontrei o arquivo de layout!' . _pt($this) . PATH_TEMPLATE . $this->layout . '.html');
			return;
		}
	}
	
	
	function __head($ret){
		
		if(!_app('titulo')) _app('titulo', 'NEOS PHP Framework');
		$css	= '';
		$js		= '';
		
		//Pegando os links CSS
		foreach(Factory::this()->varCss as $k=>$v){
			foreach($v as $k1=>$v1){ $css .= "\n\t" . '<link href="' . $v1 . '" rel="stylesheet" type="text/css" media="' . $k . '" />'; }			
		}
		//Apagando o Array
		Factory::this()->varCss = array();
		
		//Pegando os links JS
		if(isset(Factory::this()->varJs['h'])){
			foreach(Factory::this()->varJs['h'] as $k=>$v){
				foreach($v as $k1=>$v1){ $js .= "\n\t" . '<script type="text/javascript" src="' . $v1 . '"></script>'; }			
			}
		}
		//Apagando o Array
		Factory::this()->varJs['h'] = array();
			
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=' . _cfg('charset') . '" />
	<title>' . _app('titulo') . '</title>
	<meta name="resource-types" content="document"/>
	<meta name="revisit-after" content="1"/>
	<meta name="classification" content="Internet"/>
	<meta name="Description" content="NEOS PHP Framework - simples, rápido e fácil (Brasileiro)."/>
	<meta name="Keywords" content="php,simple,neos,framework,rapido,facil,simples,php5,php6,codeigniter,symfony,smart,phpeclipse,zend,zend2,java,sql,mysql,postgresql,oracle,oci,phpmagazine,javascript,css,XHTML,HTML,tableless,revista,magazine,XML,postgres,cake,jquery,site,programa"/>
	<meta name="robots" content="ALL"/>
	<meta name="distribution" content="Global"/>
	<meta name="ICBM" content="-20.2982,-40.3298" />
	<meta name="DC.title" content="NEOS PHP Framework" />
	<meta name="rating" content="General"/>
	<meta name="author" content="Paulo R. B. Rocha"/>
	<meta name="language" content="pt-br"/>
	<meta name="doc-class" content="Completed"/>
	<meta name="doc-rights" content="Public"/>
	<link href="' . URL . 'favicon.ico" rel="SHORTCUT ICON" />
	' . $css . '
	' . $js . '
</head>';
		
		
	}
	
	function __body(){}
	
	function __footer(){
		$js = '';
		//Pegando os links JS
		if(isset(Factory::this()->varJs['b'])){
			foreach(Factory::this()->varJs['b'] as $k=>$v){
				foreach($v as $k1=>$v1){ $js .= "\n\t" . '<script type="text/javascript" src="' . $v1 . '"></script>'; }			
			}
		}
		
		$app = _app();
		if($app && (_cfg()->jsfw->active==true)){
					$js .= "\n\t" . '<script type="text/javascript">/*<![CDATA[*/';
					foreach($app as $ak=>$av){$js .= "var " . _cfg()->jsfw->prefix . $ak . '="' . $av . '";';}
					$js .= '/*]]>*/</script>' . "\n";
				}
				
		return $js . '
		</body>
		</html>';		
	}
	
}