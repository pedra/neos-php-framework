<?php
/**
 * Controller Screencast - Coleção de Video Casts
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Controller
 */

class Controller_Screencast extends NEOS
{

	function __construct(){
		$t = _user()->get('theme');
		if(!$t){ $t= 'zurox'; _user()->set('theme',$t);}
		_cfg()->template = $t;
		if($t=='basico'){ _viewVar('themas',array('basico'=>'Básico','zurox'=>'Zurox'));}
		else {_viewVar('themas',array('zurox'=>'Zurox','basico'=>'Básico'));}	
	}
	
	function index($id = 24)
	{
		//gravando o id da página para ser usado por outros blocos (módulos, views, etc).
		$id = 0 + $id; 
		//adicionando variáveis para o javascript
		_app('AREA', 'screencast');
		_app('ID', $id);
		//pegando o conteudo
		$q=_db()->query('SELECT CON_CONTEUDO,IND_ID,IND_TITULO
									FROM INDICE,CONTEUDO
									WHERE IND_ID="' . $id . '"
									AND IND_ID=CON_ID
									AND IND_CAT=(SELECT CAT_ID FROM CATEGORIA WHERE UPPER(CAT_NOME)="SCREENCAST" LIMIT 1)');
		if($q){
			$conteudo = $q[0]->CON_CONTEUDO;
			$id = $q[0]->IND_ID;
			$titulo = $q[0]->IND_TITULO;
		}else{
			$titulo = 'Este video não existe!';
			$id = 24;
			$conteudo = '';
		}
		//chamando as views e setando o menu principal para 'ScreenCast'
		_viewVar('sscreencast', 'class="selected"');
		_viewVar('area', _app('AREA'));
		_view('screencast', array('titulo'=>$titulo, 'video'=>str_replace('&quot;', '"', $conteudo), 'id'=>$id));
	}
	
	function video($id = 24){$this->index($id);}

}