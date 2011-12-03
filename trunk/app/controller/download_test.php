<?php
/**
 * Controller Downloads - download de versões e manuais do NEOS
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Controller
 * @subpackage	Teste
 */
	
class Controller_Download extends NEOS
{

	function __construct(){
		$t = _user()->get('theme');
		if(!$t){ $t= 'zurox'; _user()->set('theme',$t);}
		_cfg()->template = $t;
		if($t=='basico'){ _viewVar('themas',array('basico'=>'Básico','zurox'=>'Zurox'));}
		else {_viewVar('themas',array('zurox'=>'Zurox','basico'=>'Básico'));}	
	}
	
	function index()
	{
		_viewVar('sdownload','class="selected"');
		_view('download');
		//adicionando variáveis para o javascript
		_app('AREA','downloads');
		_app('ID','0');
		_app('URL', URL_LINK);
	}
	
	//Download do arquivo selecionado ($id)
	function id($id){
		//tratando o index ($id)		
		$id = 0 + $id;
		
		//ARQUIVOS
		$file[1]['local']='files/core.zip';
		$file[1]['name']='neos.zip';

		$file[2]['local']='files/core.neos';
		$file[2]['name']='core.neos';

		$file[3]['local']='files/app.zip';
		$file[3]['name']='app_neos.zip';

		//MANUAIS
		$file[4]['local']='files/manual.pdf';
		$file[4]['name']='Manual do Usuario.pdf';

		//$file[5]['local']=URL.'files/config.pdf';
		//$file[5]['name']='Configurando o NEOS.pdf';

		if(!isset($file[$id]['name'])){exit('This file doesn\'t exist!!');}

		//gravando o log de download
		//_model('access')->setAccess('download','D',$file[$id]['name']);

		//fazendo o download usando um helper
		//$this->_download('',$file[$id]['name'],file_get_contents($file[$id]['local']));
		
		//exit( _app('URL').$file[$id]['local'] );
		_goto($file[$id]['local']);
	}
}