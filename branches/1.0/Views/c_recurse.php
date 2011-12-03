<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>NEOS PHP Framework - CORE SERVICE</title>
<script type="text/javascript" src="<?php echo URL.$cfg->admin_url.'/';?>pub/?p=js/jquery.js"></script>
<script type="text/javascript" src="<?php echo URL.$cfg->admin_url.'/';?>pub/?p=js/ctrl.js"></script>
<link href="<?php echo URL.$cfg->admin_url.'/';?>pub/?p=css/install.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>NEOS PHP Framework</h1>
<h2>CORE SERVICE (<?php echo _CAN;?>)</h2>
<neos var="msg" class="msg"/>
<!--menu esquerdo-->
<ul class="dmenu">
	<li><a href="<?php echo URL;?>" >Aplicação (site)</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/install';?>">Instalar Aplicação</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/ctrl';?>"  class="<neos var="ctrls"/>">Criar um Controller</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/model';?>" class="<neos var="model"/>">Criar um Model</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/helper';?>" class="<neos var="helper"/>">Criar um Helper</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/library';?>" class="<neos var="library"/>">Criar uma Library</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/view';?>" class="<neos var="view"/>">Criar uma View</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/logout';?>" >Logout (sair)</a></li>
</ul>
<!--recurso selecionado-->
<div class="conteudo">	
	<form enctype="multipart/form-data" action="<?php echo URL.$cfg->admin_url.'/'.$recurse;?>" method="post">
		<p>Caminho:</p>
		<p><input name="dir" type="text" value="<neos var="caminho"/>" readonly="true" size="70"/></p>
		<p>Nome do arquivo:</p>
		<p><input name="file" type="text" value="<neos var="file"/>" size="40" /><b>.php</b></p>
		<p>Conteúdo:</p>
		<p><textarea name="content" cols="105" rows="15"><neos var="content"/></textarea></p>
		<p><label><input name="utf8" type="checkbox" value="1" /> Converter o arquivo para UTF-8 antes de salvar.</label></p>
		<p>Enviar arquivo:</p>
		<p><input name="loaded" type="file" size="50" /></p>
		<div class="atencao">
			<h3>Atenção!</h3>
			<ul>
				<li>O nome da CLASSE (em conteúdo) deve&nbsp;ser o mesmo nome dado ao arquivo, porém, começando com a primeira letra maiúscula na definição da classe&nbsp;e em minúsculas para o nome do arquivo.</li>
				<li>Para o caso de HELPER, o nome do arquivo e da própria função (helper) devem estar em letras minúsculas.</li>
				<li>Se você usar caracteres latinos (acentos) pode ser necessário converter para UTF-8.</li>
				<li>Ao enviar um arquivo, o nome dado ao arquivo será o nome indicado no campo &quot;Nome do arquivo&quot;. Valem as mesmas regras do primeiro ítem acima.</li>
			</ul>
		</div>				
		<p><input name="save" type="submit" value="Salvar" /></p>
	</form>
</div>
<?php if(isset($alerta) && $alerta!=''){ echo "<script type=\"text/javascript\">alert('$alerta')</script>";}?>
</body>
</html>