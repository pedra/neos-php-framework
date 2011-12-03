<?php
/**
 * Model Users - gerenciamento de usuários do site
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Model
 */

class Model_Users
	extends Neos\Library\User {
	
	function add($add){			
		//adiciona um novo usuário - fase pré: somente email, nome e senha para ser ativado por email.
		if(!isset($add['nome']) || !isset($add['senha']) || !isset($add['mail'])){return false;}
		//formatando os dados a serem inseridos
		$d['USER_DATA']=date('d/m/Y - H:i');
		$d[$this->col_name]=$add['nome'];
		$d[$this->col_pass]=md5($add['senha']);
		$d[$this->col_login]=$add['mail'];
		$d[$this->col_active]=md5(time())._lib('Can')->dataCan();
		//inserindo...
		_db()->insert($d, $this->table);
		//pegando o ID
		$q=_db()->query('SELECT '.$this->col_id.','.$this->col_name.','.$this->col_active.' 
							FROM '.$this->table.' 
								WHERE '.$this->col_active.'="'.$d[$this->col_active].'"');
		//formatando o retorno
		if($q){return array('id'=>$q[0]->{$this->col_id},
							'nome'=>$q[0]->{$this->col_name},
							'active'=>$q[0]->{$this->col_active});}
		else{return false;}		 
	}
	//troca de senha
	function trocarSenha($mail){
		$temp = md5(time()) . _lib('Can')->dataCan();
		//fazendo update
		_db()->update(array($this->col_active=>$temp), $this->col_login.'="'.$mail.'"', $this->table);
		//pegando os dados
		$q=_db()->query('SELECT '.$this->col_login.','.$this->col_name.','.$this->col_active.' 
							FROM '.$this->table.' 
							WHERE '.$this->col_active.'="'.$temp.'"
							AND '.$this->col_login.'="'.$mail.'"');		
		//formatando o retorno
		if($q){return array('nome'=>$q[0]->{$this->col_name},
							'active'=>$q[0]->{$this->col_active},
							'mail'=>$q[0]->{$this->col_login});}
		else{return false;}
	}
	
	//verifica se um email já está cadastrado - o email deve ser indicado na classe user como COL_LOGIN
	function email_exists($email,$col=''){
		if($col==''){$col=$this->col_login;}
		if(_db()->query('SELECT '.$col.' FROM '.$this->table.' WHERE '.$col.'="'.$email.'"')){return true;}else{return false;}}
	
	//ativar o usuário - retorna o codigo do usuário ou false	
	function activate($cod){		
		$q=_db()->query('SELECT ' . $this->col_id . ',' . $this->col_pass . '
							FROM ' . $this->table . ' 
							WHERE ' . $this->col_active . '="' . $cod . '"
							AND ' . $this->col_active . '!="S"');
		if($q){
			$id = $q[0]->{$this->col_id};
			$login = _lib('Can')->geraCan(360 + $id);
			_db()->update(array($this->col_active=>'S'), $this->col_id . '=' . $id, $this->table);
			//fazendo o Login
			if($this->login($id, '', $this->col_id, '', true)){
			return $login;}}
		return false;
	}	
	
	/**
	* pegando os dados do usuario para a página pessoal
	*/
	function usuario($id){
		$id += 0;
		if($this->login && $id == $_SESSION['DB'][$this->col_id]){
			$a = $_SESSION['DB'];
			$b['foto'] = $b['id'] = $a[$this->col_id];
			$b['nome'] = $a[$this->col_name];
			$b['sobre'] = $a['USER_SOBRE'];
			$b['contato'] = $a['USER_CONTATO'];
			$b['tags'] = $a['USER_TAGS'];
			$b['cidade'] = $a['USER_CIDADE'];
			$b['estado'] = $a['USER_ESTADO'];
			$b['pais'] = $a['USER_PAIS'];
			return $b;			
		}
		//pegando o LOGIN (email)
		$q = _db()->query('SELECT ' . $this->col_login . ' 
							FROM ' . $this->table . ' 
							WHERE ' . $this->col_id . '="' . $id . '"');
		if($q){
			$a = $this->getAll($q[0]->{$this->col_login});
			$b['foto'] = $b['id'] = $a[$this->col_id];
			$b['nome'] = $a[$this->col_name];
			$b['sobre'] = $a['USER_SOBRE'];
			$b['contato'] = $a['USER_CONTATO'];
			$b['tags'] = $a['USER_TAGS'];
			$b['cidade'] = $a['USER_CIDADE'];
			$b['estado'] = $a['USER_ESTADO'];
			$b['pais'] = $a['USER_PAIS'];
			return $b;
		}
		else{return false;}		
	}
	
/*	pesquisando por usuários
	P = string a pesquisar
	D = string com os campos...*/
	function pesquisar($p,$d){
		$a=array();
		if(strpos($d,'nome')!==false){$a[]=$this->col_name;}
		if(strpos($d,'local')!==false){$a[]='USER_PAIS';$a[]='USER_ESTADO';$a[]='USER_CIDADE';}
		if(strpos($d,'tags')!==false){$a[]='USER_TAGS';}
		$a[]='USER_SOBRE';
		//formatando o termo de pesquisa		
		$pa = explode(' ',$p);		
		$w = ' WHERE 1=2 ';
		foreach($pa as $va){foreach($a as $v){$w.=' OR '.strtoupper($v).' LIKE "%'.$va.'%" ';}}
		//pesquisando no BD
		//return 'SELECT '.$this->col_id.','.$this->col_name.' FROM '.$this->table.' '.$w.' LIMIT 20';
		$q = _db()->query('SELECT '.$this->col_id.','.$this->col_name.' FROM '.$this->table.' '.$w.' LIMIT 20');		
		if($q){
			$r='<p>';
			foreach($q as $v){
				$id=_lib('Can')->geraCan(360+$v->{$this->col_id});	
				$img = (file_exists(PATH_WWW.'img/users/mini'.$v->{$this->col_id}.'.jpg')) ? $v->{$this->col_id} : '0' ;		
				$r.=' <a href="'.URL.'id/'.$id.'"><img src="'.URL.'img/users/mini'.$img.'.jpg" width="60" height="80" title="'.$v->{$this->col_name}.'" /></a> ';					
			}
			$r.='</p>';
			return $r;			
		}else{return 'Nenhum resultado para o termo:<br />"'.$p.'".<br />Tente fazer uma pesquisa mais detalhada, com outros termos.';}		
	}
	
	
	/**
	* Alterando os dados cadastrais do usuário
	*
	*/
	function alterar($dados = array()){
		if(!is_array($dados) || count($dados) < 1 ){ return false;}
		
		//Conformando os dados
		$d['USER_PAIS'] = $dados['pais'];
		$d['USER_ESTADO'] = $dados['estado'];
		$d['USER_CIDADE'] = $dados['cidade'];
		$d['USER_SOBRE'] = $dados['sobre'];
		$d['USER_TAGS'] = $dados['tags'];
		$d['USER_CONTATO'] = $dados['contatos'];
		
		$senha = strtoupper(trim($dados['senha']));
		if($senha != '') $d['USER_PASS'] = md5($senha);
		
		//exit(_pt($d,false). ' --- ' . $senha);
		
		//update no banco de dados
		_db($this->db)->update($d, $this->col_id . ' = "' . $this->login . '"', $this->table);
		
		//recarregando os dados da SESSION
		$_SESSION['DB'] = $this->getAll();
	}	
		
}