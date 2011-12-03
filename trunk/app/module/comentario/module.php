<?php
/**
 * Module Comentário - gerenciamento de comentários para todos os documentos
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Module
 */

class Module_Comentario_Module
    extends  \Neos\Base {
	
	function get($p){
		if(!isset($p['book']) || !_app('ID')){return false;}	
		
		$com = _db()->query('SELECT COM_ID,COM_DATA,COM_CONTEUDO,USER_NOME 
										FROM COMENTARIO,USUARIO 
										WHERE COM_REF='._app('ID').' 
										AND USER_ID=COM_AUTOR');
		$c='';
		if($com){			
			foreach($com as $cm){
				$c.='<div id="com'.$cm->COM_ID.'" class="coment">				
				<div class="barra"><span class="user">'.$cm->USER_NOME.'</span><span class="date">'.$cm->COM_DATA;
				if(_user()->login && _user()->get('USER_GROUP')>9){$c.= '&nbsp;&nbsp;&nbsp;<img src="'._app('URL').'img/icones/cross.png" onclick="delComent('.$cm->COM_ID.')"/>';}
				$c.='</span></div></div>
				<div class="content">'.$cm->COM_CONTEUDO.'.</div>';	
			}
		}
		
		//Usuário logado?
		if(_user()->login){
			_addJs('ckeditor/ckeditor_basic.js');
			$c.='<div class="comentar">
				<p><b>Deixe seu comentário neste post:</b></p>
				<form method="post" action="'._app('URL_LINK').'inicial/coment/'._app('ID').'" />
					<input type="hidden" name="area" value="'._app('AREA').'"/>
					<textarea id="comentario" name="comentario" rows="2" cols="50"></textarea>
				</form>
			</div>';
		} else {$c.='<p class="quiet">Para comentar é preciso estar logado no sistema. <a href="'._app('URL_LINK').'user/cadastro/">Cadastre-se</a> em nosso site: é rápido, simples e gratuíto!</p>';}
		return $c;		
	}
	
}