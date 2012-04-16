<?php
namespace Neos\Doc;
/**
 * Renderização de Views HTML
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Neos\Doc
 * @access 		public
 * @since		CAN : B4BC
 */

class Html
	extends \NEOS {

	/**
	 * Renderiza todas as views indicadas em Doc\Factory::$varView.
	 * Retorna um array com o produto da renderização ou 'ecoa' o resultado.
	 *
	 * @param bool $get	Retorna o produto da renderização para um pós-tratamento
	 * @return array|void
	*/
	function produce($get = false){
		if(isset(Factory::this()->varViewVar[0])){extract(Factory::this()->varViewVar[0]);}
		$out = array();
		foreach(Factory::this()->varViews as $nomeView=>$valView){
			$varq = APP_VIEW  . Factory::this()->varType . DS . $valView . EXTVW;
			
			//pegando a view do template - se existir
			if(_cfg()->template != ''){
				if( file_exists(_cfg()->template_path . _cfg()->template . DS . 'engine' . DS . 'views' . DS . $valView . EXTVW)) {
					$varq = _cfg()->template_path . _cfg()->template . DS . 'engine' . DS . 'views' . DS . $valView . EXTVW;
				}
			}
			if(!file_exists($varq)) trigger_error('<b>Não consegui localizar o arquivo da VIEW solicitada!</b> <br />Local : ' . $varq);
			$neosarquivo = file_get_contents($varq);
			$ret = true;
			$ponteiro = 0;
			while($ret = $this->_pegatag($neosarquivo, $ponteiro)){
				@$ponteiro = 0 + $ret['-final-'];
				$vartemp = '';
				
				if(isset(Factory::this()->varViewVar[0][$ret['-tipo-']])){$ret['var'] = $ret['-tipo-']; $ret['-tipo-'] = 'var';}
				if(is_string($nomeView) && isset(Factory::this()->varViewVar[$nomeView][$ret['-tipo-']])){$ret['var'] = $ret['-tipo-']; $ret['-tipo-'] = 'var';}

				if (isset($ret['-tipo-'])){
					if ($ret['-tipo-'] == 'url'){$vartemp = _app('URL');}
					elseif ($ret['-tipo-'] == 'charset'){$vartemp = _cfg()->charset; }
					else {
						if (method_exists(__CLASS__, '_' . $ret['-tipo-'])) $vartemp = $this->{'_' . $ret['-tipo-']}($ret, $nomeView);
						else $vartemp = $this->_neostag($ret['-tipo-'], array($ret));
					}
					if ($vartemp == false){$vartemp = '';}
				}
				//Incluindo o bloco gerado pelas NeosTags
				$neosarquivo = substr_replace($neosarquivo, $vartemp, $ret['-inicio-'], $ret['-tamanho-']);
				//RE-setando o ponteiro depois de adicionar os dados acima
				$ponteiro = strlen($vartemp) + $ret['-inicio-'];
			}//while
			
			//"Avaliando" o PHP contido no HTML
			if(isset(Factory::this()->varViewVar[$nomeView])) extract(Factory::this()->varViewVar[$nomeView]);
			ob_start();
			eval('?>' . $neosarquivo);
			
			//pegando o conteúdo processado
			$out[] = ob_get_contents();
			ob_end_clean();
		}//foreach

		if($get){ return $out; } else { echo implode($out); }
	}

	/**
	 * NEOSTAG :: Scaner para neosTags.
	 * Retorna, assim que encontra, um array contendo diversos dados sobre a varredura.
	 *
	 * @param string $arquivo	Conteúdo do arquivo a ser 'scaneado'
	 * @param integer $ponteiro	Ponto de inicio da varredura
	 * @param string $type		SubPath
	 * @param string $xpath	Nome da tag a procurar
	 * @return array
	*/
	function _pegatag(&$arquivo, $ponteiro = 0, $type = '', $xpath = 'neos'){
		if($xpath != ''){$xpath .= ':';}
		$tag = $xpath . $type;
		$inicio = strpos($arquivo, '<' . $tag . '', $ponteiro);
		if($inicio !== false){
			//pegando o tipo (< neos:tipo ... )
			$x = substr($arquivo, $inicio, 25);
			//if(preg_match('/'.$tag.'\w+/', $x, $m) == 0){preg_match('/'.$tag.'\w+/', $x, $m);}
			preg_match('/' . $tag . '\w+/', $x, $m);
			if(isset($m[0])){$ntag = trim($m[0], '\/ ');} else {return false;}
			$f1 = strpos($arquivo, '/>', $inicio);
			$f2 = strpos($arquivo, '</' . $ntag . '>', $inicio);
			//calculando...
			$f1 = ($f1 === false) ? 9999999999 : $f1 + 2;
			$f2 = ($f2 === false) ? 9999999999 : $f2 + (strlen($ntag) + 3);
			$final = ($f1 < $f2) ? $f1 : $f2;
			if($final == 9999999999) return false;
			$tamanho = ($final - $inicio);
			//repassando a dimensão do bloco
			$a['-inicio-'] = $inicio;
			$a['-tamanho-'] = $tamanho;
			$a['-final-'] = $final;
			$a['-tipo-'] = str_replace($tag, '', $ntag);
			//pegando o trecho...
			$xml = substr($arquivo, $inicio, $tamanho);
			//tirando o <neos:tipo, tabulação, etc
			$xml = str_replace(array('/>', '<' . $ntag . ' ', '</' . $ntag . '>'), array(' >'), str_replace(array("\n", "\r", "\t"), ' ', $xml));
			//pegando o conteúdo
			$temp = explode('>', $xml);
			$a['conteudo'] = end($temp);
			//tags sem valor
			$temp[0] = str_replace(array('= "', '=" ', ' multiple', ' disabled'),array('="', '="', ' multiple="true" ', ' disabled="true" '), $temp[0]);
			//separando os atributos
			$atributos = explode('" ', $temp[0]);
			foreach($atributos as $v){
				$t = explode('=', str_replace(array('\'', '"'), '', $v));
				if(isset($t[1])) {$a[trim($t[0])] = trim($t[1]);}
			}
			return $a;
		}
		return false;
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
		$function = '_neostag' . $function;
		//para acelerar: se a função já tiver sido carregada...
		if (function_exists($function)) return call_user_func_array($function, $params);
		//descobrindo o subpath - se existir
		$file = trim(str_replace('_', '/', $function), '/ ');
		if(file_exists( APP_HELPER . $file . EXTHLP )) { include_once APP_HELPER . $file . EXTHLP;
		} elseif (file_exists( PATH_NEOS . 'neos/helper' . DS . $file . EXTHLP )) { include_once PATH_NEOS . 'neos/helper' . DS . $file . EXTHLP;
		} else { return false; }
		return call_user_func_array($function, $params);		
	}
	

	/**
	 * NEOSTAG :: Insere um elemento "list" numerado (<ol...)
	 *
	 * @param array $ret dados da neosTag
	 * @param string $nomeView Nome da view atual
	 * @return string|html
	*/
	function _numlist($ret, $nomeView){
		$vartemp = '';
		if(isset($ret['var'])){
			if(isset(Factory::this()->varViewVar[0][trim($ret['var'])])){$v=Factory::this()->varViewVar[0][trim($ret['var'])];}
			else{$v='';}
			if(is_string($nomeView)&&Factory::this()->varViewVar[$nomeView][trim($ret['var'])]!=''){$v=Factory::this()->varViewVar[$nomeView][trim($ret['var'])];}
			unset($ret['var'],$ret['-inicio-'],$ret['-tamanho-'],$ret['-final-'],$ret['-tipo-'],$ret['conteudo']);
			if($v!='' && is_array($v)){
				$ul='';
				foreach($ret as $key=>$value){
					if($ul==''){$ul='<ol';}
					$ul.=' '.trim($key).'="'.trim($value).'"';
					unset($ret[$key]);
				}
				if($ul!=''){$vartemp=$ul.=">\n";}
				else{$vartemp="<ol>\n";}
				foreach($v as $vl=>$x){
					if(!is_numeric($vl)){$vartemp.='<li><a href="'._app('URL').$vl.'">'.$x."</a></li>\n";}
					else{$vartemp.="<li>$x</li>\n";}
				}
				$vartemp.="</ol>\n";
			}
		}
        return $vartemp;
	}
	
	/**
	 * NEOSTAG :: Insere um elemento "list" (<ul...)
	 *
	 * @param array $ret dados da neosTag
	 * @param string $nomeView Nome da view atual
	 * @return string|html
	*/
	function _list($ret, $nomeView){
		$vartemp = '';
		if(isset($ret['var'])){
			if(isset(Factory::this()->varViewVar[0][trim($ret['var'])])){$v=Factory::this()->varViewVar[0][trim($ret['var'])];}
			else{$v='';}
			if(is_string($nomeView)&&Factory::this()->varViewVar[$nomeView][trim($ret['var'])]!=''){$v=Factory::this()->varViewVar[$nomeView][trim($ret['var'])];}
			unset($ret['var'],$ret['-inicio-'],$ret['-tamanho-'],$ret['-final-'],$ret['-tipo-'],$ret['conteudo']);
			if($v!='' && is_array($v)){
				$ul='';
				foreach($ret as $key=>$value){
					if($ul==''){$ul='<ul';}
					$ul.=' '.trim($key).'="'.trim($value).'"';
					unset($ret[$key]);
				}
				if($ul!=''){$vartemp=$ul.=">\n";}
				else{$vartemp="<ul>\n";}
				foreach($v as $vl=>$x){
					if(!is_numeric($vl)){$vartemp.='<li><a href="'._app('URL').$vl.'">'.$x."</a></li>\n";}
					else{$vartemp.="<li>$x</li>\n";}
				}
				$vartemp.="</ul>\n";
			}
		}
        return $vartemp;
	}
	
	/**
	 * NEOSTAG :: Insere um elemento "select" 
	 *
	 * @param array $ret dados da neosTag
	 * @param string $nomeView Nome da view atual
	 * @return string|html
	*/
	function _select($ret, $nomeView){
		$vartemp = '';
		if(isset($ret['var'])){
			if(isset(Factory::this()->varViewVar[0][trim($ret['var'])])){$v=Factory::this()->varViewVar[0][trim($ret['var'])];}
			else{$v='';}
			if(is_string($nomeView)&&Factory::this()->varViewVar[$nomeView][trim($ret['var'])]!=''){$v=Factory::this()->varViewVar[$nomeView][trim($ret['var'])];}
			unset($ret['var'],$ret['-inicio-'],$ret['-tamanho-'],$ret['-final-'],$ret['-tipo-'],$ret['conteudo']);
			if($v!=''){
				$ul='';
				foreach($ret as $key=>$value){
					if($ul==''){$ul='<select';}
					if(trim($key)=='multiple'){$ul.=' '.trim($key);}else{$ul.=' '.trim($key).'="'.trim($value).'"';}
					unset($ret[$key]);
				}
				if($ul!=''){$vartemp=$ul.=">\n";}
				else{$vartemp="<select>\n";}
				foreach($v as $k=>$vl){
					$vartemp.='<option value="'.$k.'" ';
					if(is_array($vl)){$vartemp.=' selected="selected" >'.$vl[0]."</option>\n";}
					else{$vartemp.='>'.$vl."</option>\n";}
				}
				$vartemp.="</select>\n";
			}
		}
		return $vartemp;
	}
	
	/**
	 * NEOSTAG :: Carregando um arquivo JavaScript
	 *
	 * @param array $ret dados do arquivo JS vindos da neosTag
	 * @return boll
	*/
	function __script($ret){
		//Se não for indicado 'href' ...
		if(!isset($ret['href'])) return false;
		
		$t = 'b';
		$m = 'all';
		$url = URL . _cfg('urlJs');
		
		if(isset($ret['href']))		$f = $ret['href'];
		if(isset($ret['group']))	$m = $ret['group'];
		if(isset($ret['tag']))		$t = $ret['tag'];
		
		//Atributo 'rel' (relativo a...)
		if(isset($ret['rel'])) $url = ( strpos($ret['rel'], 'template') !== false ) ? URL . _cfg('template_url') . _cfg()->template . '/' : $url;
		
		//Conformando a TAG de destino
		$t = (strpos(strtolower($t), 'b') === false) ? 'h' : 'b';
		
		//Adicionando o arquivo
		Factory::_addjs($f, $m, $t, $url);
		
		return '';
	}
	
	/**
	 * NEOSTAG :: Carregando um arquivo CSS (na tag head)
	 *
	 * @param array $ret dados do arquivo CSS vindos da neosTag
	 * @return boll
	*/
	function __style($ret){		
		//Se não for indicado 'href' ...
		if(!isset($ret['href'])) return false;
		
		$m = 'all';
		$url = URL . _cfg('urlCss');
		
		if(isset($ret['media'])) $m = $ret['media'];
		if(isset($ret['rel'])) $url = ( strpos($ret['rel'], 'template') !== false ) ? URL . _cfg('template_url') . '/' . _cfg()->template . '/' : $url;

		//Adicionando o arquivo
		Factory::_addCss($ret['href'], $m, $url);
		
		return '';
	}

	/**
	 * NEOSTAG :: Carrega uma view
	 *
	 * @param array $ret dados da neosTag
	 * @return string|html
	*/
	function _view($ret, $nomeView){
		if(isset($ret['name'])){
			if(file_exists(APP_VIEW . $ret['name'].'.html')){
				return file_get_contents(APP_VIEW .$ret['name'].'.html');
			}
		}
	}
	
	/**
	 * NEOSTAG :: Insere o valor de uma variável (viewVar)
	 *
	 * @param array $ret dados da neosTag
	 * @param string $nomeView Nome da view atual
	 * @return string
	*/
	function _var($ret, $nomeView){
		$temp='';
		$ret['var'] = trim($ret['var']);
		
		if(isset(Factory::this()->varViewVar[0][$ret['var']])) { $v = Factory::this()->varViewVar[0][$ret['var']];}
		else { $v = '';}

		if( is_string($nomeView) 
			&& isset(Factory::this()->varViewVar[$nomeView][$ret['var']])
			&& Factory::this()->varViewVar[$nomeView][$ret['var']] != '') 
			$v = Factory::this()->varViewVar[$nomeView][$ret['var']];
        
		unset($ret['var'],$ret['-inicio-'],$ret['-tamanho-'],$ret['-final-'],$ret['-tipo-'],$ret['conteudo']);

		if($v!=''){
			$d='';
			foreach($ret as $k=>$vl){
				if($d == ''){ $d = '<div'; }
				$d .= ' ' . trim($k) . '="' . trim($vl) . '"';
				unset($ret[$k]);
			}
			if($d!=''){ $temp = $d .= '>' . $v . '</div>'; } else { $temp = $v; }
		}
	return $temp;
	}
	
	/**
	 * NEOSTAG :: Carrega um Módulo e retorna o resultado
	 *
	 * @param array $r dados da neosTag
	 * @param string $nomeView Nome da view atual
	 * @return string|html
	*/
	final function _modulo($r, $nomeView){
		if(isset($r['name'])){ 
			$n = $r['name']; 
			unset($r['name']); 
		} else { $n = ''; }
	
		return call_user_func(array('\Module_' . ucfirst($n). '_Module', 'this'))->get($r); 
	}
	
	/**
	 * NEOSTAG :: Carrega as configurações do cabeçalho html
	 *
	 * @return void
	*/
	function _includeHtml(){if(!isset($this->_html)){$this->_html = include (PATH_NEOS . 'Config/html.php');}}
	
	/**
	 * NEOSTAG :: Insere o conteúdo de uma "area" do Layout (head/body/footer/etc)
	 *
	 * @param array $r dados da neosTag
	 * @param string $nomeView Nome da view atual
	 * @return string|html
	*/
	function __area($r, $nomeView){
		if(isset($r['name'])){
			if($r['name']=='head'){
				$this->_includeHtml();
				if(is_array($this->_html)){
					return $this->_html['doctypes'][\_cfg::cfg()->html->doctype] ."\n"
							.$this->_html['htmltypes'][\_cfg::cfg()->html->type] ."\n"
							.'<head>'."\n"
							.'<meta http-equiv="Content-Type" content="text/html; charset='.\_cfg::cfg()->charset.'" />'."\n"
							.'<title>'._app('title').'</title>'."\n"
							.'</head>';
				}
			}
			if($r['name']=='body'){
				return implode($this->views);
				//return '<body><address>Este é o Body (corpo) da página</address>';
			}
			if($r['name']=='footer'){return '<address>Este é o Footer (rodapé) da página</address></body></html>';}
		}
	}
}