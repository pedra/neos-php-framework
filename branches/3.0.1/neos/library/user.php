<?php

/**
 * Gerencimento de Usuário.
 * Também pode ser entendido como gerenciamento de acesso ao site. Representa/retorna os parâmetros do browser (ou robot) usado para acessar o site.
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Neos\Library
 * @access 		public
 * @since		CAN : B4BC
 */
 
 
/* 	USER OBJECT
  Esta classe implementa um objeto USER que contem as definições do usuário do site/aplicação corrente.
  Para a preservação dos dados entre sessões se fáz necessário o uso de um banco de dados escolhido pelo desenvolvedor ou um bd em Sqlite.
  As configurações para o USER estão no arquivo '/neos/config/geral.php'.
  Os seguintes parâmetros podem ser configurados: identificação, autenticação, permissão, rastro (logs), ciclo vital, categoria, bagagens, etc.

  PARA USAR
  Você precisa criar uma tabela no banco de dados com, pelo menos, os seguintes campos:

  CREATE TABLE 'USUARIO' (
  USER_ID integer primary key,
  USER_IDKEY varchar(100),
  USER_LOGIN varchar(10),
  USER_PASS varchar(100),
  USER_ACTIVE varchar(100) default 'N',

  -- crie outros campos a seu critério
  );
  Atenção: USER_ID não é usado por essa classe, portanto, é opcional usar um identificador único (ID). Lembre-se que o campo USER_LOGIN também pode ser considerado como um identificador, pois DEVE ser único.
  Estes são os nomes padrões (USUARIO, USER_ID, etc). Para usar seus próprios nomes de campos e tabela (aproveitando uma tabela já existente) você deve indicar os nomes de tabela, banco de dados (alias) e campos no arquivo de configuração do NEOS (config.php).
  Assim:

  $cfg->user->db 			= 'db_alias';
  $cfg->user->table 		= 'USUARIO';
  $cfg->user->col_id		= 'USER_IDKEY';
  $cfg->user->col_login		= 'USER_LOGIN';
  $cfg->user->col_pass		= 'USER_PASS';
  $cfg->user->col_active	= 'USER_ACTIVE';

  Além dos parâmetros acima temos:

  $cfg->user->load		= false;	//carregamento automático da classe (opcional)
  $cfg->user->life		= 18000;	//tempo de vida da sessão do usuário (em segundos)
  $cfg->user->use_db	= true;		//para melhorar a performance você pode trabalhar somente com os dados da sessão (use_db=false). Neste caso, se houver alguma alteração dos dados do usuário por outro processo, a atualização será feita somente no próximo login...

  PARA CHAMAR
  Use a função '_user()' do helper 'functions' para acessar as funções e parâmetros desta classe.
  Ex.: if(_user()->login){echo 'Você está logado no sistema';}
  Ex.: if(_user()->login){echo 'Olá, '._user()->getDb('USER_NOME').'!<br/>Você está logado no sistema.';}

  TODO: construir, testar, etc...

  27-10-2010	Verificar as seguintes funções: login / get / set /
  27-10-2010	Criação da classe secundária NEOS_USER_EXTRA -> com as funções para manipulações tipo: add, delete, ativar, emailList, etc...
  11-11-2010	Ajuste no código SQL das funções '_dbTimelife' e '_dbInit' --> foram simplificados
  18-11-2010	Adicionada a classe ao novo site do NEOS para testes. Primeiros testes OK! - aumentado o timeLIFE para 5 minutos, criado uma rotina de sincronização no controller ajax (logado()) e no javascript para analizar o status do usuário no controller 'admin'.
  24-02-2011	Adicionado o parâmetro 'db' (public $db='') para apontar o alias do banco de dados com a tabela dos usuários. Atualizada a chamada a banco de dados para o novo formato: _db(alias)->function().

 */
 
namespace Library;

class User
	extends \NEOS {

    public $db = '';
    public $table = 'USUARIO';
    public $col_id = 'USER_ID';
    public $col_login = 'USER_EMAIL';
    public $col_pass = 'USER_PASS';
    public $col_name = 'USER_NOME';
    public $col_active = 'USER_ACTIVE';
    public $life = 900;
    public $login = false;

    function __construct() {
        if (!session_id()) {
            session_start();
        }
        //carregando as configurações...
        $this->_config();
        //checando o TimeLife
        $this->_timeLife();
    }

    /**
	* carrega os parametros de configuração
	*/
    function _config() {
        $this->db = '';
        if (isset(\_cfg::this()->user) && is_object(\_cfg::this()->user)) {
            foreach (\_cfg::this()->user as $k => $v) {
                $this->{$k} = $v;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
	* Login
	*/
	function login($login = '', $senha = '', $clogin = '', $csenha = '', $force = false) {
        $login = strtoupper($this->_escape($login));
        $senha = md5($senha);
        //escolhendo os campos de login e senha
        $col_login = ($clogin != '') ? $clogin : $this->col_login;		
        $col_pass = ($csenha != '') ? $csenha : $this->col_pass;
		
        //força um login sem senha
		$wsenha = ($force) ? '' : 'AND ' . $col_pass . '="' . $senha . '"';
		
        //buscando no BD
        $q = _db($this->db)->query('SELECT * FROM ' . $this->table . '
									WHERE UPPER(' . $col_login . ')="' . $login . '"
									' . $wsenha . '
									AND ' . $this->col_active . '="S"');
        if ($q) {
            //carregando TODOS os dados para acesso rápido
            foreach ($q[0] as $k => $v) {
                if ($k == $this->col_pass) {
                    continue;
                }
                $_SESSION['DB'][$k] = $v;
            }
            $this->login = true;
            $this->id = $_SESSION['DB'][$this->col_id];
            $_SESSION['login'] = true;
            $_SESSION['life_time'] = $this->life + time();
            return true;
        } else {
            return false;
        }
    }

    /**
	* FASTLOGIN
    * $login 	=> login + senha -> na mesma string
    * $len 	=> tamanho da parte LOGIN do fastlogin | a parte SENHA (PASSWORD) é o restante da string.
    * O login é calculado usando o CAN do ID do BD. 
	*/
    function fastLogin($login='', $len=3) {
        $login = strtoupper(trim($login));
        if ($login == '') return false;
		$len = 0 + $len;
        $log = substr($login, 0, $len);
        $log = _lib('Can')->decodCan($log, $len) - 360;
        $senha = substr($login, $len);
        return $this->login($log, $senha, $this->col_id);
    }

    /**
	* Alias para logoff
	*/
    function logout() {
        $this->logoff();
    }

    function logoff() {
        if (!session_id()) {
            session_start();
        }
        //matando todas as variáveis da sessão
        $_SESSION = array();
        //destruindo o cookie da sessão (no navegador)
        if (isset($_COOKIE[session_name()])) setcookie(session_name(), '', time() - 42000, '/');
        // Finally, destroy the session.
        session_destroy();
        //redefine (envia cookie tambem) a sessão atual
        session_regenerate_id();
        //resetando o login e id ...
        $this->login = false;
        $this->id = false;
        $_SESSION['login'] = false;
    }

    //--------------------------------------------------------------------------------------------

    /**
	* pega a variável indicada na classe ou na sessão
	*/
    function get($var) {
        if (isset($this->{$var})) return $this->{$var};
        if (isset($_SESSION[$var])) return $_SESSION[$var];
        if (isset($_SESSION['DB'][$var])) return $_SESSION['DB'][$var];
        return false;
    }

    /**
	* pega a coluna indicada no DB (usuario atual ou o indicado)
	*/
    function getDb($col, $login='') {
        $col = strtoupper(trim($col));
        if ($login == '') {
            $q = _db($this->db)->query('SELECT ' . $col . ' FROM ' . $this->table . ' WHERE ' . $this->col_id . '="' . $_SESSION['DB'][$this->col_id] . '"');
        } else {
            $q = _db($this->db)->query('SELECT ' . $col . ' FROM ' . $this->table . ' WHERE UPPER(' . $this->col_login . ')="' . strtoupper(trim($login)) . '"');
        }
        if ($q) {
            return $q[0]->{$col};
        } else {
            return false;
        }
    }

    /**
	* pega TODOS os dados do DB (usuario atual ou o indicado)
	*/
    function getAll($login='') {
        if ($login == '' && isset($_SESSION['DB'])) {
            $q = _db($this->db)->query('SELECT * FROM ' . $this->table . ' WHERE ' . $this->col_id . '="' . $_SESSION['DB'][$this->col_id] . '"');
        } else {
            $q = _db($this->db)->query('SELECT * FROM ' . $this->table . ' WHERE UPPER(' . $this->col_login . ')="' . strtoupper(trim($login)) . '"');
        }
        if ($q) {
            foreach ($q[0] as $k => $v) {
                $r[$k] = $v;
            }return $r;
        } else {
            return false;
        }
    }

    /**
	* seta uma variável da classe ou sessão (se não existe cria na sessão)
	*/
    function set($var, $val=true) {
        if (isset($this->{$var})) return $this->{$var} = $val;
        return $_SESSION[$var] = $val;
    }

    /**
	* seta um campo no DB - setDb(array('NOME'=>'Novo Nome'));
	*/
    function setDb($col='', $login='') {
        if (!is_array($col)) return false;
        if ($login == '') {
            $where = $this->col_id . '="' . $this->id . '"';
        } else {
            $where = 'UPPER(' . $this->col_login . ')="' . strtoupper(trim($login)) . '"';
        }
        return _db($this->db)->update($col, $where, $this->table);
    }
	

    //utilitários --------------------------------------------------------------------------------------------
    function getIp() {
        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        } else {
            return false;
        }
    }

    function _includeAgent() {
        if (!isset($this->_user_agents)) {
            $this->_user_agents = include (PATH_NEOS . '/neos/config/user_agents.php');
        }
    }

    function _searcUserAgent($type, $in='') {
        if ($in == '') $in = 'HTTP_USER_AGENT';
		if (isset($_SERVER[$in])) {
            $this->_includeAgent();
			$uag = $_SERVER[$in];
			$ver = '';
            foreach ($this->_user_agents[$type] as $k => $v) {
				$t = strpos(strtoupper($uag), strtoupper($k));
                if ($t !== false) {
					if($type == 'browsers'){
						$t += strlen($k);
						if($uag[$t] == '/' || $uag[$t] == ' '){ 
							for($i = $t +1; $i < strlen($uag) ; $i++){
								if($uag[$i] == ' ' || $uag == '/') break;
								$ver .= $uag[$i];							
							}
						}
						$b['browser'] = $v;
						$b['version'] = $ver;
						return $b;
					} else { return $v;}
                    break;
                }
            }
        }return false;
    }

    function getBrowser() {
        return $this->_searcUserAgent('browsers');
    }

    function getOs() {
        return $this->_searcUserAgent('platforms');
    }

    function getRobot() {
        return $this->_searcUserAgent('robots');
    }

    function getMobile() {
        return $this->_searcUserAgent('mobiles');
    }

    /**
	* checa se a linguagem é suportada pelo usuário/browser
	*/
    function getLang($l='') {
        if ($l == '') {
            return $this->_searcUserAgent('lang', 'HTTP_ACCEPT_LANGUAGE');
        }if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strrpos(strtoupper($_SERVER['HTTP_ACCEPT_LANGUAGE']), strtoupper($l)) !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
	* verifica se o charset é compatível com o usuário/browser
	*/
    function getCharset($c='') {
        if ($c == '') $c = \_cfg::this()->charset;
		
		if (isset($_SERVER['HTTP_ACCEPT_CHARSET']) && strrpos(strtoupper($_SERVER['HTTP_ACCEPT_CHARSET']), strtoupper($c)) !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
	* retorna o método da requisição (POST/GET)
	*/
    function getMethod() {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return $_SERVER['REQUEST_METHOD'];
        } else {
            return false;
        }
    }

    /**
	* ajustando o tempo de vida 
	*/
    function _timeLife() {
        //checando se existe...
        if (isset($_SESSION['login']) && isset($_SESSION['life_time'])) {
            $this->login = true;
            $this->id = $_SESSION['DB'][$this->col_id];
            //checando o lifeTime
            if ($_SESSION['life_time'] < time()) {
                //age como logado e extende o life_time
                $this->login = true;
                $_SESSION['login'] = true;
                $_SESSION['life_time'] = time() + $this->life;
            }
        } else {
            $this->login = false;
            $_SESSION = array();
        }
    }

}