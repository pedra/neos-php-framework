<?php
class NEOS_CORE extends NEOS {
	
	public $write=true;
	public $msg='';
	public $exists=false;
	
	function __construct(){
		parent::__construct();
		if(session_id()==''){session_start();}		
		//checando se tem permissão para $cfg->app
		if(!is_writable($this->_cfg->app) || !is_writable($_SERVER['SCRIPT_FILENAME'])){
			if(chmod($this->_cfg->app,0666) && chmod($_SERVER['SCRIPT_FILENAME'],0666)){$this->write=true;}
			else{$this->write=false;$this->msg='<p>Não é possível "<b>escrever</b>" na pasta da aplicação!</p><p>Modifique as permissões do diretório (e arquivos) para 0666. Ao terminar, retorne as permissões ao normal.</p>';}			
		}
		if(is_dir($this->_cfg->app.'web') || is_dir($this->_cfg->app.'controllers')){$this->exists=true;}
		$this->login();
	}
	
	//carrega uma view do core
    final function _cview($view='index',$data='',$nome='',$template='000',$ret=false){
        $view=str_ireplace(array('.html','.htm','.php','.neos'),'',trim($view));
        if(file_exists($this->_cfg->core.'Views/'.$view.'.php')) {
            $ttemp=$this->_cfg->core.'Views/'.$view.'.php';
            if ($ret === TRUE) {return file_get_contents($ttemp);}else{$nome!='' ? $this->_neosViews[$nome]= $ttemp : $this->_neosViews[]= $ttemp;}
			if(is_array($data)){$this->_viewVar($data,'',$nome);}
			if($template!='000'){$this->_cfg->default->template=$template;}
        }else{$this->_cfg->error['cod']=2;$this->_cfg->error['class']=$view;trigger_error("View ($view) não encontrada!");return false;}
    }	
	//Login genérico
	function login(){
		global $cfg;
		//criando um arquivo .htaccess
		if(!file_exists($cfg->app.'.htaccess') && !$this->exists){
			file_put_contents($cfg->app.'.htaccess','<IfModule mod_rewrite.c>
    	RewriteEngine On
    	#RewriteBase /
	RewriteCond %{REQUEST_FILENAME} !-f
    	RewriteRule ^(.*)$ index.php/$1 [QSA,L]
</IfModule>'); _goto($cfg->admin_url.'/');}
		//checando o login
		if(isset($_POST['user'])){if($_POST['user']==$cfg->admin_user && md5($_POST['pass'])==$cfg->admin_pass){
			$_SESSION['user']=$cfg->admin_user;
			$_SESSION['data']=microtime(true);			
			return true;			
			}}
		//mostra o formulário para login...
		if(!isset($_SESSION['user'])){$this->_cview('login');}	
	}
	function logout(){
		global $cfg;
		$_SESSION = array();
		session_destroy();
		if(!isset($_SESSION['user'])){_goto($cfg->admin_url.'/');}	
	}	
	//Fáz downloads de recursos extras (imagens, javascript, css, etc)
	final function pub(){
		$p=$_GET['p'];
		if($p==''){exit();}
		if(strpos($p,'..')!==false){exit();}
		if(strpos($p,'/')!==false){$d=explode('/',$p);$d[0].=SEP;}else{$d[0]='';$d[1]=$p;}		
		$this->_download($this->_cfg->core.'Public'.SEP.$d[0],$d[1]);	
	}
}
