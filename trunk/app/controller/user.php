<?php
/**
 * Controller User - gerenciamento de usuários do site
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Controller
 */
	
class Controller_User extends NEOS
{
	public $mail_host='localhost';
	public $mail_titulo='Ativação de Cadastro - NEOS PHP Framework';
	public $mail_origem='contato@site.com';
	public $mail_nomeorigem='NEOS PHP Framework';
	
	function __construct(){
		$t = _user()->get('theme');
		if(!$t){ $t= 'zurox'; _user()->set('theme',$t);}
		_cfg()->template = $t;
		if($t=='basico'){ _viewVar('themas',array('basico'=>'Básico','zurox'=>'Zurox'));}
		else {_viewVar('themas',array('zurox'=>'Zurox','basico'=>'Básico'));}
		
		_app('titulo', 'Comunidade :: NEOS PHP Framework');	
	}	
	
	function index(){
		$d['sair']='';
		$d['entrar']='';
		$d['suser']='class="selected"';
		if(_user()->login){$d['entrar']='hide';}else{$d['sair']='hide';}
		_view('users/login',$d);
		//adicionando variáveis para o javascript
		_app('AREA','user');
		_app('ID','0');
	}
	
	//Página do usuário
	//TODO Criar página para usuário não logado ou que não é dono...
	function id($id=''){ //exit(_pt($_SESSION,false));
		$d['foto'] = 'noUser';
				
		if($id != '' && !is_numeric($id)){
			$id = _lib('Can')->decodCan($this->_escape($id)) - 360;
			$d	= _model('Users')->usuario($id);
		}
		//se não conseguiu achar o usuario: vai para o index	
		if(!is_array($d)){_goto('user');}
		//checando se a foto existe
		$d['foto'] = 'foto' . $d['foto'];
		if(!file_exists(PATH_WWW . 'img/users/' . $d['foto'] . '.jpg')){$d['foto'] = 'noUser';}
		
		$d['suser'] = 'class="selected"';
		
		//nome do usuário no título da página
		if(isset($d['nome'])) _app('titulo', $d['nome']);
		
		//mostrando a página certa
		if(_user()->login && $id == _user()->get(_user()->col_id)){
			//adicionando variáveis para o javascript
			_app('AREA', 'paginaPessoal');
			_app('ID', $id);
			
			_view('users/pagina', $d);}
		else{
			//adicionando variáveis para o javascript
			_app('AREA', 'user');
			_app('ID', $id);
			_view('users/usuario', $d);
		}	
	}
	
	//FastLogin
	function fastlogin($fl=''){
		if($fl!='' && _user()->fastLogin($fl)){_ajax('Olá, '._user()->get(_user()->col_name));}
		else{_ajax('Não foi possível fazer seu LOGIN!');}
	}
	//login
	function login(){
		if(_user()->login){_ajax('Você já está logado no sistema!'); return false;}
		if(isset($_POST['login']) && isset($_POST['senha'])){
			//checando se foi enviado o login (CAN) ou email
			if(strpos($_POST['login'],'@')===false){_user()->fastLogin($_POST['login'].$_POST['senha']);}
			else{_user()->login($_POST['login'],$_POST['senha']);}
			if(_user()->login){_ajax('ok');}else{_ajax('Não foi possível autenticar no sistema!!');}
		}else{_ajax('Você precisa digitar seu código de acesso e senha!');}
	}
	
	//logout
	function logout(){_user()->logout();_ajax('Você saiu do sistema!');}
	
	//précadastro
	function add(){
		$_POST=$this->_escape($_POST);
		//verificando se o email já está cadastrado no sistema
		if(_model('Users')->email_exists($_POST['mail'])){_ajax('Este EMAIL já está cadastrado!');}
		//inserindo o novo usuário		
		if($a=_model('users')->add($_POST)){
			//preparando para ennviar o e-mail de ativação
			$s=array('<neos:nome/>','<neos:login/>','<neos:codigo/>');
			$r=array($a['nome'],_lib('Can')->geraCan(360+$a['id']),$a['active']);
			$this->mail_body=str_replace($s,$r,_view('email/ativar','','',true));
			
			$para[$_POST['mail']]=$_POST['nome'];//destinatário
			$oculta['prbr@ymail.com']='Paulo Rocha';//cópia oculta
			
			if($this->_sendEmail($para,$oculta)){_ajax('ok');}
			else{//deletando a entrada
				_db()->query('DELETE FROM '._model('users')->table.' WHERE '._model('users')->col_active.'="'.$a['active'].'"');
				_ajax("Não consegui enviar o e-mail de ativação!\nIsso pode ocorrer se o e-mail informado não estiver correto.\n\nVerifique se o e-mail foi digitado corretamente ou tente fazer o cadastro mais tarde.");}	
		}
		else{_ajax("Erro no Banco de Dados ao gravar novo usuário!\nO administrador do site foi notificado da ocorrencia deste erro.\n\nPor favor tente mais tarde.");}
	}
	
	/**
	* ativação de cadastro
	*/
	function ativar($cod=''){
		$cod = $this->_escape($cod);
		if($cod == '') exit('Código de ativação inválido!');
		$a = _model('Users')->activate($cod);
		if($a == false) exit('Código de ativação não existe!');
		//redirecionando para a página do usuário (já logado).
		else{_goto('id/' . $a);}	
	}
	
	/**
	* troca de senha
	*/
	function trocarSenha($mail=''){		
		$mail = $this->_escape($mail);
		if($mail == '') _ajax('Email inválido!');
		if($a = _model('users')->trocarSenha($mail)){
			//preparando para ennviar o e-mail de ativação
			$s = array('<neos:nome/>','<neos:codigo/>');
			$r = array($a['nome'],$a['active']);
			$this->mail_body = str_replace($s, $r, _view('email/trocarsenha', '', '', true));
			
			$para[$a['mail']] = $a['nome'];//destinatário
			$oculta['prbr@ymail.com'] = 'Paulo Rocha';//cópia oculta
			
			$this->_sendEmail($para, $oculta);	
		}
		_ajax('ok');
	}
	
	/**
	* trocar a foto do usuário
	*/
	function trocaFoto(){		
		$file = PATH_WWW.'img/users/'.'foto'._user()->id.'.jpg';
		$mini = PATH_WWW.'img/users/'.'mini'._user()->id.'.jpg';
		move_uploaded_file($_FILES['foto']['tmp_name'], $file);
		//gerando miniatura
		_lib('Canvas')->carrega($file)
			->hexa('#000')
			->redimensiona(60,80,'preenchimento')
			->grava($mini,70);
		//Redimensionamento da foto
		_lib('Canvas')->carrega($file)
			->hexa('#000')
			->redimensiona(300,400,'preenchimento')
			->marca(PATH_WWW.'img/logo.png','baixo','centro')
			->grava($file,70);
		//Regarrega a página		
		_goto('id/'._lib('Can')->geraCan(360 + _user()->id));				
	}
	
	/**
	* pesquisar usuários
	*/
	function pesquisar(){
		$d=$this->_escape($_GET);
		if(!isset($d['pesquisar']) || !isset($d['onde']) || $d['pesquisar']==''){
			_ajax('É preciso digitar um termo para a pesquisa!');}		
		_ajax(_model('Users')->pesquisar($d['pesquisar'],$d['onde']));		
	}
	
	/**
	* salvando os dados do usuário
	*/
	function salvar(){
		if(!_user()->login){_goto();}
		//fazendo o update (usando o model)
		_model('users')->alterar($this->_escape($_POST, false)); 
		//Regarrega a página		
		_goto('id/'._lib('Can')->geraCan(360 + _user()->id));		
	}
	
	//Funções restritas (toda a função que começa com "_" ).............................
	
	/**
	* Enviando emails
	*/
	function _sendEmail($para=array(),$oculta=array()){
		_lib('mail')->Host=$this->mail_host;
		_lib('mail')->Body=$this->mail_body;
		_lib('mail')->Subject=$this->mail_titulo;
		_lib('mail')->From=$this->mail_origem;
		_lib('mail')->Fromname=$this->mail_nomeorigem;
		foreach($para as $k=>$v){_lib('mail')->AddAddress($k,$v);}
		foreach($oculta as $k=>$v){_lib('mail')->AddBCC($k,$v);}
		return _lib('mail')->Send();
	}

}