<?php
/**
 * Controller Inicial - página inicial e página do manual
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Controller
 */


class Controller_Inicial
	extends NEOS {
		
	function __construct(){
		$t = _user()->get('theme');
		if(!$t){ $t= 'zurox'; _user()->set('theme',$t);}
		_cfg()->template = $t;
		if($t=='basico'){ _viewVar('themas',array('basico'=>'Básico','zurox'=>'Zurox'));}
		else {_viewVar('themas',array('zurox'=>'Zurox','basico'=>'Básico'));}	
	}


	function index() {
		
		_view::set('home','','home');
		
		//adicionando variáveis para o javascript
        _app('AREA','home');
        _app('ID','3');
		
	}


	//Mostra as páginas do manual
	function manual($st1='',$st2='',$st3='')
	{ 
	
		$titulo = '';		//Titulo da página do manual
		$link = '';		//link gravado no BD

		//decodificando o(s) parâmetro(s) de entrada - argumentos da função
		//O MANUAL pode ser referenciado pelo ID ou pelo pseudo caminho (ex.: www.site.com/manual/tópico/titulo da página )
		if(!is_numeric($st1)){
			$v1 = array('á','Á','ã','Ã','õ','Õ','é','É','ç','Ç');
			$v2 = array('a','A','a','A','o','O','e','E','c','C');
			//convertendo caracteres latinos
			$st1a = str_ireplace($v1,$v2,$st1);
			$st2a = str_ireplace($v1,$v2,$st2);
			$st3a = str_ireplace($v1,$v2,$st3);

			if($st1a == ''){$st1a = 'Introducao'; $st1 = 'Introdução';}
			$link = '';
			//criando o breadcumbs - pseudo link de referencia para a navegação do usuário
			if($st1 != ''){$link = $st1a;}
			if($st2 != ''){$link = $st2a;}
			if($st3 != ''){$link = $st3a;}
			//pegando o link de referencia do DB (campo IND_LINK)
			$link = strtoupper(utf8_decode($link));
		}

		if(!is_numeric($st1)){$st1 = 0;}
		//pegando a página do manual baseado no link ou id.
		$q = _db::query('SELECT CON_CONTEUDO,IND_ID,IND_TITULO
									FROM INDICE,CONTEUDO
									WHERE (UPPER(IND_LINK)="' . $link . '" OR IND_ID="' . $st1 . '")
									AND IND_ID=CON_ID
									AND IND_CAT=1');
		if($q){
			$conteudo = $q[0]->CON_CONTEUDO;
			$id = $q[0]->IND_ID;
			$titulo = $q[0]->IND_TITULO;
		}else{$id = 1; $conteudo = $link . '<br>Esta página não existe!';}

		//mostrar a barra de edição do manual (somente administrador)
		if(_user()->login) {
			_addJs('ckeditor/ckeditor_basic.js');
			if(_user()->get('USER_GROUP') > 9){
				_viewVar('tools','<span class="tools">
					<input type="submit" id="salvar" onclick="return submitConteudo()" value="" title="Salvar auterações"/>
					<input type="button" id="cancelar" value="" title="Cancelar a edição"/>
					<input type="button" id="editar" value="" title="Editar..." />
				</span>');
			}
		}
		//gravando o id da página do manual para ser usado por outros blocos (módulos, views, etc).
		_app('ID', $id);
		_app('AREA', 'manual');
		//chamando as views e setando algumas variáveis
		_view::value('smanual', 'class="selected"');
		_view::value('area', _app('AREA'));
		
		_view::set('manual', array('titulo'=>$titulo,'conteudo'=>str_replace('&quot;','"',$conteudo),'id'=>$id));
	}

	//Atualiza os dados no BD
	function salvar($id = 1){
		if(_user()->login && _user()->get('USER_GROUP')>9){
			$id = 0 + $id ;
			$cont['CON_CONTEUDO'] = str_replace('"', '&quot;', $_POST['conteudo']);
			_db()->update($cont, 'CON_ID=' . $id, 'CONTEUDO');
			//gravando log			
			_db()->insert(array(
							'LOG_USER'=>_user()->id,
							'LOG_ACTION'=>'Alteração em página: '.$id), 
							'LOGS');
			_ajax('Alterado com sucesso!');
		}else{_ajax('Você não tem permissão para editar!');}
	}

	//Grava os comentários da página
	function coment($id = 1){
		if(_user()->login){
			$id = 0 + $id;
			$d['COM_REF']		= $id;
			$d['COM_CONTEUDO']	= $_POST['comentario'];
			$d['COM_AUTOR']		= _user()->get('USER_ID');
			_db::insert($d, 'COMENTARIO');
			_goto($_POST['area'] . '/'. $id);
			//_ajax('Comentário salvo.');
		}else{
			_goto($_POST['area'] . '/' . $id);
			//_ajax('Você não pode enviar comentários sem estar logado!');
		}
	}

	//Apagar um comentário
	function delComent($id = 0){
		if(_user()->login && _user()->get('USER_GROUP') > 9){
			$id = 0 + $id;
			if($id != 0){_db::query('DELETE FROM COMENTARIO WHERE COM_ID=' . $id);}
			_ajax('Comentário apagado!');
		}else{_ajax('Você não tem permissão para apagar comentários!');}
	}

	//Troca do Tema do site
	function changeTheme(){
		if(isset($_POST['theme']) && _user()->login){
			//grava na sessão
			_user()->set('theme', $this->_escape($_POST['theme']));
			//refresh
			_ajax('ok');
		} else {
			//erro
			_ajax('erro');
		}
	}

}