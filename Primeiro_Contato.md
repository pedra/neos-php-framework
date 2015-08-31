
<h1>Primeiro Contato</h1>

<p>Se você já usou outro framework não terá qualquer dúvida de como fazer o NEOS funcionar.</p>
<p>Mas se esta é a sua primeira vez não fique apreensivo: será muito fácil instalar e começar a usar seu novo <strike>brinquedo</strike> framework.</p>

<h2>Download</h2>
<p>Pensando em um passo-a-passo bem completo, vamos começar fazendo o download do NEOS.</p>
<p>Você deve escolher sempre a última versão e no formato PHAR. Caso sua intenção seja estudar o framework (seus scripts, sua estrutura) e posteriormente até mesmo alterá-lo, então você deve baixar a versão SourceCode; zipada.</p>

<p>Veja neste <a href='http://code.google.com/p/neos-php-framework/downloads/list'>link</a> as versões disponíveis e faça o download apropriado.</p>

<h2>Servidor</h2>
<p>Como pré-requisito você precisa:</p>
<ul>
<li>Servidor web Apache (de acesso público ou instalado em sua máquina de teste)</li>
<li>Módulo re-write do Apache ativado</li>
<li>PHP na versão 5.3(+) instalado</li>
</ul>

<p>Para usar os arquivos PHAR diretamente chamados na url, você terá que incluir (se já não estiver) a seguinte linha no arquivo de configuração do Apache:</p>
```
AddType application/x-httpd-php .phar```

<p>Se pretende criar arquivos PHAR em seu servidor terá que habilitar isso na configuração do PHP (php.ini), na seguinte linha:</p>
```
phar.readonly = Off```

<p>Arquivos PHAR podem ser gravados com um "certificado" de segurança - um hash. Caso não precise disso, modifique (ou insira) a seguinte linha em seu php.ini:</p>
```
phar.require_hash = Off```

<p>Bem, agora é só salvar o arquivo "neos.phar" no root do seu servidor e checar se tudo está funcionando, digitando o seguinte em seu navegador:</p>
```

http://localhost/neos.phar
//considerando "localhost" como o endereço de seu servidor```
<p>Uma tela de "boas-vindas" deve ser mostrada em seu navegador.</p>

<p><font color='#F00'>Caso você não consiga ou não tenha acesso as configurações do Apache e do PHP você pode também renomear o arquivo "neos.phar" para "index.php". Isso somente para o teste acima.</font></p>

<h2>Criando um "Hello World"</h2>
<p>Vamos criar a estrutura básica (MVC) de uma aplicação com o NEOS.</p>
<p>Crie a seguinte estrutura de pastas e arquivos:</p>
```

root --- pasta raiz do servidor Apache


root/neos.phar
root/.htacess
root/index.php //necessário somente se o servidor não reconhecer arquivos PHAR
root/app/.htaccess
root/app/controller/inicial.php
root/app/view/html/home.html
root/app/model/

você pode criar outras pastas conforme a necessidade de sua aplicação
root/css
root/js
root/image
...etc```
<p>Agora vamos ver qual o conteúdo de cada arquivo listado acima:</p>

<h3>/index.php</h3>
<p><font color='#F00'>Este arquivo será necessário <b>somente</b> se você não conseguir fazer o servidor reconhecer os arquivos PHAR acessando-os diretamente pela url - acesso externo.</font></p>
<b>Conteúdo:</b>
```
<?php
include 'phar://neos.phar/main.php';
Main::run();```

<h3>/.htaccess</h3>
<b>Conteúdo:</b>
```
<IfModule mod_rewrite.c>
Options +FollowSymlinks
RewriteEngine On

RewriteRule "(^|/)\." - [F]
RewriteCond %{REQUEST_FILENAME} -s [OR]
RewriteCond %{REQUEST_FILENAME} -l [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^.*$ - [NC,L]

RewriteRule ^.*$ neos.phar [NC,L]


Unknown end tag for &lt;/IfModule&gt;

```
<p><font color='#F00'>Se não puder acessar o arquivo "neos.phar" externamente, troque a penúltima linha por:</font></p>

```

RewriteRule ^.*$ index.php [NC,L]```

<p>Esse arquivo fará o re-direcionamento de toda a requisição feita ao servidor que não corresponder a um arquivo real (existente) para o sistema do NEOS.</p>

<h3>/app/.htaccess</h3>
<b>Conteúdo:</b>
```

Deny From All```
<p>Esse arquivo especial do servidor Apache está indicando ao servidor que nenhum acesso externo (usuário do site) será permitido nesta pasta.</p>
<p>Isso porque esta pasta contém todos os arquivos de código (PHP) da nossa aplicação e portanto <b>não</b> deve ser "visto" por ninguém.</p>

<h3>/app/controller/inicial.php</h3>
<b>Conteúdo:</b>
```
<?php

class Controller_Inicial

extends NEOS {

function index() {
_view::val('mensagem', 'Hello World');
_view::set('home');
}
}```

<p>Vamos ver linha por linha o que tudo isso significa.</p>
<p>"Controller_Inicial" é o controlador default do NEOS. É representado por uma classe que "extends" o próprio framewrk - NEOS. Isso é interessante porque o controlador tem total controle sobre <b>todo</b> o funcionamento do sistema (veja <a href='Controllers.md'>Controllers</a>).</p>

<p>A função "index" é a função default do controller e contém duas instruções bem fáceis de entender.<p>
<pre><code>_view::val('mensagem', 'Hello World');</code></pre>
<p>Isto grava "Hello World" na variável de nome "mensagem", disponível somente para as views.</p>

<pre><code>_view::set('home');</code></pre>
<p>A instrução acima chama uma view de nome "home" (/app/view/html/home.html).</p>

<h3>/app/view/html/home.html</h3>
<b>Conteúdo:</b>
<pre><code>&lt;!DOCTYPE HTML&gt;<br>
&lt;html&gt;<br>
&lt;head&gt;<br>
&lt;meta charset="utf-8"&gt;<br>
&lt;title&gt;Teste<br>
<br>
Unknown end tag for &lt;/title&gt;<br>
<br>
<br>
<br>
<br>
Unknown end tag for &lt;/head&gt;<br>
<br>
<br>
<br>
&lt;body&gt;<br>
<br>
&lt;p&gt;&lt;neos:mensagem /&gt;<br>
<br>
Unknown end tag for &lt;/p&gt;<br>
<br>
<br>
<br>
<br>
<br>
Unknown end tag for &lt;/body&gt;<br>
<br>
<br>
<br>
<br>
Unknown end tag for &lt;/html&gt;<br>
<br>
</code></pre>

<p>Bem, esse é um arquivo html convencional (html5) exceto pela conteúdo  do parágrafo, do corpo do html:</p>

<pre><code>&lt;p&gt;&lt;neos:mensagem /&gt;<br>
<br>
Unknown end tag for &lt;/p&gt;<br>
<br>
</code></pre>

<p>Aqui nós temos uma <b>neosTag</b> que mostrará o conteúdo da variável "mensagem", setada no controller.</p>

<h3>Testando!</h3>
<p>Agora, finalmente, podemos testar nossa primeira aplicação, digitando no navegar o seguinte:</p>
<pre><code>http://localhost</code></pre>
<p>Considerando que "localhost" seja o endereço de nosso servidor!</p>
<p>Se tudo estiver correto, a frase <b>"Hello World"</b> deve aparecer na tela do seu navegador.</p>
