
<h1>Você programa em PHP?</h1>
<p><pre><code>...ou em CodeIgniter, Cake, Symfony, Zend...</code></pre></p>

<h3>O NEOS é <strong>puro</strong> PHP!</h3>

<p>Você não precisa ler extensos manuais somente para aprender a usar o framework.<p>
<strong>Basta conhecer o PHP.</strong>

<ul>
<li>Fácil de usar!</li>
<li>Fácil de aprender!</li>
<li>Orientado a objeto (PHP 5.3)!</li>
<li>Extremamente rápido!</li>
<li>Em Português!</li>
</ul>

<p>Você precisa de mais alguma coisa para se convencer?</p>
<p>Se você <strong>nunca usou um framework</strong> comece pelo mais fácil!</p>

<p><i><font color='#890'>Aprenda mais em nossa <a href='http://code.google.com/p/neos-php-framework/wiki'>wiki</a> page.</font></i></p>
<br>
<br>
<br>
<br>
<h2>Iniciando</h2>

<ol>
<li>Baixe o NEOS na área de <a href='http://code.google.com/p/neos-php-framework/downloads/list'>downloads</a> e salve no "root" do seu servidor (PHP 5.3 ou mais).</li>
<li>Digite o endereço do seu servidor seguido do nome do arquivo baixado.<br>
<br>
<p>Exemplo:</p>
<pre><code><br>
http://www.seu_site.com/neos.phar</code></pre>

<p><font color='#F00'>Caso seu servidor não esteja pronto para executar arquivos PHAR diretamente, crie um arquivo "index.php" com o seguinte conteúdo:</font></p>

<pre><code><br>
&lt;?php  include 'phar://neos.phar';</code></pre>

<p>É só acessar:</p>
<pre><code><br>
http://www.seu_site.com/index.php</code></pre>

</li>

</ol>

<p><strong>Pronto! O NEOS estará funcionando!</strong></p>
<p>Agora dê uma olhada no <a href='http://neos-php-framework.googlecode.com/files/manual.pdf'>Manual</a> do NEOS para começar a <strike>se divertir</strike> trabalhar agora mesmo!</p>

<p><i><font color='#890'>Aprenda mais em nossa <a href='http://code.google.com/p/neos-php-framework/wiki'>wiki</a> page.</font></i></p>
<br>
<br>
<h2>Arquivos PHAR</h2>

<p>Para obter maior performance em velocidade e segurança, o NEOS é distribuído em formato PHAR (PHP Arquive). É um formato nativo do próprio PHP e assemelha-se muito com os packets ".jar", do Java.</p>
<p>Para certificar-se de que seu servidor está habilitado para acessar esse tipo de arquivo diretamente (pela url digitada) procure a seguinte "entrada" no arquivo de configuração de seu servidor Apache:</p>
<pre><code><br>
AddType application/x-httpd-php .phar</code></pre>
<p>Se não existir, crie essa linha no arquivo de configurações do usuário (administrador) do Apache</p>
<p>Garanta, também, que o arquivo de configuração do PHP (php.ini) tenha as opções configuradas como abaixo:</p>

<pre><code><br>
phar.readonly = Off<br>
phar.require_hash = Off</code></pre>


<p>O primeiro ítem habilita a gravação (criação) de novos arquivos PHAR. Você poderá usar o próprio PHP para transformar suas bibliotecas e até mesmo <b>um site inteiro</b> em arquivo (pacote) PHAR. Isso garante maior velocidade, segurança, portabilidade, etc.</p>
<p>O segundo habilita o funcionamento sem a necessidade de um certificado de segurança; mais indicado para a fase de testes.</p>
<p>Baixe o <a href='http://neos-php-framework.googlecode.com/files/make.phar'>Conversor Phar</a> da área de downloads e faça suas próprias experiências.</p>
<p>Conheça mais sobre o PHAR no <a href='http://br.php.net/manual/pt_BR/book.phar.php'>manual do PHP</a>.</p>

<p><i><font color='#890'>Aprenda mais em nossa <a href='http://code.google.com/p/neos-php-framework/wiki'>wiki</a> page.</font></i></p>
