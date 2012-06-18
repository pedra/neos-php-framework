<?php
/** 
 * Configuração Geral 
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Config
 */

//DEFAULTS
$cfg->charset				= 'utf-8';		//charset do projeto
$cfg->ctrl					= 'inicial';	//controller inicial
$cfg->func					= 'index';		//função default
$cfg->args					= '';			//pseudo argumentos (opcional)

//GERAL
$cfg->uri					= '';
$cfg->timezone				= 'America/Sao_Paulo';
$cfg->get_ctrl				= 'c';    //variavel que contém o controller a ser chamado (método GET)
$cfg->get_func				= 'f';    //variavel que indica a função (GET)
$cfg->post_ctrl				= 'c';   //variavel que contém o controller a ser chamado (método POST)
$cfg->post_func				= 'f';   //variavel que indica a função (POST)
$cfg->use_db				= false;    //usar DataBase para os registros do NEOS (config,sessions,etc)

//SESSIONS
$cfg->session				= false;   //auto-start session
$cfg->session_life			= 180;   //tempo de vida da sessão - em minutos (default: 180)

//CACHE
$cfg->cache_time			= 000;   //tempo de cache para a classe NESO_CACHE (em segundos)
$cfg->cache_log				= 'cache.txt'; //log do CACHE

//OUTPUT
$cfg->static_view			= true;  //habilitação de views estaticas
$cfg->out_expires			= 24 * 3600;  //expiração das páginas processadas [neos_output()] em segundos
$cfg->out_compress			= false;  //ativa a compactação da saída (somente Views)
$cfg->out_filter			= false;   //ativa a filtragem da saida para "head" e "body"
$cfg->out_restricted		= false;	//se TRUE mostra somente a saída processada pela classe DocFactory 

//HTML
$cfg->html->doctype			= 'xhtml1-trans';
$cfg->html->type			= 'xhtml';
$cfg->pathCss				= PATH_WWW . 'css' . DS;
$cfg->urlCss				= 'css/';
$cfg->pathJs				= PATH_WWW . 'js' . DS;
$cfg->urlJs					= 'js/';

//TEMPLATES
$cfg->template				= '';		//template ativo (sem barras)
$cfg->template_path			= PATH_WWW . 'templates' . DS;      //diretório dos templates
$cfg->template_url			= 'templates';          //URL de acesso aos templates  (sem barras no inicio e fim)

//AppJsFramework
$cfg->jsfw->active			= true;
$cfg->jsfw->prefix			= 'neos_';
$cfg->jsfw->filename		= 'neosJsFw.js';
$cfg->jsfw->url				= 'js/' . $cfg->jsfw->filename;
$cfg->jsfw->path			= PATH_WWW . 'js/' . $cfg->jsfw->filename;

//ADMIN CORE SERVICE
$cfg->admin_user			= 'neosAdmin';
$cfg->admin_pass			= MD5('123456');
$cfg->admin_url				= '12702341910a53c77d8f6e354b7dc5f2'; //'neoscoreadmin';
$cfg->admin_controller		= 'control';

//REPORT
$cfg->mode					= 'test';    //pode ser production ou test (desenvolvimento)
$cfg->status				= 'displayextended';    //mostra a barra de status - opções: 'displayfileextended'
$cfg->error['action']		= 'display'; //ação em caso de erro - opções: 'displayfileroutemail'
$cfg->error['level']		= E_ALL;    //nivel dos erros reportados/ignorados
$cfg->logfile				= PATH_APP . '/log_test.html';   //arquivo de log de erros (se 'action' contiver 'file')
$cfg->error_route			= 'usererror';   //ir para este controller se houver erros (se 'action' contiver 'route')

//USER OBJECT
$cfg->user->db				= '';  //habilita a classe NEOS_USER
$cfg->user->table			= 'USUARIO';   //nome da tabela de usuarios