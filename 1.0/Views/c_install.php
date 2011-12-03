<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>NEOS PHP Framework  -  CORE SERVICE</title>

<script type="text/javascript">
var bdtipo=Array();
<?php foreach($cfg->db as $db=>$valor){
	echo "bdtipo['$db']=[";	 
	 if(isset($valor->dsn)){echo "'".str_replace('\\','/',$valor->dsn)."',";}else{echo "'',";}
	 if(isset($valor->host)){echo "'".str_replace('\\','/',$valor->host)."',";}else{echo "'',";}
	 if(isset($valor->user)){echo "'".str_replace('\\','/',$valor->user)."',";}else{echo "'',";}
	 if(isset($valor->pass)){echo "'".str_replace('\\','/',$valor->pass)."',";}else{echo "'',";}
	 if(isset($valor->database)){echo "'".str_replace('\\','/',$valor->database)."',";}else{echo "'',";}
	 if(isset($valor->charset)){echo "'".str_replace('\\','/',$valor->charset)."',";}else{echo "'',";}
	 if(isset($valor->driver)){echo "'".str_replace('\\','/',$valor->driver)."',";}else{echo "'pdo',";}
	echo "''";
	echo "]\n";	
}?>
bdtipo['nenhum']=['','','','','','','','']
</script>
<script type="text/javascript" src="<?php echo URL.$cfg->admin_url.'/';?>pub/?p=js/jquery.js"></script>
<script type="text/javascript" src="<?php echo URL.$cfg->admin_url.'/';?>pub/?p=js/install.js"></script>
<link href="<?php echo URL.$cfg->admin_url.'/';?>pub/?p=css/install.css" rel="stylesheet" type="text/css" />
</head>

<body>
<input type="hidden" id="URL" value="<?php echo URL.$cfg->admin_url.'/';?>"/>
<h1>NEOS PHP Framework</h1>
<h2>CORE SERVICE (<?php echo _CAN;?>)</h2>
	<neos var="msg" class="msg"/>
	<!--menu esquerdo-->
	<ul class="dmenu">
		<li><a href="<?php echo URL;?>" >Aplicação (site)</a></li>
		<li><a href="<?php echo URL.$cfg->admin_url.'/install';?>" class="selected">Instalar Aplicação</a></li>
		<li><a href="<?php echo URL.$cfg->admin_url.'/ctrl';?>" >Criar um Controller</a></li>
		<li><a href="<?php echo URL.$cfg->admin_url.'/model';?>" >Criar um Model</a></li>
		<li><a href="<?php echo URL.$cfg->admin_url.'/helper';?>" >Criar um Helper</a></li>
		<li><a href="<?php echo URL.$cfg->admin_url.'/library';?>" >Criar uma Library</a></li>
		<li><a href="<?php echo URL.$cfg->admin_url.'/view';?>" >Criar uma View</a></li>
		<li><a href="<?php echo URL.$cfg->admin_url.'/logout';?>" >Logout (sair)</a></li>
	</ul>
	<!--recurso selecionado-->
	<div class="conteudo">		
		<form action="<?php echo URL.$cfg->admin_url.'/install';?>" method="post">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="49%">
							<p>Local da instalação:</p>
							<p><input name="local" type="text" value="<neos var="local"/>" readonly="readonly" size="40" /></p>
							<p>URL da instalação (<a href="<neos var="url"/>" title="Clique para visitar o site...">verificar</a>):</p>
							<p><input name="url" type="text" value="<neos var="url"/>" size="40" /></p>
							<p>Tipo de Layout:</p>
							<p><select name="layout" >
									<option value="A">A - Blocos misturados </option>
									<option value="B">B - Bloco APP separado </option>
									<option value="C" selected="selected">C - Todos os blocos separados</option>
								</select><img src="<?php echo URL.$cfg->admin_url.'/';?>pub/?p=img/help.png" id="ajuda"/>
							</p>							
						</td>
						<td width="51%" class="tb_direita">
							<h3>Aplicação de Exemplo (base)</h3>
							<p><label><input name="sites" type="radio" id="sites_0" value="0" checked="checked" />&nbsp;Não instalar</label></p>
							<p><label><input type="radio" name="sites" value="1" id="sites_1" />&nbsp;Site simples (três páginas)</label></p>
							<p><label><input type="radio" name="sites" value="2" id="sites_2" />&nbsp;Com Templates &amp; Módulos</label></p>
							<p>Para a instalação deste último é necessário que seu sistema suporte a biblioteca Sqlite.</p>
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<span class="ajuda">
							<p><b>Tipo A</b> - Todos os arquivos da aplicação estarão em uma mesmo pasta; com os blocos 'web' e 'app' misturados.</p>
							<p><b>Tipo B</b> - Será criada uma pasta para a aplicação ( app ).</p>
							<p><b>Tipo C</b> - Será criada uma pasta específica para cada bloco da aplicação (app &amp; web).</p>
							<p>Consulte o <b>manual</b> do NEOS para mais detalhes.</p>
							<p>&nbsp;</p>
						</span>
						</td>
					</tr>
				</table>			
			
			<div class="bdn">
			<h2>BANCO DE DADOS</h2>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>
							<p>Alias (apelido):</p>
							<p><neos type="select" var="db" onchange="tipo(this)" name="seldb"/></p>
							<p>Novo alias (apelido):</p>
							<p><input name="novo_alias" type="text" value="<neos var="novo_alias"/>" size="20" id="novo_alias" /></p>					
							<p>Conector:</p>
							<p><neos type="select" var="bd_driver" onchange="driver(this)" name="bd_driver" id="bd_driver"/></p>
						</td>
						<td width="40%" rowspan="2">
							<span id="bd_dsn_span">
								<p>DSN:</p>
								<p><input id="bd_dsn" name="bd_dsn" type="text" class="texto" value="<neos var="bd_dsn"/>" /></p>
							</span> 
							<span id="bd_host_span">
								<p>Host:</p>
								<p><input id="bd_host" name="bd_host" type="text" class="texto" value="<neos var="bd_host"/>" /></p>
							</span> 
							<span id="bd_user_span">
								<p>Usuário:</p>
								<p><input id="bd_user" name="bd_user" type="text" class="texto" value="<neos var="bd_user"/>" /></p>
							</span> 
							<span id="bd_pass_span">
								<p>Senha:</p>
								<p><input type="password" id="bd_pass" name="bd_pass" class="texto" value="<neos var="bd_pass"/>" /></p>
							</span> 
							<span id="bd_database_span">
								<p>Banco de Dados:</p>
								<p><input type="text" id="bd_database" name="bd_database" class="texto" value="<neos var="bd_database"/>" /></p>
							</span> 
							<span id="bd_charset_span">
								<p>Charset:</p>
								<p><input type="text" id="bd_charset" name="bd_charset" class="texto" value="<neos var="bd_charset"/>" /></p>
							</span>
							<p><input id="bdteste" type="button" value="Testar a conexão..." style="width:150px"  /><img src="<?php echo URL.$cfg->admin_url.'/';?>pub/?p=img/help.png" id="ajuda_teste"/></p>
							<span class="ajuda_teste">
								<p><b>Atenção!</b></p>
								<p>Os testes feitos aqui não alterarão as configurações do  core.</p>
								<p>Será criado um <b>novo</b> arquivo de configuração, somente para esta aplicação, durante o processo de instalação.</p>
								<p>Se você pretende alterar a configuração global do NEOS, acesse manualmente o arquivo de configurações.</p>
							</span>
						</td>
					</tr>
					<tr>
						<td class="tb_esquerda">					
							<p>Use uma das 'alias' préviamente configuradas no arquivo config.php do core ou crie uma nova.</p>
							<p>Deixando o campo "Novo Alias" em branco será considerado o alias escolhido na caixa de seleção!</p>
							<p>Antes de indicar um novo &quot;alias&quot;, selecione o <b>conector</b> (driver) desejado na caixa de seleção acima. Para o nome do&nbsp;&quot;alias&quot; não use caracteres especiais como acentos, virgula, pontos, traço, etc.</p>
						</td>
					</tr>
				</table>
			</div>		
			<p><input name="save" type="submit" value="Criar Aplicação" style="width:150px"  /></p>
		</form>
	</div>
	<div  style="clear:both"></div>
<?php if(isset($alerta) && $alerta!=''){ echo "<script type=\"text/javascript\">alert('$alerta')</script>";}?>
</body>
</html>
