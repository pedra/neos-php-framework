<h2>Erro no CONTROLLER!</h2>

<div class="msg"><?php echo $msg;?></div>

<h2>Solução:</h2>
<div class="dica">

<ol>
<li>Verifique se foi informado o nome correto do controller.</li>
<li>Copie o código abaixo e cole em um arquivo texto no seguinte local (path/name.ext):<p><b>"<?php echo $cfg->ctrl.strtolower($cfg->default->ctrl).'.php';?>"</b></p></li>
</ol>

<div>
<pre>
<span class="vmb">&lt;?php</span>
	
<span class="vd">class</span> <b><?php echo ucfirst($cfg->default->ctrl);?></b> <span class="vd">extends</span> <b>NEOS</b> 
{
	
	<span class="lr">//optional: function __construct(){parent::__construct(); /* other constructions...*/ }</span>
		
	<span class="az">function</span> <b><?php echo $cfg->default->func;?></b><span class="az">()</span>
	{
		
		<span class="lr">//your code here!</span>
		
	}
	
}
</pre>
</div>
<?php if(isset($cfg->admin_url) && $cfg->admin_url!=''){?>
<p>Use o <b><a href="<?php echo URL.'index.php/'.$cfg->admin_url;?>">CORE SERVICE</a></b> para criar este recurso.</p>
<?php }?>
<p>Consulte o manual do NEOS para mais informações sobre CONTROLLERS.</p>
</div>