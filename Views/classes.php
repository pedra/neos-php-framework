<h2>Erro na <b>CLASSE</b>!</h2>

<div class="msg"><?php echo $msg;?></div>

<h2>Solução:</h2>
<div class="dica">

<ol>
<li>Verifique se foi informado o nome correto da classe.</li>
<li>Procure a classe no site do NEOS.</li>
<li>Crie você mesmo a classe desejada.</li>
</ol>

<p>Como as classes podem ser de vários tipos (mail, BD, templates, etc) você deve ter um bom conhecimento sobre o tipo de classe antes de tomar uma providência.</p>
<p>Praticamente qualquer classe pode ser usada no NEOS - isso inclue classes desenvolvidas para outros frameworks.</p>
<p>Um bom site para pesquisar por classes é o <a href="http://www.phpclasses.org" title="Vai abrir em outra janela (tab)!" target="_new">http://www.phpclasses.org</a></p>
<p>Uma sintaxe bem comum  para uma classe seria:</p>

<div>
<pre>
<span class="vmb">&lt;?php</span>

<span class="vd">class</span> <b><?php echo ucfirst($cfg->error['class']);?></b>  extends <b>NEOS_class</b>
{ 
	
	<span class="lr">//optional: function __construct(){parent::__construct(); /* other constructions...*/ }</span>
		
	<span class="az">function</span> <?php echo $cfg->error['function'];?><span class="az">()</span>
	{
		
		<span class="lr">//your code here!</span>
		
	}
	
}
</pre>
</div>
<p>O nome da classe deve começar por um caracter <b>maiúsculo</b> e o arquivo que a contém deve ter o mesmo nome da classe, em <b>minúsculas</b>, com a extensão ".php".</p>
<?php if(strpos($cfg->core,'phar:')===false){?>
<p>A classe deve ser instalada no CORE do NEOS (<b><?php echo $cfg->core.'Lybrary'.SEP.$cfg->error['class'].'.php';?></b>) para que outras aplicações tenham acesso.</p><?php }?>
<p>Para que a sua classe seja restrita somente a sua aplicação atual, instale-a em:<p><b>"<?php global $cfg;echo $cfg->library.strtolower($cfg->error['class']).'.php';?>"</b></p>
<p>
<?php if(isset($cfg->admin_url) && $cfg->admin_url!=''){?>
<p>Use o <b><a href="<?php echo URL.'index.php/'.$cfg->admin_url;?>">CORE SERVICE</a></b> para criar este recurso.</p>
<?php }?>
<p>Consulte também o manual do NEOS para mais informações sobre CLASSES.</p>
</div>