<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>NEOS PHP Framework</title>
<style>
*{margin:0; padding:0; font-family:Verdana, Arial, Helvetica, Lucida Grande, Verdana, Sans-serif;}
body{margin:20px auto; width:800px; background:#000 url(pub/?p=img/fd.png) repeat-x}
h1 { font-size:14px; color:#FFF}
h2{ padding:5px 0; font-size:9px; font-weight:normal; color:#999}
p { font-size:10spx; color:#999; padding:2px 0}
input { padding:2px 5px; margin-bottom:10px}
.conteudo { width:180px; margin:60px auto; color:#FDD; border:1px solid #FFF; font-size:11px; background:#000; padding:10px 10px;}
</style>
</head>
<body>
<div class="conteudo">
	<h1>NEOS PHP Framework</h1>
	<h2>CORE <?php echo _CAN;?></h2>
	<p>&nbsp;</p>
	<form action="<?php echo URL.$cfg->admin_url.'/';?>" method="post">
		<p>Usuário</p>
		<p><input name="user" type="text" /></p>
		<p>Senha</p>
		<p><input name="pass" type="password" /></p>
		<p><input name="" type="submit" value="Entrar..." /></p>
	</form>	
</div>
</body>
</html>
<?php exit();?>