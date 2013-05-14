<?php
$ehelp[1] = 'Não foi possível conectar ao banco de dados';
$ehelp[2] = 'Error in prepare';
$ehelp[3] = 'Bind sem Prepare!?';
$ehelp[4] = 'Executar oque?!';

$ehelp[5] = <<<'EOD'
É necessário informar uma sentença SQL válida.
<h3>Exemplo:</h3>
<code>_db::query("SELECT * FROM Tabela");</code>
EOD;

$ehelp[6] = <<<'EOD'
<h3>Confira a sintaxe do comando INSERT:</h3>
<code>_db::insert(<small>[array]</small>Campos, <small>[string]</small>Tabela, <small>[string]</small>Alias);</code>
<p><b>Campos</b> é um Array no formato: <b>Array['campo'] = valor;</b></p>
<p><b>Tabela</b> é o nome da tabela (string) onde se deseja inserir os dados.</p>
<p><b>Alias</b> é opcional e indica uma configuração de banco de dados diferente da default.</p>
EOD;


$ehelp[7] = <<<'EOD'
<h3>Confira a sintaxe do comando UPDATE:</h3>
<code>_db::update(<small>[array]</small>Campos, <small>[string]</small>Where, <small>[string]</small>Tabela, <small>[string]</small>Alias);</code>
<p><b>Campos</b> é um Array no formato: <b>Array['campo'] = valor;</b></p>
<p><b>Where</b> uma sentença "WHERE" válida (sem a palavra-chave WHERE).
<p><b>Tabela</b> é o nome da tabela (string) onde se deseja inserir os dados.</p>
<p><b>Alias</b> é opcional e indica uma configuração de banco de dados diferente da default.</p>
<h3>Exemplo:</h3> 
<code>$campos['nomeCampo'] = 'novoValor';
$where = 'ID = 32 AND COD_USER = 123';
$tabela = 'Tabela';

_db::update($campos, $where, $tabela);</code>
EOD;
