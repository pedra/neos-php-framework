<?php

class Admin
	extends Base{
		
	function __construct(){ //verificando se tem acesso ao ADMIN
		$_SESSION['level'] = 2; //Desenvolvimento  - DELETAR
		if(!isset($_SESSION['level']) || $_SESSION['level'] < 2 ) _go();
	}
		
	function novo($item = null){
		if(isset($_POST['preview'])){
	
	//$conteudo = highlight_string($t, true);

		//if(isset($_POST['salvar'])) $conteudo = strip_tags(str_replace('<?', '&lt;?',$_POST['conteudo']), '<p><a><h2><h3><h4><h5><b><pre><code><blockquote><img><iframe>');
		$cont = str_replace('<?', '&lt;?',$_POST['conteudo']);
		
		//$cont = $this->scaner($cont);
		
		if(isset($_POST['preview'])) $conteudo = htmlentities($_POST['conteudo'], ENT_QUOTES, "UTF-8");
		
		//_p($conteudo);
		$conteudo = addslashes($conteudo);//para salvar no banco de dados
		
	} elseif(isset($_POST['cancelar'])){
		_go();
		
	} else {
		$cont = '';
				$conteudo = '<pre><?php
namespace Lib\Db;
		
class Admin
	extends Base{
		
	function __construct(){ //verificando se tem acesso ao ADMIN
		$_SESSION["level"] = 2; //Desenvolvimento  - DELETAR
		if(!isset($_SESSION["level"]) || $_SESSION["level"] < 2 ) _go();
	}
		
	function novo($item = null){
		$conteudo = "Nenhum conteúdo précarregado!";
		if(isset($_POST["salvar"])) _p(htmlentities(str_replace("dt","code",$_POST["conteudo"])));
		if(isset($_POST["salvar"])) $conteudo = str_replace("dt","code",$_POST["conteudo"]);
		
			_view::val("data", date("d/m/Y"));
			_view::val("conteudo", $conteudo);
			_view::set("blog/admin/novo/artigo");
	}</pre>';
	}
	
	

			_view::val('data', date('d/m/Y'));
			_view::val('cont', '<pre>'.$cont.'</pre>');
			_view::val('conteudo', stripslashes($conteudo));
			_view::set('blog/admin/novo/artigo');
	}
		

		private function scaner($code){
			$xcode = '';
			$tamanho = strlen($code);
			$ponteiro = 0;
			
			while($ponteiro < ($tamanho - 8)){
				$a = $this->xcode($code, $ponteiro);
				
				if(is_array($a)){
					if($a['i'] > $ponteiro && $ponteiro < 1) $xcode = substr($code, $ponteiro, ($a['i'] - $ponteiro) -1);
					$xcode .= $a['code'];
					$ponteiro = $a['f'];					
				}											
			}
			if($ponteiro < $tamanho) $xcode .= substr($code, $ponteiro, ($tamanho - $ponteiro));
			return $xcode;
		}
		
		private function xcode($code, $ponteiro){	
			
			$ini = strpos($code, '[[code]]', $ponteiro);
			if($ini === false) return 'inicio';				
			
			$fim = strpos($code, '[[/code]]', $ini);
			if($fim === false) return 'fim';
			
			//se tudo estiver ok....
			$string = substr($code, ($ini + 8), (($fim - $ini) - 9));
			
			$a = explode("\r", $string);
			$s = '<pre>';
			foreach($a as $k=>$v){
				if($k > 9 && $k < 100) $k = '0'.$k;
				elseif($k < 10) $k = '00'.$k;
				
				$s .= '<span>'.$k.'</span>'.str_replace("\r", '', str_replace("\n",'',$v))."\r";				
			}
			return array('code'=>$s.'</pre>', 'i'=>$ini, 'f'=>$fim);
			
		}
		
		
		
		
		
		
}