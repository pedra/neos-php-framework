<?php
/* SUPER CLASS
 * @package 	NEOS
 * @link		http://neophp.tk
 * @since		CAN-A93H001
 */
class NEOS {
    public $_db;
    public $_cfg;
    public $_neosViews=array();
    public $_neosVars=array();
	
    function __construct(){
		$this->_cfg=&$GLOBALS['cfg'];
		if($this->_cfg->static_view!==false){if($sv=$this->_static_view()){exit($sv);}}
	}
	//carrega uma página estática (../view/static/xxx.html)
	final function _static_view($p=''){ 
		if($p==''){global $uri;if(isset($uri[0])){$p=$uri[0];}else{return false;}}
		$p=strtolower(str_ireplace(array('.html','.htm','.php','.neos'),'',trim($p,'/\\')));
		if($p=='.html' || file_exists($this->_cfg->view.'statics'.SEP.$p.'.html')){
			return file_get_contents($this->_cfg->view.'statics'.SEP.$p.'.html');	
		}else{return false;}
	}
	//carrega um helper manualmente
	final function _helper($h='',$a=''){return _helper(str_ireplace('.php','',trim($h)),$a);}
	//cria uma entrada no array dse variáveis das views
    final function _viewVar($var='neos',$val='',$view=NULL){
        if(is_array($var)){foreach($var as $key=>$val){if($view==NULL){$this->_neosVars[0][$key]=$val;}else{$this->_neosVars[$view][$key]=$val;}}
        }else{if($view==NULL){$this->_neosVars[0][$var]=$val;}else{$this->_neosVars[$view][$var]=$val;}}        
    }
	//carrega uma view
    final function _view($view='index',$data='',$nome='',$template='000',$ret=false){
        $view=str_ireplace(array('.html','.htm','.php','.neos'),'',trim($view));
        if(file_exists($this->_cfg->view.$view.'.html')) {
            $ttemp=$this->_cfg->view.$view.'.html';
            if ($ret === TRUE) {return file_get_contents($ttemp);}else{$nome!='' ? $this->_neosViews[$nome]= $ttemp : $this->_neosViews[]= $ttemp;}
			if(is_array($data)){$this->_viewVar($data,'',$nome);}
			if($template!='000'){$this->_cfg->default->template=$template;}
        }else{$this->_cfg->error['cod']=2;$this->_cfg->error['class']=$view;trigger_error("View ($view) não encontrada!");return false;}
    }
	//carrega um controller auxiliar
    final function _controller($c){
        $c=str_ireplace('.php','',ucfirst(trim($c)));
        if(file_exists($this->_cfg->ctrl.strtolower($c).'.php')){
            require_once($this->_cfg->ctrl.strtolower($c).'.php');
            return new $c;
        }else{$this->_cfg->error['cod']=1;$this->_cfg->error['class']=$c;trigger_error("Controller '$c' não encontrado!");} 
    }
	//carrega um model
    final function _model($m,$ret=false){
        $m=str_ireplace('.php','',ucfirst(trim($m)));
        if(file_exists($this->_cfg->model.strtolower($m).'.php')) {
            require_once($this->_cfg->model.strtolower($m).'.php');
            if($ret){$this->model=new $m;}else{return new $m;}
        }else{
			$this->_cfg->error['cod']=3;
			$this->_cfg->error['class']=$m;
			trigger_error("Model '$m' não encontrado!");}        
    }
	//carrega um helper automaticamente
    function __call($f,$a){return _helper($f,$a);}
}
//HELPERS ---------------------------------------------------------------------------------------------------------------
//carregador de classes automatico.
function _load($c){
    global $cfg;
    $l=array($cfg->view,$cfg->model,$cfg->library,$cfg->driver,$cfg->core.'Library'.SEP,$cfg->core.'Library'.SEP.'DB'.SEP);
    foreach($l as $loc){if(file_exists($loc.strtolower($c).'.php')){require_once($loc.strtolower($c).'.php');return;}}
	$cfg->error['cod']=5;
	$cfg->error['class']=$c;
    trigger_error('Classe "'.$c.'" não encontrada.');
    return false;
}
//carrega um helper (ou pack de helpers)
function _helper($f,$a,$p=''){
    if(!function_exists($f)){
		if(trim($p)!=''){$p=trim($p,'/\\').'/';}else{$p='';}
        global $cfg;$b=false;
		$l=array($cfg->helper,$cfg->core.'Helpers'.SEP);
        foreach($l as $loc){if(file_exists($loc.strtolower($p.$f).'.php')){include($loc.strtolower($p.$f).'.php');$b=true;break;}}
		if(!$b){$cfg->error['cod']=4;$cfg->error['function']=$f;trigger_error('Helper "'.$f.'" não encontrado!');return false;}
	}
	return call_user_func_array($f,$a);
}
//tratamento de erros
function trata_erros($n=0,$m,$f='',$l='',$v=''){
	global $cfg;if($n > $cfg->error['level']){return;}
    $NFE=new NEOS_Error($n,$m,$f,$l,$v);
  	if($NFE){exit();}else{return;}
}
//exception
function trata_exception($m){	
	$NFE=new NEOS_Exception($m);
	if($NFE){exit();}else{return;}
}
//carrega um módulo (?templates!)
function _modulo($modulo,$start=false){
    global $cfg;
    if(file_exists($cfg->module.strtolower($modulo).'.php')){
        require_once($cfg->module.strtolower($modulo).'.php');
        if($start){$mod=new $modulo;return $mod->start();
        }else{return;}
    }
	$cfg->error['cod']=6;
    trigger_error('Módulo "'.$modulo.'" não encontrado.');
    return false;
}
//desvia para outra url - "vai para..."
function _goto($uri='', $metodo='', $codigo_http=302){
    if(strtolower($metodo)=='refresh'){header('Refresh:0;url='.URL.$uri);
    }else{header('Location: '.URL.$uri, TRUE, $codigo_http);}
	exit;
}
//drive básico de acesso a banco de dados
function _db($p1='',$p2='',$p3='',$f='',$alias='',$driver=''){
    global $cfg,$ctrl;
	if($alias==''){$alias=$cfg->default->db;}
	$f=strtolower(trim($f));
	if($driver==''){$driver=trim(strtolower($cfg->db->{$alias}->driver));}
	//Se for PDO...
	if($driver=='pdo'){	
		if(!is_object($ctrl->_db) || get_class($ctrl->_db)!='NEOS_DBO'){$ctrl->_db=new NEOS_DBO($alias);}
		if($p1==''){return $ctrl->_db;}
		if($f==''){return $ctrl->_db->query($p1,$p2,$p3);}else{return $ctrl->_db->{$f}($p1,$p2,$p3);}
	//Se não for, usa uma classe para o driver selecionado...	
	}else{
		if(!is_object($ctrl->_db) || get_class($ctrl->_db)!=ucfirst($driver)){$temp=ucfirst($driver);$ctrl->_db = new $temp;}
		if($p1==''){return $ctrl->_db;}
    	if($f==''){return $ctrl->_db->query($p1,$p2,$p3);}else{return $ctrl->_db->{$f}($p1,$p2,$p3);}
	}
}
//scaner de NeosTags
function _pegatag(&$arquivo,$inicio=0,$type='',$tag='neos'){
    global $cfg;
    $tamanho=strlen($arquivo);
    $ret=false;
    $tag_inicio=stripos($arquivo,'<'.$tag.' ',$inicio);
    if($tag_inicio!==false){
        $tag_final=stripos($arquivo,'>',$tag_inicio);
        if($tag_final===false){return false;}
		$tag_tamanho=($tag_final + 1)-$tag_inicio;
        $xml='<?xml version="1.0" encoding="'.$cfg->charset.'"?>'.substr($arquivo,$tag_inicio,$tag_tamanho-1).'/>';
        $xml=str_replace('//>','/>',$xml);
        $xml=simplexml_load_string($xml);
        if(is_object($xml)){foreach($xml->attributes() as $k=>$v){$ret[trim($k)]=trim($v);}}
        $ret['inicio']=$tag_inicio;
        $ret['final']=$tag_final;
        $ret['tamanho']=$tag_tamanho;
    }
	return $ret;
}
//scaner de NeosTags completo
function _pegaAlltag(&$arquivo,$tag='neos:url',$subst=URL){
	if(is_array($tag)){foreach($tag as $v){$ntag[]='<'.$v.'/>';}return str_ireplace($ntag,$subst,$arquivo);}
	else{return str_ireplace('<'.$tag.'/>',$subst,$arquivo);}}
//benchmark
function _setmark($name='',$files=false,$vars=''){
	global $neos_benchmark;
	$a=count($neos_benchmark);
	$neos_benchmark[$a]['time']=microtime(true);
	$neos_benchmark[$a]['mem']=memory_get_usage();
	$neos_benchmark[$a]['peak']=memory_get_peak_usage();
	$neos_benchmark[$a]['name']=trim($name);
	if($files){$neos_benchmark[$a]['files']=get_included_files();}
	if($vars!=''){$neos_benchmark[$a]['vars']=$vars;}
}
//retorna objetos do núcleo
function _neos($a='db'){
	global $cfg;	
	if($cfg->use_db==true && strtolower($a)=='db'){global $neos_db;if(!is_object($neos_db)){$neos_db=new NEOS_DB;$neos_db->_start();}return $neos_db;}
	if(strtolower($a)=='cfg'){return $cfg;}
	if(strtolower($a)=='ctrl'){global $ctrl;return $ctrl;}
}
//CORE ---------------------------------------------------------------------------------------------------------------
@ob_start();
if(function_exists('ini_set')){@ini_set('display_errors','0');}
@define('_CAN','A93H001');//define o CAN
@_setmark();//benchmark
@define('SEP', DIRECTORY_SEPARATOR);
@$cfg->core=dirname(__FILE__).SEP;//core path
if(stripos(@$cfg->app,'phar://')===false){@$cfg->app=realpath($cfg->app).SEP;}//app path
@include $cfg->core.'Config/config.php';//core/config
@include $cfg->app.'config.php';//app/config
if(@$cfg->session){@session_start();}//inicia sessões
//define a URL
if(!isset($cfg->sub_uri)){
@$c=str_ireplace($_SERVER['DOCUMENT_ROOT'],'',$_SERVER['SCRIPT_NAME']);@$c=str_ireplace(basename($_SERVER['SCRIPT_NAME']),'',$c);@$f='';
for($i=0;$i<=(strlen($_SERVER['REQUEST_URI'])-1);$i++){if(!isset($c[$i]) || !isset($_SERVER['REQUEST_URI'][$i])){break;}if($_SERVER['REQUEST_URI'][$i]==$c[$i]){@$f.=$c[$i];}else{break;}}
@$cfg->sub_uri=trim($f,'/');}
@$cfg->sub_uri=trim($cfg->sub_uri,'/\\');if($cfg->sub_uri!=''){$cfg->sub_uri=$cfg->sub_uri.'/';}
@define('URL','http://'.$_SERVER['HTTP_HOST'].'/'.$cfg->sub_uri);
//callback de erro e exceção
@error_reporting($cfg->error['level']);
if(function_exists('ini_set')){@ini_set('error_prepend_string','<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>NEOS PHP Framework - parado...</title></head><body bgcolor="#FF3355"><div style="font-family:Verdana,Helvetica,sans-serif;margin:90px auto;width:500px;background:#FEE;padding:20px;border:20px dashed #F35";><h1>NEOS PHP Framework</h1><p>O seguinte erro parou o sistema:</p><p style="color:#960">');
@ini_set('error_append_string','</p><p style="margin-top:100px;font-size:11px;color:#935">Provavelmente esse erro foi causada por algum <b>problema em seu script</b> - verifique.<br />
Apesar de exaustivos testes, &eacute; possivel que ocorram bugs no NEOS. Se for este o caso reporte este <a href="http://neophp.tk/bugs/">bug</a> em nosso site ou procure ajuda em nossa <a href="http://neophp.tk/manual/">documenta&ccedil;&atilde;o</a>.</p></div></body></html>');}
if(function_exists('ini_set')){if(isset($cfg->error['action']) && trim($cfg->error['action'])!=''){@ini_set('display_errors','1');}else{@ini_set('display_errors','0');}}
set_error_handler('trata_erros');
set_exception_handler('trata_exception');
spl_autoload_register('_load');//autoload classes
//resolve URI
$uri=$_SERVER['REQUEST_URI'];
$uri=str_replace($cfg->sub_uri,'',$uri);
$uri=str_replace(basename($_SERVER['SCRIPT_NAME']),'',$uri);
$uri=urldecode($uri);
$uri=explode('/',trim($uri,'/'));
//Métodos de acesso = GET, POST ou RELATIVE
$neos_metodo='';
//GET
if($neos_metodo=='' && isset($_GET[$cfg->get_ctrl])){
	$uri[0]=$_GET[$cfg->get_ctrl];unset($_GET[$cfg->get_ctrl]);
    if(isset($_GET[$cfg->get_func])){$uri[1]=$_GET[$cfg->get_func];unset($_GET[$cfg->get_func]);}
	$uri=array_merge($uri,$_GET);
    $neos_metodo='GET';
}
//POST
if($neos_metodo=='' && isset($_POST[$cfg->post_ctrl])){
	$uri[0]=$_POST[$cfg->post_ctrl];unset($_POST[$cfg->post_ctrl]);
    if(isset($_POST[$cfg->post_func])){$uri[1]=$_POST[$cfg->post_func];unset($_POST[$cfg->post_func]);}
	$uri=array_merge($uri,$_POST);
    $neos_metodo='POST';
}
//RELATIVE (URL)
if($neos_metodo==''){
    isset($uri[0]) && $uri[0]!='' ? $uri[0]=trim($uri[0]) : $uri[0]=$cfg->default->ctrl;
	isset($uri[1]) && $uri[1]!='' ? $uri[1]=trim($uri[1]) : '';
	$neos_metodo='URL';
}
//Mask
	$c=trim($uri[0]);
	if(isset($cfg->mask[$c][0])){unset($uri[0]);foreach(array_reverse($cfg->mask[$c]) as $v){array_unshift($uri,$v);}}
//Chamando o controller do CORE SERVICE
if(isset($cfg->admin_url) && $cfg->admin_url==$uri[0]){unset($uri[0]);require_once($cfg->core.'Controllers/'.$cfg->admin_controller);$ctrl=new Core;}else{
//Chamando o controller	
if(file_exists($cfg->ctrl.strtolower($uri[0]).'.php')){$cfg->default->ctrl=ucfirst(trim($uri[0]));unset($uri[0]);}
if(!file_exists($cfg->ctrl.strtolower($cfg->default->ctrl).'.php')){$cfg->error['cod']=1;trigger_error('Controller "'.$cfg->default->ctrl.'" n&atilde;o encontrado.');}
require_once($cfg->ctrl.strtolower($cfg->default->ctrl).'.php');
$ctrl=new $cfg->default->ctrl;}
//function
if(isset($uri[1])){$func=trim($uri[1],'_');if($uri[1]==$cfg->default->func){unset($uri[1]);}}else{$func=$cfg->default->func;}
$cfg->default->args=$uri;
if(method_exists($ctrl,$func)){if(isset($uri[1]) && $func==$uri[1]){unset($uri[1]);}call_user_func_array(array($ctrl,$func),$uri);
}else{call_user_func_array(array($ctrl,$cfg->default->func),$uri);}
//OUTPUT ---------------------------------------------------------------------------------------------------------------
//TEMPLATES
if(isset($_SESSION['template']) && $_SESSION['template']!=''){$cfg->default->template=$_SESSION['template'];}
if($cfg->default->template!=''){
    define('URLT',URL.$cfg->template_url.'/'.$cfg->default->template.'/');
    $ctrl->_neosVars[0]['urlt']=URLT;
	//se existir, carrega a classe de template do próprio template atual (_tpl/template/index.php)
    if(file_exists($cfg->template_path.$cfg->default->template.SEP.'index.php')){
		include $cfg->template_path.$cfg->default->template.SEP.'index.php';
		$ttp=new template;
    }else{$ttp=new NEOS_Template;}
	$ttp->get_layout();
}else{
//VIEWS
	if(isset($ctrl->_neosVars[0])){extract($ctrl->_neosVars[0]);}
	count($ctrl->_neosViews)>0 ? $arrayView=&$ctrl->_neosViews : $arrayView=array();
    foreach($arrayView as $nomeView=>$valView){
        $neosarquivo = file_get_contents($valView);
		$ret=true;
        $ponteiro=0;
		while($ret=_pegatag($neosarquivo,$ponteiro)){
            @$ponteiro=$ret['final']+0;
			$vartemp='';
            //chamando as neosTags - helpers
			if(isset($ret['var']) && (!isset($ret['type']))){_helper('_neostagvar',array(),'neostag');}
			if(isset($ret['type'])){_helper('_neostag'.trim($ret['type']),array(),'neostag');}
			//Incluindo o bloco gerado pelas NeosTags
			$neosarquivo=substr_replace($neosarquivo,$vartemp,$ret['inicio'],$ret['tamanho']);
			//RE-setando o ponteiro depois de adicionar os dados acima
			$ponteiro=strlen($vartemp)+$ret['inicio'];
        }//while
		//setando as tags URL e CHARSET do site (a tag TEMPLATE será pega na classe de template...)
		$neosarquivo=_pegaAlltag($neosarquivo,array('neos:url','neos:charset'),array(URL,$cfg->charset));
		//"Avaliando" o PHP contido no HTML
        if(isset($ctrl->_neosVars[$nomeView])){extract($ctrl->_neosVars[$nomeView]);}
        eval('?>'.$neosarquivo);
    }//foreach
}//fim do IF template
//Mostra o uso de memória e tempo de execução total - depende da classe NEOS_Status.
if(trim($cfg->status)!=''){$s=new NEOS_Status();}