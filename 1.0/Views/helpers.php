<h2>Erro no HELPER!</h2>

<div class="msg"><?php echo $msg;?></div>

<h2>Solução:</h2>
<div class="dica">

<ol>
<li>Verifique se foi informado o nome correto do helper.</li>
<li>Procure no site do NEOS pelo helper, faça o download e salve na pasta 'Helpers' de sua aplicação.</li>
<li>Crie você mesmo o helper:
	<p>Copie o código abaixo e cole em um arquivo texto no seguinte local (path/name.ext):<p><b>"<?php echo $cfg->core.'Helpers'.SEP.$cfg->error['function'].'.php';?>"</b></p>
	<p>Para que o seu helper seja restrito somente a sua aplicação atual, instale-o em:<p><b>"<?php echo $cfg->helper.strtolower($cfg->error['function']).'.php';?>"</b></p>	
	</li>
</ol>

<div>
<pre>
<span class="vmb"></span><span class="vmb">&lt;?php</span> <span class="vd">if</span>(!<span class="az">function_exists</span>( <span class="vm">'<?php echo $cfg->error['function'];?>'</span> )){


	<span class="az">function</span> <?php echo $cfg->error['function'];?>()
	{
		<span class="lr">//your code here!</span>
	}

}
</pre>
</div>
<?php if(isset($cfg->admin_url) && $cfg->admin_url!=''){?>
<p>Use o <b><a href="<?php echo URL.'index.php/'.$cfg->admin_url;?>">CORE SERVICE</a></b> para criar este recurso.</p>
<?php }?>
<p>Consulte o manual do NEOS para mais informações sobre HELPERS.</p>
</div>