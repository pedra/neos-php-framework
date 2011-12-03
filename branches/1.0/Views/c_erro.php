<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>NEOS PHP Framework - CORE SERVICE</title>
<link href="<?php echo URL.$cfg->admin_url.'/';?>pub/?p=css/install.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>NEOS PHP Framework</h1>
<h2>CORE SERVICE (<?php echo _CAN;?>)</h2>
<!--menu esquerdo-->
<ul class="dmenu">
	<li><a href="<?php echo URL;?>">Aplicação (site)</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/install';?>">Instalar Aplicação</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/ctrl';?>">Criar um Controller</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/model';?>">Criar um Model</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/helper';?>">Criar um Helper</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/library';?>">Criar uma Library</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/view';?>">Criar uma View</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/logout';?>">Logout (sair)</a></li>
</ul>
<!--recurso selecionado-->
<div class="conteudo">	
	<div class="erro">
		<h3>Ocorreu um erro inesperado com essa aplicação!</h3>	
	</div>	
</div>
</body>
</html>