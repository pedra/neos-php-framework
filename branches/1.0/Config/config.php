<?php
$cfg->uri=$_SERVER['REQUEST_URI'];
$cfg->get_ctrl='c';
$cfg->get_func='f';
$cfg->post_ctrl='c';
$cfg->post_func='f';
$cfg->static_view=false;
$cfg->session=true;
$cfg->use_db=false;
//APP
$cfg->ctrl=$cfg->app.'controllers'.SEP;
$cfg->view=$cfg->app.'views'.SEP;
$cfg->model=$cfg->app.'models'.SEP;
$cfg->library=$cfg->app.'librarys'.SEP;
$cfg->driver=$cfg->app.'drivers'.SEP;
$cfg->helper=$cfg->app.'helpers'.SEP;
$cfg->module=$cfg->app.'modules'.SEP;
//WEBROOT
if(!isset($cfg->web)){$cfg->web=dirname($cfg->app).SEP.'web'.SEP;}
$cfg->template_path=$cfg->web.'templates'.SEP;
$cfg->template_url='templates';
//ADMIN CORE SERVICE
$cfg->admin_user='neosAdmin';
$cfg->admin_pass=MD5('123456');
$cfg->admin_url='neoscoreadmin';
$cfg->admin_controller='control.php';
//REPORT
$cfg->status='display';
$cfg->error['action']='display';
$cfg->error['level']=E_ALL;
$cfg->logfile='log.txt';
$cfg->error_route=$cfg->admin_url.'/erro';
//DEFAULTS
$cfg->charset='utf-8';
$cfg->default->ctrl='inicial';
$cfg->default->func='index';
$cfg->default->args='';
$cfg->default->template='';
$cfg->default->db='sqlite_pdo';
//DB::MYSQL_PDO
$cfg->db->mysql_pdo->dsn='mysql:host=localhost;dbname=site';
$cfg->db->mysql_pdo->user='neos';
$cfg->db->mysql_pdo->pass='123456';
//DB::MYSQL
$cfg->db->mysql->driver='mysql';
$cfg->db->mysql->host='localhost';
$cfg->db->mysql->user='neos';
$cfg->db->mysql->pass='123456';
$cfg->db->mysql->database='site';
$cfg->db->mysql->charset='utf8';
//DB::SQLITE_PDO
$cfg->db->sqlite_pdo->driver='pdo';
$cfg->db->sqlite_pdo->dsn='sqlite2:'.$cfg->app.'neos.db';
//DB::SQLITE
$cfg->db->sqlite->driver='sqlite';
$cfg->db->sqlite->database=$cfg->app.'neos.db';
//DB::ORACLE
$cfg->db->oracle->driver='oracle';
$cfg->db->oracle->host='(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST = 10.160.1.66)(PORT=1521))(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME=mxgmx)))';
$cfg->db->oracle->user='neos';
$cfg->db->oracle->pass='123456';
$cfg->db->oracle->database='site';
$cfg->db->oracle->charset='utf8';
//DB::ORACLE_PDO
$cfg->db->oracle_pdo->dsn='oci:dbname=(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST = 10.160.1.66)(PORT=1521))(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME=mxgmx)))';
$cfg->db->oracle_pdo->user='';
$cfg->db->oracle_pdo->pass='';
//DB::POSTGRE_PDO
$cfg->db->postgres_pdo->dsn='pgsql:host=localhost port=5432 dbname=testdb user=bruce password=mypass';
$cfg->db->postgres_pdo->user='';
$cfg->db->postgres_pdo->pass='';