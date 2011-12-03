<h2>Erro no MODEL!</h2>

<div class="msg"><?php echo $msg;?></div>

<h2>Solução:</h2>
<div class="dica">

<ol>
<li>Verifique se foi informado o nome correto do model ou do método invocado.</li>
<li>Crie você mesmo um model:<p>Copie o código abaixo e cole em um arquivo texto no seguinte local (path/name.ext):<p><b>"<?php global $cfg;echo $cfg->model.strtolower($cfg->error['class']).'.php';?>"</b></p></li>
</ol>

<div>
<pre>
<span class="vmb">&lt;?php</span>

<span class="vd">class</span> <b><?php echo $cfg->error['class'];?></b>  extends <b>NEOS_models</b>
{
	
	<span class="lr">//optional: function __construct(){parent::__construct(); /* other constructions...*/ }</span>
		
	<span class="az">function</span> <?php echo $cfg->error['function'];?><span class="az">()</span>
	{
		
		<span class="lr">//your code here!</span>
		
	}
	
}
</pre>
</div>
<?php if(isset($cfg->admin_url) && $cfg->admin_url!=''){?>
<p>Use o <b><a href="<?php echo URL.'index.php/'.$cfg->admin_url;?>">CORE SERVICE</a></b> para criar este recurso.</p>
<?php }?>
<p>Consulte o manual do NEOS para mais informações sobre MODELS.</p>
</div>