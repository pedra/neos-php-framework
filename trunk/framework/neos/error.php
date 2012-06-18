<?php
$ehelp[1] = <<<'EOD'

<h3>O NEOS não está encontrando o controller!</h3>
<p>Isso pode ser causado por duas razões:</p>
<ol>
	<li>O arquivo ou caminho do arquivo não existe ou está configurado de forma errada;</li>
	<li>O <b>conteúdo</b> ou a <b>declaração da classe</b> do controller está errada.</li>
</ol>	
<p>Para solucionar este problema crie (ou verifique se existe) o seguinte arquivo:</p>
<code>
EOD;
$ehelp[1] .= APP_CONTROLLER . \_cfg::this()->ctrl . EXTCTRL;
$ehelp[1] .= <<<'EOD'
</code>
<p>Certifique-se de que o conteúdo do arquivo seja semelhante a esse modelo:</p>
<code>&lt;?php
<small>//A palavra "Controller" sempre precede o nome do controller, sepado por um "_" e ambos começando com letra maiúscula
//O controller DEVE extender a super-classe NEOS</small>
class Controller_Inicial 	
	extends NEOS {			
	
	<small>//função default</small>
	function index() {
		
		<small>//criando uma variável na view</small>
		_view::val('ola', 'Hello World!');
		
		<small>//carregando uma view para renderização</small>
		_view::set('inicial');
	}
	
	<small>//outra função - para acessar digite no navegador: 
EOD;
$ehelp[1] .= URL . 'inicial/outra';
$ehelp[1] .= <<<'EOD'
</small>
	function outra(){
		echo 'Eu sou a "OUTRA" !!!';
	}
}
</code>
<p>Depois, <b>recarregue esta página</b> para ver o problema solucionado.</p>
EOD;


$ehelp[2] = <<<'EOD'
<ol>
	<li>o diretório (pasta) da Aplicação existe e está corretamente indicado?</li>
	<li>o arquivo do controller existe?</li>
</ol>
<p>Para o NEOS carregar corretamente o controller, este precisa estar no seguinte caminho:</p>
<code>APP_CONTROLLER + nome_controller_letras_minúsculas + EXTCTRL

<small>normalmente seria:</small> root + app/controller/inicial.php</code>
<p>O conteúdo deste arquivo é a declaração da classe do controller e deve ser parecido com isso:</p>
<code>&lt;?php
class Controller_Inicial
	extends NEOS {
	
	function index() {
		
		<small>//criando uma variável na view</small>
		_view::val('ola', 'Hello World!');
		
		<small>//carregando uma view para renderização</small>
		_view::set('inicial');
	}
	
	//outra função
	function outra(){
		echo 'Eu sou a "OUTRA" !!!';
	}
}
<small>//basta criar o arquivo e colar este conteúdo para testar ou usar seu controller "Inicial".</small></code>
<p>A constante <b>APP_CONTROLLER</b> indica o caminho dos arquivos de controller (default). Pode ser re-configurada se você estiver usando a versão aberta do NEOS (não a PHAR) no arquivo:</p>
<code>PATH_NEOS + neos/config/constants.php</code>
<p>Normalmente isso não é recomendado, ficando mais interessante usar a configuração default:</p>
<code>PATH_APP + controller/</code>
<p>Outra constante importante neste caso é <b>PATH_APP</b> que carrega a indicação do local onde está a base de seus arquivos da <b>aplicação</b> e pode ser configurada no arquivo <b>index</b>, no root de acesso do site.</p>
<p>A configuração default é:</p>
<code>PATH + app/</code>
<p>Onde <b>PATH</b> indica o root do servidor web (ou do virtual host - Apache).</p>
<p>Se você <b>realmente</b> precisa redefinir estes valores, pode faze-lo com as seguintes declarações:</p>
<code><small>//arquivo index.php</small>
define('PATH'    , __DIR__ );
define('PATH_APP', PATH . DIRECTORY_SEPARATOR . 'app');
<small>//isto equivale ao default - modifique conforme a sua necessidade.</small>
</code>
<p>Veja a seguir o que está configurado neste sistema atualmente para cada uma das constantes citadas:</p>
<code>
EOD;
$ehelp[2] .= 'PATH             = ' . PATH . '
PATH_NEOS        = ' . PATH_NEOS . '
PATH_APP         = ' . PATH_APP . '
APP_CONTROLLER   = ' . APP_CONTROLLER . '
EXTCTRL          = ' . EXTCTRL . '</code>';
