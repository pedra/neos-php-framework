<?php
	/**
	 * Funções globais de apoio. Estas funções podem ser chamadas de qualquer lugar do sistema - são globais :P.
	 * @copyright	NEOS PHP Framework - http://neosphp.com
	 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
	 * @author		Paulo R. B. Rocha - prbr@ymail.com
	 * @version		CAN : B4BC
	 * @package		Neos\Helper
	 * @subpackage	Core
	 * @access 		public
	 * @since		CAN : B4BC
	 */

	/**
	 * Redireciona para uma nova localização ('vai para...').
	 * Esta ação depende também da configuração do browser - browsers modernos redirecionam :P.
	 *
	 * @param string $uri Caminho interno (a partir da url base do site) ou completo (depende de '$externo') - default: página inicial.
	 * @param string $metodo Tipo de redirecionamento: 'reflesh' ou 'location'.
	 * @param numeric $cod Código do redirecionamento.
	 * @param bool $externo True habilita o redirecionamento para outro site (externo)
	 * @return void 
	*/
	function _goto($uri = '', $metodo = '', $cod = 302, $externo = false) {
	   if($externo == false) $uri = URL . $uri;
		if (strtolower($metodo) == 'refresh') {header('Refresh:0;url=' . $uri);}
		else {header('Location: ' . $uri, TRUE, $cod);}
		exit;
	}

	/**
	 * Imprime na tela como a função 'print_r' do PHP com tags '<pre>' do html.
	 * Isso garante um resultado gráfico mais elegante - principalmente para depuração de arrays/objetos.
	 *
	 * @param mixed $v (valor) Pode ser uma string, número, objeto ou array a serem mostrados.
	 * @param bool $ec (echo) True mostra imediatamente na tela; False retorna o conteúdo printavel (inversamente ao mesmo parâmetro da função print_r)
	 * @param bool $t (tabela) Mostra o resultado em uma tabela levemente estilizada.
	 *
	 * @return string|boll
	*/
	function _pt($v, $ec = true, $t = false) {
		if($t == false) $x = '<pre>' . print_r($v, true) . '</pre>';
		if($t == true){
			$x = '<style>table{background:#FFF;padding:10px}td,th{border:1px solid #EEE; padding:3px 10px}</style><table border="0" cellpadding="2" cellspacing="3"><tr><th>INDICE</th><th>VALOR</th></tr>';
			if(is_array($v) || is_object($v)){
				foreach ($v as $k => $v) {
					if (is_array($v) || is_object($v)) $v = '<pre>' . print_r($v, true) . '</pre>';
					$x.='<tr><td><b>' . $k . '</b></td><td>' . $v . '</td></tr>';
				}
			}
		else {$x.='<tr><td> </td><td>' . $v . '</td></tr>';}
		$x.='</table>
	';
		}
		if($ec){echo $x;}
		else{return $x;}
	}	
	
	/**
	 * Retorna a instância da classe User (singleton).
	 *
	 * @return object
	*/
	function _user(){
		return \Neos\Library\User::this();
	}
	
	/**
	 * Carrega a classe indicada da Library e retorna sua referencia.
	 * Retorna a instância (singleton) da classe indicada.
	 *
	 * @param string $l Nome da classe a ser carregada.
	 * @return object
	*/
	function _lib($l){
		$l = '\\Neos\\Library\\' . ucfirst(strtolower($l));
		return $l::this();
	}
	
	/**
	 * Carrega um model da Aplicação.
	 * Retorna a instância (singleton) da classe indicada.
	 *
	 * @param string $m Nome da classe a ser carregada.
	 * @return object
	*/
	function _model($m){
		$m = '\\Model_' . ucfirst(strtolower($m));
		return $m::this();
	}
	
	/**
	 * Referencia a classe Model ( Neos\Db\Model - alias '_db').
	 * Usado para conexão com banco de dados e realização de transações de dados.
	 * Retorna a instância do driver indicado em '$alias'.
	 *
	 * @param string $alias Apelido da configuração de banco de dado previamente configurada ou a default.
	 * @return object 
	*/
	function _db($alias = NULL){
		if($alias == NULL) return \_db::this();
		return \_db::this()->connect($alias);
	}	
	
	/**
	 * Setando uma View
	 *
	 * @param string $file nome do arquivo contendo a view
	 * @param array $dt variáveis para a view
	 * @param string $name nome de referencia para a view
	 *
	 * @return void
	*/
	function _view($file, $dt = '', $name = ''){
		\_view::set($file, $dt, $name);	
	}
	
	/**
	 * Carregando uma variável para as views.
	 * O conteudo da variável será armazenada para a renderização da view.
	 *
	 * @param string $var nome da variável
	 * @param mixed $val valor da variável
	 * @param string $view nome da view a que pertence
	 * @return void
	*/
	function _viewVar($var, $val, $view = NULL){
		\_view::value($var, $val, $view);	
	}
	
	/**
	 * Retorna a instância da classe Cfg (singleton).
	 *
	 * @param string $item Nome de um objeto da classe Config.
	 * @return object
	*/
	function _cfg($item = NULL){
		return \_cfg::this()->cfg($item);	
	}
	
	/**
	 * Seta uma entrada na Listagem de "bookmarks"
	 *
	 * @param string $v Valor a ser mostrado (string)
	 * @return void
	*/
	function _setmark($v){
		return \_view::push($str);
	}
	
	/**
	 * Seta ou recupera um dado (mixed) da "app".
	 * 'Banco de Variáveis' visível em toda a aplicação para a troca de dados.
	 * Retorna o valor (mixed) gravado.
	 *
	 * @param string $var Nome com o qual o dado (mixed) será gravado ou recuperado.
	 * @param mixed $val Valor a ser gravado - se não for indicado a função entende que se trata de recuperação do valor indicado em $var.
	 * @return mixed
	*/
	function &_app($var = NULL, $val = NULL){
		if($var == NULL) return \_neos::this()->varVars;
		if($val != NULL) \_neos::this()->varVars[$var] = $val;
		return \_neos::this()->varVars[$var];
	}
	
	/**
	 * Adiciona um arquivo Javascript à Aplicação.
	 *
	 * @param string $f Nome do arquivo localizado na pasta configurada em "$cfg->app->pathJs".
	 * @param string $g Grupo - Cria um arquivo único para cada grupo (all, editor, etc - "link" cria um link individual).
	 * @param string $b Body - se TRUE o link será criado antes do fechamento da tag "body". Caso contrário será na tag "head".
	 * @return void
	*/	
	function _addJs($f, $g = 'all', $b = true, $url = NULL) {
		//Pegando o array varJs	
		$js = &_view::this()->varJs;
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
	function _addCss($f, $m='all', $url = NULL) {
		//Pegando o array varCss
		$css = &_view::this()->varCss;
		$f = ($url == NULL) ? URL . _cfg('urlCss') . $f : $url . $f;
		
		//Se já existir ignora.
		if (isset($css[$m]) && in_array($f, $css[$m])) return false;
		
		//Gravando os valores...
		$css[$m][] = $f;
	}
	
	/**
	 * Chamando um helper do tipo NEOSTAG (para views)
	 * O nome da função será prefixada com 'neostag_'
	 *
	 * @param string $function Nome da função (neostag).
	 * @param string $params Parametros da função. 
	 * @return mixed
	*/
	function _neostag($function, $params){ 
		$function = 'neostag_' . $function;
		//para acelerar: se a função já tiver sido carregada...
		if (function_exists($function)) return call_user_func_array($function, $params);
		//descobrindo o subpath - se existir
		$file = trim(str_replace('_', '/', $function), '/ ');
		if(file_exists( APP_HELPER . $file . EXTHLP )) { include_once APP_HELPER . $file . EXTHLP;
		} elseif (file_exists( PATH_NEOS . 'helper' . DS . $file . EXTHLP )) { include_once PATH_NEOS . 'helper' . DS . $file . EXTHLP;
		} else { return false; }
		return call_user_func_array($function, $params);		
	}
	
	/**
	 * Chamando um helper.
	 *
	 * @param string $helper Nome da função.
	 * @param string $params Parametros da função. 
	 * @return mixed
	*/
	
	//alias para helper
	function _hlp($helper, $params) {return _helper($helper, $params);}
	function _helper($helper, $params){
		//para acelerar: se a função já tiver sido carregada...
		if (function_exists($helper)) return call_user_func_array($helper, $params);
		//descobrindo o path - se existir
		$file = trim(str_replace('_', '/', $helper), '/ ');
		if(file_exists( APP_HELPER . $file . EXTHLP )) { include_once APP_HELPER . $file . EXTHLP;
		} elseif (file_exists( PATH_NEOS . 'helper' . DS . $file . EXTHLP )) { include_once PATH_NEOS . 'helper' . DS . $file . EXTHLP;
		} else { 
			_cfg()->error['cod'] = 4;
			_cfg()->error['function'] = $helper;
			trigger_error('Helper "' . $helper . '" não encontrado!');
			return false;
		}
		return call_user_func_array($helper, $params);	
	}	
	
	
	
	
	
	//TODO : criar estas funções! ---------------------------------------------------------------------------------	
	
	

	//Ajax output
	function _ajax($d, $t='') {
		
		_cfg()->out_filter = false;
		_cfg()->out_compress = false;
		
		ob_clean();
		
		$t = strtolower(trim($t));
		
		if ($t == 'json') exit(json_encode($d));
		if ($t == 'script') exit('<script type="application/javascript">' . (string) $d . '</script>');
		if (is_array($d)) $d = print_r($d, true);
		
		exit($d);
	}
		
	
	//acesso a classe Neos\Doc\Factory - ex,: _docFactory()->produce('html);
	function _docFactory(){}
		
	//Pega uma tradução num arquivo do NEMESPACE atual + $file ou da pasta "language" (geral) 
	function _lang($strID, $lang = 'pt-BR', $file = null){}
	
	//alias para a classe Neos\Doc\Factory (Document Generator)
	function _doc($type = 'html'){}
	
	//acesso aos 'ASSETS' da aplicação - como em: _www('js')->include('jquery'); --- inclue um 'link' para jquery no 'head' da página
	//TODO : criar uma classe para ASSETS - responsável por organizar os recuros de js, css, icones, flash, outros...
	function _www(){}
	

	
	
	//TODO : precisa ser atualizada para o NEOS 2
    //carrega MODULO
    function _modulo($m, $p=array()) {
        $m = ucfirst(strtolower(trim($m)));
        if ($a = _obj('module/' . $m, true)) {
            return $a->get($p);
        }if (file_exists(APP_MODULE . strtolower($m) . SEP . 'index' . EXTMOD)) {
            require_once(APP_MODULE . strtolower($m) . SEP . 'index' . EXTMOD);
            global $_neos_objects;
            $_neos_objects['module/' . $m] = new $m($p);
            return _obj('module/' . $m)->get($p);
        }_obj()->error['cod'] = 6;
        trigger_error('Módulo "' . $m . '" não encontrado.');
        return false;
    }

//Fim...