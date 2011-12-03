<?php if(!defined('URL')) exit;
class NFTemplate {
	
	public $mod;
	public $layout;
	
	function __construct(){
		global $cfg;
		$this->layout='layout';
		if(file_exists($cfg->module.SEP.'config.php')){
			include $cfg->module.SEP.'config.php';
		}else{trigger_error('Não encontrei as configurações dos módulos!');return;}
		$this->mod=$module;
	}	
		
	function get_layout(){
		global $cfg;
		if(file_exists($cfg->template_path.SEP.$cfg->default->template.SEP.$this->layout.'.html')){
			$arquivo = file_get_contents($cfg->template_path.SEP.$cfg->default->template.SEP.$this->layout.'.html');
			//setando as tags URL - url do site
			$ret = true; $ponteiro = 0;			
			while($ret=_pegatag($arquivo,$ponteiro,'','url')){$arquivo=substr_replace($arquivo,URL,$ret['inicio'],$ret['tamanho']);$ponteiro=$ret['final'];}
			//setando as tags TEMPLATE - url do template
			$ret = true; $ponteiro = 0;			
			while($ret=_pegatag($arquivo,$ponteiro,'','template')){$arquivo=substr_replace($arquivo,URLT,$ret['inicio'],$ret['tamanho']);$ponteiro=$ret['final'];}
			
			//Buscando e resolvendo a tag NEOS
			$ret = true; $ponteiro = 0;			
			while($ret = _pegatag($arquivo,$ponteiro)){
				//somente uma variável
				if(!isset($ret['type']) && isset($ret['var'])){$this->insere_variavel($arquivo,$ret);}
				//módulos
				if(isset($ret['type']) && isset($ret['name']) && $ret['type']=='modulo'){$this->insere_modulo($arquivo,$ret);}
				//Sub-view ou região
				if(isset($ret['type']) && isset($ret['name']) && $ret['type']=='area'){$this->insere_area($arquivo,$ret);}
				//Seta o ponteiro para a próxima varredura
				$ponteiro=$ret['final'];
			}//while
			echo $arquivo;
		}else{trigger_error('Não encontrei o arquivo de layout!');return;}		
	}
			
	function insere_variavel(&$arquivo,&$ret){
		global $ctrl;	
		@$vartemp = $ctrl->_neosVars[0][trim($ret['var'])];
		$arquivo=substr_replace($arquivo,$vartemp,$ret['inicio'],$ret['tamanho']);
		$ret['final']=strlen($vartemp)+$ret['inicio'];
	}
	
	function insere_modulo(&$arquivo,&$ret){		
		$modulo = trim($ret['name']);
		if(!isset($this->mod[$modulo])){return false;}
		_modulo($modulo);
		$mod=new $modulo();
		
		$div='';$divf='';
		foreach($ret as $key=>$value){if(trim($key)=='style' || trim($key)=='class' || trim($key)=='id' || trim($key)=='align' || trim($key)=='value' || trim($key)=='src' || trim($key)=='title'){if($div==''){$div='<div';};$div.=' '.trim($key).'="'.trim($value).'" ';}}
		if(!$div==''){$div.='>'; $divf='</div>';}
		$vartemp=$mod->start($ret,$div,$divf,$this->mod[$modulo]);
		
		$arquivo=substr_replace($arquivo,$vartemp,$ret['inicio'],$ret['tamanho']);
		$ret['final']=strlen($vartemp)+$ret['inicio'];
	}

	function insere_area(&$arquivo,&$ret){
		global $cfg;
		global $ctrl;
		$vartemp='';
		@$view=$ctrl->_neosViews[trim($ret['name'])];
		if($view!=''){
			if(file_exists($view)){
				//extraindo as variáveis...
				foreach($ctrl->_neosVars as $vars){extract($vars);}
				//gravando a view em $this->_neosViews
				ob_start();
				eval('?>'.file_get_contents($view));
				
				$div='';$divf='';
				foreach($ret as $key=>$value){if(trim($key)=='style' || trim($key)=='class' || trim($key)=='id' || trim($key)=='align' || trim($key)=='value' || trim($key)=='src' || trim($key)=='title'){if($div==''){$div='<div';};$div.=' '.trim($key).'="'.trim($value).'" ';}}
				if(!$div==''){$div.='>'; $divf='</div>';}
				$vartemp=$div.ob_get_clean().$divf;
			}
		}
		$arquivo=substr_replace($arquivo,$vartemp,$ret['inicio'],$ret['tamanho']);
		$ret['final']=strlen($vartemp)+$ret['inicio'];
}
}