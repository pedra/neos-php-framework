<?php
if(!defined('URL_BASEX')){
	defined('DS') || define('DS', DIRECTORY_SEPARATOR);
	//pegando o PATH físico do ARQUIVO PHAR
	$x = explode('/', $_SERVER['SCRIPT_FILENAME']);
	array_pop($x);
	$x = str_replace(array('/', '\\'), DS, implode('/', $x));
	define('RPATH', $x . DS); 
	
	//1º achar script file
	$temp = explode('/', $_SERVER['SCRIPT_FILENAME']);
	$script = end($temp);
	$root = explode('/', $_SERVER['DOCUMENT_ROOT']);
	
	//2º descobrir o excedente entre o root e script file
	foreach($root as $k=>$d){if($temp[$k] == $d) unset($temp[$k]);}
	
	//3º juntando tudo
	$phpself = implode('/', $temp);
	$ssl = _detectSSL_x() ? 'https://' : 'http://';
	define('URL_BASEX', trim($ssl . $_SERVER['SERVER_NAME'] . '/' . $phpself, ' /') . '/' . _cfg::this()->admin_url . '/');
}
//detecta se o acesso está sendo feito por SSL (https)
	function _detectSSL_x(){
		if (!isset($_SERVER["HTTPS"]))		return false;
		if ($_SERVER["HTTPS"] == "on")		return true;
		if ($_SERVER["HTTPS"] == 1)			return true;
		if ($_SERVER['SERVER_PORT'] == 443) return true;
		return false;
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>NEOS PHP Framework</title>
<base href="<?php echo URL_BASEX;?>" />
<link rel="shortcut icon" href="ui/img/favicon.ico"/>
<link href="ui/screen.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div id="menu">
    	<a href="http://neosphp.org/manual">manual</a> | 
        <a href="http://neosphp.org/baixar">baixar</a> | 
        <a href="http://neosphp.org/podcast">podcast</a>
    </div>
    
    <div id="container" class="container">
    	<div id="logo"><a href="index">NEOS PHP Framework</a></div>
        
        <h1>Bem Vindo!</h1>
        <p>Agora você precisa criar sua aplicação com o NEOS</p>
        <ol>
        	<li>Crie uma pasta com o nome de "app"</li>
            <li>Crie também as seguintes sub-pastas:
            	<ul>
                	<li> app/controller </li>
                    <li> app/view </li>
                    <li> app/model </li>
                </ul>
            </li>
            <li>Crie o arquivo "app/controller/inicial.php" com o seguinte conteúdo:</li>
            	<p><b>Este é o seu primeiro (default) controller</b></p>
            	<p class="codigo"><?php echo highlight_file('inc/ex_controller.php', true);?></p>
			<li>Depois, uma view em "app/view/home.html" com o seguinte conteúdo:
            	<p class="codigo"><?php echo highlight_file('inc/ex_view.html', true);?></p>
            <li>Agora você já pode testar: recarregue esta página!</li>
            
            <?php if(is_writable(RPATH)) echo '<p><b>Se você quiser eu posso criar isso tudo pra você. <a href="'.URL_BASEX.'createApp.php">Click aqui</a>.</b></p>';?> 
		</ol>
        
        <!--<iframe src="http://player.vimeo.com/video/39629544?title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1" width="600" height="450" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>-->

<?php include 'inc/footer.php'; exit();?>
