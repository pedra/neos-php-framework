<h2>Erro de VIEW!</h2>

<div class="msg"><?php echo $msg;?></div>

<h2>Solução:</h2>
<div class="dica">

<ol>
<li>Verifique se foi informado o nome correto da view.</li>
<li>Crie uma view!
	<p>Você pode criar a view da forma tradicional: um arquivo HTML convencional.</p>
	<p>Na view, além das tags e comandos do PHP <span class="vdc">(apesar de ser um arquivo HTML!)</span>, é possível usar as "NeosTags" para adicionar recursos extra de programação com a vantagem de se parecerem com tags de HTML.</p>
	<p>Veja o código abaixo e use como exemplo - o manual do NEOS pode ser mais útil!</p>
	<p>O arquivo deve ser salvo em: <b>"<?php global $cfg;echo $cfg->view.$cfg->error['class'].'.html';?>"</b></p>
</li>
</ol>

<div>
<pre>
<span class="cz">&lt;!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"></span>
<span class="az">&lt;html xmlns</span>="http://www.w3.org/1999/xhtml"<span class="az">></span>

	<span class="az">&lt;head></span>
		<span class="az">&lt;meta http-equiv</span>="Content-Type" <span class="az">content</span>="text/html; <span class="az">charset</span>=utf-8" <span class="az">/></span>
		<span class="az">&lt;title></span>NEOS PHP Framework<span class="az">&lt;/title></span>
		<span class="az">&lt;link href</span>="<span class="vmb">&lt;neos:url/></span>css/css.css" <span class="az">rel</span>="stylesheet" <span class="az">type</span>="text/css" <span class="az">/></span>
		<span class="az">&lt;script type</span>="text/javascript" <span class="az">src</span>="<span class="vmb">&lt;neos:url/></span>js/jquery.js"<span class="az">>&lt;/script></span>
		<span class="az">&lt;script type</span>="text/javascript" <span class="az">src</span>="<span class="vmb">&lt;neos:url/></span>js/seu_javascript.js"<span class="az">>&lt;/script></span>
	<span class="az">&lt;/head></span>
	
	<span class="az">&lt;body></span>
	
	<span class="vmb">	&lt;neos</span> <span class="az">var</span>=&quot;content&quot; <span class="az">class</span>=&quot;conteudo&quot; <span class="vmb">/&gt;</span>
		<span class="cz">&lt;!-- resulta em: --&gt;</span> <span class="vmb">&lt;div</span> <span class="az">class</span>=&quot;conteudo&quot;<span class="vmb">&gt;</span><span class="az"> o valor da variável &quot;content&quot; </span><span class="vmb">&lt;/div&gt;</span>

	<span class="vmb">	&lt;neos</span> <span class="az">type</span>=&quot;module&quot; <span class="az">name</span>=&quot;menu&quot; <span class="az">class</span>=&quot;menu_esquerdo&quot; <span class="az">id</span>=&quot;menu&quot; <span class="vmb">/&gt;</span>
		<span class="cz">&lt;!-- carrega o módulo 'menu' em substiituição a neosTag acima --&gt;</span>
		
		<span class="vmb">&lt;neos</span> <span class="az">type</span>=&quot;select&quot; <span class="az">var</span>=&quot;array&quot; <span class="az">class</span>=&quot;escolha&quot; <span class="az">id</span>=&quot;escolha&quot; <span class="vmb">/&gt;</span>
		<span class="cz">&lt;!-- cria um '&lt;select class=&quot;escolha&quot; ... ' com os dados de 'array' --&gt;</span>

	<span class="vmb">	&lt;?php</span> <span class="vd">if</span> ( <span class="az">isset</span>(<span class="az">$mensagem</span>) ){ <span class="vd">echo</span> <span class="az">$mensagem</span>; } <span class="vmb">?&gt;</span>
		<span class="cz">&lt;!-- prefira não usar PHP (como acima) - use neosTags equivalentes (a baixo): --&gt;
		</span><span class="vmb">&lt;neos</span> <span class="az">var</span>=&quot;mensagem&quot;<span class="vmb">/&gt;</span>
	
	<span class="az">&lt;/body>
	
&lt;/html>
</span></pre>
</div>
<p>Você também pode dividir suas views em vários arquivos <span class="vdc">(header, body, footer, etc.)</span>, criar módulos <span class="vdc">(menu, login, etc.)</span>, usar templates e muito mais. Consulte o manual para mais informações.</p>
<?php if(isset($cfg->admin_url) && $cfg->admin_url!=''){?>
<p>Use o <b><a href="<?php echo URL.'index.php/'.$cfg->admin_url;?>">CORE SERVICE</a></b> para criar este recurso.</p>
<?php }?>
</div>