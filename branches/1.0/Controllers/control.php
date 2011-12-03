<?php
class Core extends NEOS_CORE{
	
	function index($t=''){
		$this->install();
		//-----------------------------------------------------------------------------------------
		// 	TODO
		//	a função _db(); ficou com a possibilidade de carregar outros drivers além do PDO.
		//	Configuração
		//	$cfg->db->ALIAS->driver 	= nome da classe de conexão ou 'pdo' para NEOS_DBO
		//	$cfg->db->ALIAS->dsn		= dsn de conexão pdo - somente PDO;
		//	$cfg->db->ALIAS->host		= host para conexão não PDO;
		//	$cfg->db->ALIAS->user		= usuário;
		//	$cfg->db->ALIAS->pass		= senha;
		//	$cfg->db->ALIAS->database	= database ou banco de dados a ser acessado - para Sqlite (sem PDO) equivale ao local do arquivo sqlite;
		//	$cfg->db->ALIAS->charset	= Set de caractere usado para a conexão (ex.: utf8, latin1, ...);
		//
		//	O que precisa ser feito!
		//	Para o driver PDO é necessário chamar a função '$q->all()' para pegar os resultados
		//	Para os outros drivers o resultado (objeto) já vem depois do query...
		//	É necessário chegar a um concenso...
		//-----------------------------------------------------------------------------------------
		
		//Considerando que o default é: sqlite_pdo - usando o model CHAIN para obter o resultado: _db()->query()->all();
		//$q = _db()->query('select * from sqlite_master')->all(NEOS_DBO::ARR_NUM);
		//echo '<pre>'.print_r($q,true).'</pre>';

		//Forçando o uso do ALIAS sqlite (drive comum Sqlite):
		//$q = _db('select * from sqlite_master','','','','sqlite');
		//echo '<pre>'.print_r($q,true).'</pre>';
		
		//return;
				
	}
	function erro(){$this->_cview('c_erro');}
	//---------------------------------------------------------------------------------------------------------------------------------------------	
	private function _layout_a(){
		global $cfg;
		@mkdir($cfg->ctrl,0777);
		@mkdir($cfg->view,0777);
		@mkdir($cfg->view.'statics/',0777);
		@mkdir($cfg->model,0777);
		@mkdir($cfg->app.'images/',0777);
		@mkdir($cfg->app.'js/',0777);
		@mkdir($cfg->app.'css/',0777);
		//controller
		file_put_contents($cfg->ctrl.$cfg->default->ctrl.'.php','<?php
	
class '.ucfirst($cfg->default->ctrl).' extends NEOS 
{	
      //optional: function __construct(){parent::__construct(); /* other constructions...*/ }
		
      function '.$cfg->default->func.'()
      {		
          $this->_view(\'neos_welcome\');		
      }
	
}');
		@chmod($cfg->ctrl.$cfg->default->ctrl.'.php',0777);
		//view
		file_put_contents($cfg->view.'neos_welcome.html',file_get_contents($cfg->core.'Views/splash.php'));
		@chmod($cfg->view.'neos_welcome.html',0777);
		//arquivos htaccess
		file_put_contents($cfg->ctrl.'.htaccess','Deny From All'); 
		@chmod($cfg->ctrl.'.htaccess',0777);
		file_put_contents($cfg->view.'.htaccess','Deny From All'); 
		@chmod($cfg->view.'.htaccess',0777);
		file_put_contents($cfg->model.'.htaccess','Deny From All'); 
		@chmod($cfg->model.'.htaccess',0777);
		file_put_contents($cfg->app.'.htaccess','<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php/$1 [QSA,L]
</IfModule>');
		@chmod($cfg->app.'.htaccess',0777);
		//config...
		if($_POST['seldb']!=$cfg->default->db || $_POST['novo_alias']!=''){
			$config=$this->_setdb();
			file_put_contents($cfg->app.'config.php',$config); 
			@chmod($cfg->app.'config.php',0777);	
		}
		//arquivo index
		if(file_exists($cfg->app.'index.php')){if(!unlink($cfg->app.'index.php')){$this->_viewVar('alerta','Erro ao apagar arquivo "index.php"!');return false;};}
		file_put_contents($cfg->app.'index.php','<?php $cfg->app=dirname(__FILE__); include \''.$cfg->core.'core.php\';'); 
		@chmod($cfg->app.'index.php',0777);
		return true;
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------	
	private function _layout_b(){
		global $cfg;
		@mkdir($cfg->app.'app/',0777);
		@mkdir($cfg->app.'app/controllers/',0777);
		@mkdir($cfg->app.'app/views/',0777);
		@mkdir($cfg->app.'app/views/statics/',0777);
		@mkdir($cfg->app.'app/models/',0777);
		@mkdir($cfg->app.'images/',0777);
		@mkdir($cfg->app.'js/',0777);
		@mkdir($cfg->app.'css/',0777);
		//controller
		file_put_contents($cfg->app.'app/controllers/'.$cfg->default->ctrl.'.php','<?php
	
class '.ucfirst($cfg->default->ctrl).' extends NEOS 
{	
      //optional: function __construct(){parent::__construct(); /* other constructions...*/ }
		
      function '.$cfg->default->func.'()
      {		
          $this->_view(\'neos_welcome\');		
      }
	
}');	
		@chmod($cfg->app.'app/controllers/'.$cfg->default->ctrl.'.php',0777);
		//view
		file_put_contents($cfg->app.'app/views/neos_welcome.html',file_get_contents($cfg->core.'Views/splash.php')); 
		@chmod($cfg->app.'app/views/neos_welcome.html',0777);
		//arquivos htaccess
		file_put_contents($cfg->app.'app/.htaccess','Deny From All');
		@chmod($cfg->app.'app/.htaccess',0777);
		file_put_contents($cfg->app.'.htaccess','<IfModule mod_rewrite.c>
    RewriteEngine On
	RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php/$1 [QSA,L]
</IfModule>');
		@chmod($cfg->app.'.htaccess',0777);
		//config...
		if($_POST['seldb']!=$cfg->default->db || $_POST['novo_alias']!=''){
			$config=$this->_setdb();
			file_put_contents($cfg->app.'app/config.php',$config);
			@chmod($cfg->app.'app/config.php',0777);	
		}
		//arquivo index
		if(file_exists($cfg->app.'index.php')){if(!unlink($cfg->app.'index.php')){$this->_viewVar('alerta','Erro ao apagar arquivo "index.php"!');return false;};}
		file_put_contents($cfg->app.'index.php','<?php $cfg->app=dirname(__FILE__).\'/app/\'; include \''.$cfg->core.'core.php\';'); 
		@chmod($cfg->app.'index.php',0777);
		return true;		
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------	
	private function _layout_c(){
		if($this->exists){return false;}
		global $cfg;
		@mkdir($cfg->app.'app/',0777);
		@mkdir($cfg->app.'web/',0777);
		@mkdir($cfg->app.'web/images/',0777);
		@mkdir($cfg->app.'web/js/',0777);
		@mkdir($cfg->app.'web/css/',0777);
		@mkdir($cfg->app.'app/controllers/',0777);
		@mkdir($cfg->app.'app/views/',0777);
		@mkdir($cfg->app.'app/views/statics/',0777);
		@mkdir($cfg->app.'app/models/',0777);
		//controller
		file_put_contents($cfg->app.'app/controllers/'.$cfg->default->ctrl.'.php','<?php
	
class '.ucfirst($cfg->default->ctrl).' extends NEOS 
{	
      //optional: function __construct(){parent::__construct(); /* other constructions...*/ }
		
      function '.$cfg->default->func.'()
      {		
          $this->_view(\'neos_welcome\');		
      }
	
}');
		@chmod($cfg->app.'app/controllers/'.$cfg->default->ctrl.'.php',0777);
		//view
		file_put_contents($cfg->app.'app/views/neos_welcome.html',file_get_contents($cfg->core.'Views/splash.php'));
		@chmod($cfg->app.'app/views/neos_welcome.html',0777);
		//arquivos htaccess
		file_put_contents($cfg->app.'.htaccess','<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule    ^$ web/    [L]
    RewriteRule    (.*) web/$1 [L]
</IfModule>');
		@chmod($cfg->app.'.htaccess',0777);
		file_put_contents($cfg->app.'web/.htaccess','<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php/$1 [QSA,L]
</IfModule>');
		@chmod($cfg->app.'web/.htaccess',0777);
		//config...
		if($_POST['seldb']!=$cfg->default->db || $_POST['novo_alias']!=''){
			$config=$this->_setdb();
			file_put_contents($cfg->app.'app/config.php',$config);
			@chmod($cfg->app.'app/config.php',0777);	
		}
		//arquivo index
		file_put_contents($cfg->app.'web/index.php','<?php $cfg->app=dirname(dirname(__FILE__)).\'/app/\'; include \''.$cfg->core.'core.php\';');
		if(file_exists($cfg->app.'index.php')){if(!unlink($cfg->app.'index.php')){$this->_viewVar('alerta','Erro ao apagar arquivo "index.php"!');return false;};}
		return true;
	}		
	//---------------------------------------------------------------------------------------------------------------------------------------------
	private function _setdb(){
		$config='<?php ';
		if($_POST['novo_alias']!=''){
			$config.="\n//Banco de Dados";
			//pdo
			if(trim($_POST['bd_driver'])=='pdo'){	
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->driver=\'pdo\';';			
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->dsn=\''.$_POST['bd_dsn'].'\';';
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->user=\''.$_POST['bd_user'].'\';';
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->pass=\''.$_POST['bd_pass'].'\';';
			}
			if(trim($_POST['bd_driver'])=='sqlite'){
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->driver=\'sqlite\';';
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->database=\''.$_POST['bd_database'].'\';';
			}
			if(trim($_POST['bd_driver'])=='mysql'){	
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->driver=\'mysql\';';
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->host=\''.$_POST['bd_host'].'\';';				
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->user=\''.$_POST['bd_user'].'\';';
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->pass=\''.$_POST['bd_pass'].'\';';
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->database=\''.$_POST['bd_database'].'\';';
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->charset=\''.$_POST['bd_charset'].'\';';
			}
			if(trim($_POST['bd_driver'])=='oracle'){	
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->driver=\'oracle\';';			
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->host=\''.$_POST['bd_host'].'\';';				
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->user=\''.$_POST['bd_user'].'\';';
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->pass=\''.$_POST['bd_pass'].'\';';
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->database=\''.$_POST['bd_database'].'\';';
				$config.="\n".'$cfg->db->'.$_POST['novo_alias'].'->charset=\''.$_POST['bd_charset'].'\';';
			}
			$config.="\n//Default \n";
			$config.='$cfg->default->db=\''.$_POST['novo_alias'].'\';';
		}else{
			if($_POST['seldb']!='nenhum'){
				$config.="\n//Default \n";
				$config.='$cfg->default->db=\''.$_POST['seldb'].'\';';
			}
		}
		return $config;
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------	
	function install($test=''){
		global $cfg;
		if($test!=''){$this->bdteste();return;}
		//avisando que já existe...
		if($this->exists){$this->msg.='<p>Já existe uma <b>aplicação instalada</b> neste diretório!</p><p>Você <b>não</b> conseguirá instalar outra...</p>';}
		if($this->msg!=''){$this->_viewVar('msg',$this->msg);}
		//processando os dados da instalação
		if(isset($_POST['save']) && $this->write){
			//criado a estrutura de pastas
			switch($_POST['layout']){
				case 'A': $ret=$this->_layout_a();break;					
				case 'B': $ret=$this->_layout_b();break;				
				case 'C': $ret=$this->_layout_c();break;
			}
			if($ret){$this->_viewVar('alerta','Aplicação instalada com sucesso!\n\nTalvez seja necessário recarregar...');}else{$this->_viewVar('alerta','Ocorreu algum erro durante a instalação!\n\nPor favor verifique.');}
			
		}
		//local da instalação
		if(isset($_POST['local'])){$this->_viewVar('local',$_POST['local']);}else{$this->_viewVar('local',dirname($_SERVER['SCRIPT_FILENAME']).'/');}
		//URL da instalação
		if(isset($_POST['url'])){$this->_viewVar('url',$_POST['url']);}else{$this->_viewVar('url',URL);}
		//listagem de bancos de dados
		$a['nenhum']='- Nenhum -';
		foreach($cfg->db as $k=>$v){$a[$k]=$k;}
		$this->_viewVar('db',$a);
		//listagem de conectores de BD
		$this->_viewVar('bd_driver',array('pdo'=>'PDO','mysql'=>'Mysql','sqlite'=>'Sqlite','oracle'=>'Oracle'));		
		//carregando a view
		$this->_cview('c_install');
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------
	//Testando o Banco de Dados - AJAX
	function bd_teste(){
		global $cfg;

		switch($_POST['bd_driver']){		
		case 'pdo':	
			if(!class_exists('PDO')){echo 'PDO não está disponivel (ou habilitado) neste sistema!';}
			else{
				try{$c=new PDO($_POST['bd_dsn'],$_POST['bd_user'],$_POST['bd_pass']);
				}catch(PDOException $e){echo 'Conexão PDO não pode ser feita: ' . $e->getMessage();break;}
				$m='Erro de SQL!!';
				foreach($c->query('Select "SQL testado com sucesso!"') as $val){$m=$val[0];}
				echo "Banco de dados conectado...\n".$m;
			}
			break;
		case 'mysql':
			$temp=_db('Select "SQL testado com sucesso!"','','','','','mysql');
			if($temp){
				foreach($temp as $v){foreach($v as $vv){echo $vv;};}
				echo "\n".'Banco de dados MYSQL';
			}else{echo 'Não foi possível conectar ao banco de dados!!';}
			break;
		case 'sqlite':
			$temp=_db('Select "SQL testado com sucesso!"','','','','','sqlite');
			if($temp){
				foreach($temp as $v){foreach($v as $vv){echo $vv;};}
				echo "\n".'Banco de dados SQLITE';
			}else{echo 'Não foi possível conectar ao banco de dados!!';}
			break;
		case 'oracle':
			$temp=_db('Select "SQL testado com sucesso!"','','','','','oracle');
			if($temp){
				foreach($temp as $v){foreach($v as $vv){echo $vv;};}
				echo "\n".'Banco de dados ORACLE';
			}else{echo 'Não foi possível conectar ao banco de dados!!';}
			break;
		default: echo 'Não encontrei o banco de dados...'; break;		
		}
		exit();
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------
	private function upload($name,$local,$file){
		global $cfg;
		//checando o destino
		if(is_file($local.$file)){$this->_viewVar('alerta','O arquivo já existe!'); return;}
		if(!is_dir($local)){if(!mkdir($local,0777)){$this->_viewVar('alerta','Não consegui criar o diretório "'.$local.'"!'); return false;};}
		//criando o arquivo
		if (move_uploaded_file($_FILES[$name]['tmp_name'], $local.$file)){
			$this->_viewVar('alerta','O arquivo foi salvo com sucesso!');
		} else {
			$this->_viewVar('alerta','O arquivo NÃO foi salvo!');
		}	
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------
	private function savefile($content,$local,$file){
		//checando o destino
		if(is_file($local.$file)){$this->_viewVar('alerta','O arquivo já existe!'); return;}
		if(!is_dir($local)){if(!mkdir($local,0777)){$this->_viewVar('alerta','Não consegui criar o diretório "'.$local.'"!'); return false;}}
		if(isset($_POST['utf8'])){$content=utf8_encode($content);}
		if(file_put_contents($local.$file,$content)){$this->_viewVar('alerta','Arquivo salvo com sucesso!');@chmod($local.$file,0777);}else{$this->_viewVar('alerta','Arquivo NÃO pode ser salvo!');}
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------
	function ctrl(){
		global $cfg;
		$this->_viewVar('ctrls','selected');
		$this->_viewVar('recurse','ctrl');
		$this->_viewVar('caminho',$cfg->ctrl);		
		if(isset($_POST['save'])){
			if(trim($_POST['file'])==''){$this->_viewVar('alerta','Indique o nome do arquivo!!');}
			else{
				if(isset($_FILES['loaded']['name']) && $_FILES['loaded']['error']==0){$this->upload('loaded',$cfg->ctrl,trim($_POST['file']).'.php');}
				else{if(trim($_POST['content'])!=''){$this->savefile($_POST['content'],$cfg->ctrl,strtolower($_POST['file']).'.php');}}
			}
		}
		$this->_viewVar('titulo','CONTROLLER');
		if(isset($_POST['file'])){$file=$_POST['file'];}else{$file='Controller';}
		$this->_viewVar('file',strtolower($file));
		if(isset($_POST['content'])){$this->_viewVar('content',str_ireplace('<?','&lt;?',$_POST['content']));}
		else{$this->_viewVar('content',"&lt;?php
	
class $file extends NEOS 
{	
      //optional: function __construct(){parent::__construct(); /* other constructions...*/ }
		
      function index()
      {		
          //your code here!		
      }
	
}");}
		$this->_cview('c_recurse');	
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------
	function model(){
		global $cfg;
		$this->_viewVar('model','selected');
		$this->_viewVar('recurse','model');
		$this->_viewVar('caminho',$cfg->model);		
		if(isset($_POST['save'])){
			if(trim($_POST['file'])==''){$this->_viewVar('alerta','Indique o nome do arquivo!!');}
			else{
				if(isset($_FILES['loaded']['name']) && $_FILES['loaded']['error']==0){$this->upload('loaded',$cfg->model,trim($_POST['file']).'.php');}
				else{if(trim($_POST['content'])!=''){$this->savefile($_POST['content'],$cfg->model,strtolower($_POST['file']).'.php');}}
			}
		}
		$this->_viewVar('titulo','MODEL');
		if(isset($_POST['file'])){$file=$_POST['file'];}else{$file='Model';}
		$this->_viewVar('file',strtolower($file));
		if(isset($_POST['content'])){$this->_viewVar('content',str_ireplace('<?','&lt;?',$_POST['content']));}
		else{$this->_viewVar('content',"&lt;?php
	
class $file extends NEOS_models 
{	
      //optional: function __construct(){parent::__construct(); /* other constructions...*/ }
		
      function index()
      {		
          //your code here!		
      }
	
}");}
		$this->_cview('c_recurse');	
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------
	function view(){
		global $cfg;
		$this->_viewVar('caminho',$cfg->view);
		if(isset($_POST['save'])){
			$content=trim($_POST['head']).trim($_POST['body']);
			if(trim($_POST['file'])==''){$this->_viewVar('alerta','Indique o nome do arquivo!!');}
			else{
				if(isset($_FILES['loaded']['name']) && $_FILES['loaded']['error']==0){$this->upload('loaded',$cfg->view,trim($_POST['file']).'.html');}			
				else{if($content!=''){$this->savefile($content,$cfg->view,strtolower($_POST['file']).'.html');}}
			}
		}
		if(isset($_POST['file'])){$file=$_POST['file'];}else{$file='my_view';}
		$this->_viewVar('file',strtolower($file));
		if(isset($_POST['head'])){$this->_viewVar('head',str_ireplace('<?','&lt;?',$_POST['head']));}
		else{$this->_viewVar('head','<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

      <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
          <title>NEOS PHP Framework</title>
          <link href="<url/>css/css.css" rel="stylesheet" type="text/css" />
          <script type="text/javascript" src="<url/>js/jquery.js"></script>
          <script type="text/javascript" src="<url/>js/seu_javascript.js"></script>
      </head>
	  ');}
		if(isset($_POST['body'])){$this->_viewVar('body',str_ireplace('<?','&lt;?',$_POST['body']));}
		else{$this->_viewVar('body','      <body>
	
          <neos var="content" class="conteudo" />
          <!-- resulta em: --> <div class="conteudo"> o valor da variável "content" </div>

          <neos type="module" name="menu" class="menu_esquerdo" id="menu" />
          <!-- carrega o módulo "menu" em substiituição a neosTag acima -->
		
          <neos type="select" var="array" class="escolha" id="escolha" />
          <!-- cria um "<select class="escolha" ... " com os dados de "array" -->

          &lt;?php if ( isset($mensagem) ){ echo $mensagem; } ?>
          <!-- prefira não usar PHP (como acima) - use neosTags equivalentes: -->
          <neos var="mensagem"/>
	
      </body>
	
</html>
');}
		$this->_cview('c_view');	
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------
	function helper(){
		global $cfg;
		$this->_viewVar('helper','selected');
		$this->_viewVar('recurse','helper');
		$this->_viewVar('caminho',$cfg->helper);		
		if(isset($_POST['save'])){
			if(trim($_POST['file'])==''){$this->_viewVar('alerta','Indique o nome do arquivo!!');}
			else{
				if(isset($_FILES['loaded']['name']) && $_FILES['loaded']['error']==0){$this->upload('loaded',$cfg->helper,trim($_POST['file']).'.php');}
				else{if(trim($_POST['content'])!=''){$this->savefile($_POST['content'],$cfg->helper,strtolower($_POST['file']).'.php');}}
			}
		}
		$this->_viewVar('titulo','HELPER');
		if(isset($_POST['file'])){$file=$_POST['file'];}else{$file='my_helper';}
		$this->_viewVar('file',strtolower($file));
		if(isset($_POST['content'])){$this->_viewVar('content',str_ireplace('<?','&lt;?',$_POST['content']));}
		else{$this->_viewVar('content',"&lt;?php 
		
if(!function_exists( '$file' )){
		
      function $file()
      {		
          //your code here!		
      }

}");}
		$this->_cview('c_recurse');	
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------
	function library(){
		global $cfg;
		$this->_viewVar('library','selected');
		$this->_viewVar('recurse','library');
		$this->_viewVar('caminho',$cfg->library);
		if(isset($_POST['save'])){
			if(trim($_POST['file'])==''){$this->_viewVar('alerta','Indique o nome do arquivo!!');}
			else{
				if(isset($_FILES['loaded']['name']) && $_FILES['loaded']['error']==0){$this->upload('loaded',$cfg->library,trim($_POST['file']).'.php');}
				else{if(trim($_POST['content'])!=''){$this->savefile($_POST['content'],$cfg->library,strtolower($_POST['file']).'.php');}}
			}
		}
		$this->_viewVar('titulo','LIBRARY');
		if(isset($_POST['file'])){$file=$_POST['file'];}else{$file='my_library';}
		$this->_viewVar('file',strtolower($file));
		if(isset($_POST['content'])){$this->_viewVar('content',str_ireplace('<?','&lt;?',$_POST['content']));}
		else{$this->_viewVar('content',"&lt;?php
	
class $file extends NEOS_class 
{	
      //optional: function __construct(){parent::__construct(); /* other constructions...*/ }
		
      function index()
      {		
          //your code here!		
      }
	
}");}
		$this->_cview('c_recurse');
	}
}
?>