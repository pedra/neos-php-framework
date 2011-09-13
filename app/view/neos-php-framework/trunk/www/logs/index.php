<?php ob_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DOCS - NEOS PHP Framework</title>
<link href="http://neos3php.com/css/screen.css" rel="stylesheet" type="text/css" />
<link href="http://neos3php.com/logs/css/logs.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="container">
<h1>Registro de Ocorrência de Erros</h1>

<?php
	//carregando a biblioteca MicroData
	include 'lib/md.php';
	$time = microtime(true);
	
	//Montando o MicroData e retornando os dados
	$d = MD::mount('log_test.txt');
	
	//tempo de execução do MicroData
	$time = 'Tempo de execução: <b>' . number_format((microtime(true) - $time)*1000,3,',','.') . ' ms</b>';
	
	//definindo a URL
	$url = str_replace($_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
	
	//Comando para deletar o arquivo de log
	if(isset($_GET['d']) && $_GET['d'] == 'delete') {
		MD::this()->data = null; 
		MD::this()->data[0] = end($d);
		ob_end_clean();
		header('Location: ' . $url, TRUE, 302);
	}
		
	$linhas		= 19;//Linhas por página		
	$tamanho	= count($d);//quantidade de linhas	 
	$paginas	= intval($tamanho/$linhas) + (($tamanho % $linhas > 0) ? 1 : 0);//total de páginas
	$pagAtual	= isset($_GET['p']) ? $_GET['p'] : 'U';//página a ser exibida
	$pagAtual	= $pagAtual == 'U' ? $paginas : $pagAtual;
	$inicio		= ($pagAtual * $linhas) - $linhas;
	
	//Status
	$out = '<p class="quiet">Existem ' . $tamanho . ' registros no total. Visualizando a página <b>' . $pagAtual . '</b> de ' . $paginas . ' páginas (' . $linhas . ' registros por página). ' . $time . '.</p>';
	
	//iniciando o buffer com um parágrafo de status
	$out .= '<p>Páginas: ';
	
	//criando os links de paginação
	for($i = 1; $i <= $paginas; $i++){
		if($pagAtual == $i) $out .= '<b>&nbsp;' . $i . '&nbsp;</b> | ';
		else $out .= '<a href="' . $url . 'p=' . $i . '">&nbsp;' . $i . '&nbsp;</a> | ';
	}	
	$out .= ' <a href="' . $url . 'p=U">&nbsp;Final&nbsp;</a></p>';
	
	//Deletar arquivo de logs
	$out .= '<p><a href="' . $url . 'p=1&d=delete"><input name="delete" type="button" id="delete" value="Apagar Logs" /></a></p>';

	$out .= '<div id="tabela">
	<table width="100%" border="0" cellspacing="3" cellpadding="3">';

	//construindo o cabeçalho
	$out .= '<tr><th>ID</th>';
	
	//outras colunas pegas no arquivo
	foreach($d[0] as $k=>$v){$out .= '<th>' . $k . '</th>';}
	
	//fechando a linha da tabela
	$out .= '</tr>';
	
	//pegando somente a parte que será 'printada'		
	$d = array_slice($d, $inicio, $linhas, true);	
	
	//loop para imprimir a tabela
	foreach($d as $k=>$v){
		$out .= "\n<tr><td>" . ($k + 1) . '</td>';
		foreach($v as $vt){ $out .= '<td>' . $vt . '</td>';}
		$out .= '</tr>';
	}
	$out .= '</table>';	
	
	//descarregando o buffer de saída
	$pg = isset($_GET['p']) ? $_GET['p'] : 'U';
	echo $out . '<input type="hidden" id="pagAtual" value="' . $pg . '"/>';		
?>

</div>

<div class="footer"><b> &copy; NEOS PHP Framework</b><p style=" float:right"> Todos os direitos reservados - <a href="mailto:contato@neosphp.com">contato@neosphp.com</a>.</p></div>
</div>

<script type="text/javascript" src="http://neos3php.com/js/jquery.js"></script>
<script type="text/javascript" src="http://neos3php.com/js/jquery-ui.js"></script>
<script type="text/javascript" src="http://neos3php.com/logs/js/logs.js"></script>

</body>
</html>