<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<neos:charset/>" />
<title>NEOS PHP Framework</title>
<style>
*{margin:0; padding:0; font-family:Lucida Grande, Verdana, Sans-serif;}
body{margin:20px auto; width:600px; background:#890}
h1 { font-size:26px; color:#EF9}
h2{ font-size:16px; margin:20px 0; color:#ABC;}
p { font-size:11px; margin-bottom:5px; color:#EE9}
a { text-decoration:none; color:#FF5}
a:hover { text-decoration:underline}
table { margin:10px 0;}
table tr th { color:#000; text-align:left; padding:5px; border-bottom:1px solid #DDD; font-weight:bold}
table tr td { border-bottom:1px solid #DDD; padding:5px;}
.info { margin:10px auto; padding:10px; background:#FFE; font-family:Courier, monospace; font-size:11px; color:#333; border:1px solid #EEE}
.info .red td { font-weight:bold;color:#933; font-size:12px; background:#FEE}
</style>
</head>

<body>
<h1>NEOS PHP Framework</h1>
<h2>Bem vindo!</h2>
<p>Veja na tabela abaixo algumas informa&ccedil;&otilde;es sobre este sistema.</p>
<div class="info">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><th colspan="2">SERVIDOR</th></tr>
  <tr>
    <td width="130">Vers&atilde;o</td>
    <td><?php echo $_SERVER['SERVER_SOFTWARE'].' / '.php_uname();?>&nbsp;</td>
  </tr>
  <tr <?php if(!in_array('mod_rewrite',apache_get_modules())){echo 'class="red"';
  }?>>
    <td>M&oacute;dulos (Apache) </td>
    <td><?php if(in_array('mod_rewrite',apache_get_modules())){echo 'M&oacute;dulo REWRITE carregado!';}else{echo '&Eacute; preciso habilitar o m&oacute;dulo rewrite!!';}?>&nbsp;</td>
  </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><th colspan="2">PHP</th></tr>
  <tr <?php if (version_compare(PHP_VERSION, '5.3.0', '<')){echo 'class="red"';
  }?>>
    <td width="130">Vers&atilde;o</td>
    <td><?php echo phpversion();?>&nbsp;</td>
  </tr>  
  <tr>
    <td>Include Path</td>
    <td><?php echo get_include_path();?>&nbsp;</td>
  </tr>
  <tr>
    <td>Arquivo 'php.ini'</td>
    <td><?php echo php_ini_loaded_file();?>&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">NEOS</th></tr>

<tr>
    <td width="130">Vers&atilde;o</td>
    <td><?php echo _CAN;?>&nbsp;</td>
  </tr>
  <tr>
    <td>Bloco "core"</td>
    <td><?php echo $cfg->core;?>&nbsp;</td>
  </tr>
  <tr>
    <td>Bloco "app"</td>
    <td><?php echo $cfg->app;?>&nbsp;</td>
  </tr>
</table>
</div>

<p>A vers&atilde;o m&iacute;nima do PHP para o correto funcionamento do NEOS &eacute; a vers&atilde;o 5.2.9 . Este CORE foi testado e otimizado para a vers&atilde;o 5.3.0 e superiores. Usu&aacute;rios das vers&otilde;es 5.0 &agrave; 5.2.x tamb&eacute;m podem usar, por&eacute;m, &eacute; poss&iacute;vel que alguns recursos disparem mensagens de erro.</p>
<p>Obtenha mais informa&ccedil;&otilde;es sobre o <b>NEOS</b> em <a href="http://neophp.tk">http://neophp.tk</a></p>

</body>
</html>