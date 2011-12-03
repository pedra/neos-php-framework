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
	<li><a href="<?php echo URL.$cfg->admin_url.'/ctrl';?>" >Criar um Controller</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/model';?>" >Criar um Model</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/helper';?>" >Criar um Helper</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/library';?>" >Criar uma Library</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/view';?>" class="selected">Criar uma View</a></li>
	<li><a href="<?php echo URL.$cfg->admin_url.'/logout';?>" >Logout (sair)</a></li>
</ul>
<!--recurso selecionado-->
<div class="conteudo">
	
	<form enctype="multipart/form-data" action="<?php echo URL.$cfg->admin_url.'/view';?>" method="post">
		<p>Caminho:</p>
		<p><input name="dir" type="text" value="<neos var="caminho"/>" readonly="true" size="70"/></p>
		<p>Nome do arquivo:</p>
		<p><input name="file" type="text" value="<neos var="file"/>" size="40" /><b>.html</b></p>
		<p>HEAD:</p>
		<p><textarea name="head" cols="100" rows="11"><neos var="head"/></textarea></p>
		<p>BODY:</p>
		<p><textarea name="body" cols="100" rows="18"><neos var="body"/></textarea></p>
		<p><label><input name="utf8" type="checkbox" value="1" /> Converter o arquivo para UTF-8 antes de salvar.</label></p>
		<p>Enviar arquivo:</p>
		<p><input name="loaded" type="file" size="50" /></p>
		<div class="atencao">
			<h3>Atenção!</h3>
			<ul>
				<li>Se você usar caracteres latinos (acentos) pode ser necessário converter para UTF-8.</li>
				<li>Ao enviar um arquivo, o nome dado ao arquivo será o nome indicado no campo &quot;Nome do arquivo&quot;.</li>
			</ul>
		</div>
		<p><input name="save" type="submit" value="Salvar" /></p>	
	</form>
</div>
<?php if(isset($alerta) && $alerta!=''){ echo "<script type=\"text/javascript\">alert('$alerta')</script>";}?>
</body>
</html>
