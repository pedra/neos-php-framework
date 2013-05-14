<?php
if(!function_exists('_doc_excel')) {
	
/**
 * Cria um arquivo com uma tabela compatível com excel (Excel 2003 - xml)
 *
 * @param array $head Contém os dados de cabeçalho da tabela (veja exemplo) 
 * @param array $table Array com os dados do conteúdo da tabela (veja exemplo)
 * @param string $file Nome e localização completa do arquivo a ser gerado (default: retorna os dados do arquivo sem criar/salvar um arquivo)
 * 
 * @sample
 * Propriedades do Documento
 * $prop['author']	= "Nome do Autor";
 * Outras propriedades: 'lastAuthor', 'title', 'subject', 'description' e 'company'.
 *
 * O array "tables" deve ser formatado com os seguintes níveis de índices:
 * 1º nível: nome da planilha (ou tabela do banco de dados) - deve ser uma string;
 * 2º nível: número sequêncial de linhas;
 * 3º nível: título da coluna;
 * 
 * Exemplos:
 * $tables['Tabela'][0] = array('Titulo coluna 1'=>'valor', 'Titulo coluna 2'=>'valor', 'etc...');
 * $tables['OutraTabela'][0]['Coluna1'] = 'Valor da coluna';
 * 
 */
	 
	/*Segue um exemplo de uso real para um melhor entendimento:
	
	//Pegando e conformando os dados da tabela "INDICE"
	$indice = _db()->query('SELECT * FROM INDICE');
	foreach($indice as $kk=>$row){foreach($row as $k=>$v){$a['Indice'][$kk][$k] = $v;}}	
	
	//Pegando e conformando os dados da tabela "LOGS"
	$logs = _db()->query('SELECT * FROM LOGS');		
	foreach($logs as $kk=>$row){foreach($row as $k=>$v){$a['Logs'][$kk][$k] = $v;}}
	
	//chamando a função		
	$this->_doc_excel($a, PATH_WWW . 'teste.xls', array('author'=>'Paulo R. B. Rocha'));
	
	//Fazendo o download do arquivo (redirecionamento)
	_goto('teste.xls');
	*/	
		
	 
	function _doc_excel($tables = array(), $file = '', $prop = array()){
		if(count($tables) <= 0)	return false;
		//Definindo as propriedades defaults
		$defProp = array(
						'author'=>'Neos PHP Framework',
						'lastAuthor'=>'',
						'title'=>'Documento do Excel 2003 (xml)',
						'subject'=>'',
						'description'=>'',
						'company'=>'NEOS PHP Framework');
		$prop = array_merge($defProp, $prop);		
		
		//Iniciando uma pasta padrão Excel (2003 - xml)		
		$tabela = _doc_excel_head_workbook($prop);
		
		//Loop para cada planilha do excel
		foreach($tables as $KTable=>$table){
			
			$tabela .= '<Worksheet ss:Name="' . $KTable . '">
	<Table x:FullColumns="1" x:FullRows="1" ss:DefaultColumnWidth="105" ss:DefaultRowHeight="15">';   
			
			//montando o TITULO
			$tabela .= "\n" . '<Row ss:AutoFitHeight="0" ss:Height="28.5">';
			foreach(array_keys($table[0]) as $title){
				$tabela .= "\n" . '<Cell ss:StyleID="ntitle"><Data ss:Type="String">' . $title . '</Data></Cell>';			
			}
			$tabela .= "\n" . '</Row>';
	
			//montando as demais linhas da tabela
			foreach($table as $k=>$row){
				$tabela .= "\n" . '<Row ss:AutoFitHeight="0">';
				foreach($row as $val){
					if(is_numeric($val)) {
						$c = ' ss:StyleID="nnumero"';
						$t = 'Number';
					} else {
						$c = '';
						$t = 'String';
					}
					$tabela .= "\n" . '<Cell' . $c .'><Data ss:Type="' . $t .'">' . htmlspecialchars($val, ENT_QUOTES) . '</Data></Cell>';
				}
				$tabela .= "\n" . '</Row>';   
			}   		
			//fechando a tabela e acrescentando o rodapé
			$tabela .= "\n" . '</Table>' . _doc_excel_finish_worksheet();
			}
			
		//Finalizando a pasta do Excel	
		$tabela .= '</Workbook>';
   		//salvando o aquivo ou retornando os dados
   		if($file != '' && is_dir(dirname($file))) {file_put_contents($file, $tabela);}
   		else {return $tabela;}		
	}
	
/**
 * Retorna um cabeçalho padrão para o arquivo xml
 */	
	function _doc_excel_head_workbook($prop){
		return '<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Title>' . $prop['title'] . '</Title>
  <Subject>' . $prop['subject'] . '</Subject>
  <Author>' . $prop['author'] . '</Author>
  <LastAuthor>' . $prop['lastAuthor'] . '</LastAuthor>
  <Description>' . $prop['description'] . '</Description>
  <Created>' . date("Y-m-d") . 'T' . date("H:i:s") . 'Z</Created>
  <Company>' . $prop['company'] . '</Company>
  <Version>12.00</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>12270</WindowHeight>
  <WindowWidth>19095</WindowWidth>
  <WindowTopX>120</WindowTopX>
  <WindowTopY>120</WindowTopY>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"
     ss:Color="#A5A5A5"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"
     ss:Color="#A5A5A5"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"
     ss:Color="#A5A5A5"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"
     ss:Color="#A5A5A5"/>
   </Borders>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="ntitle">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="0" ss:Color="#FFFFFF"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="0" ss:Color="#FFFFFF"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="0" ss:Color="#FFFFFF"/>
   </Borders>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"
    ss:Bold="1"/>
  </Style>
  <Style ss:ID="ncenter">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="ntexto">
   <NumberFormat ss:Format="@"/>
  </Style>
  <Style ss:ID="nnumero">
   <NumberFormat ss:Format="#,##0_ ;[Red]\-#,##0\ "/>
  </Style>
  <Style ss:ID="nmoeda">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <NumberFormat ss:Format="&quot;R$&quot;\ #,##0.00"/>
  </Style>
 </Styles>' . "\n";
	}
	
/**
 * Retorna um rodapé padrão para a planilha
 */	
	function _doc_excel_finish_worksheet(){
		return "\n" .
 '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0.31496062000000002"/>
    <Footer x:Margin="0.31496062000000002"/>
    <PageMargins x:Bottom="0.78740157499999996" x:Left="0.511811024"
     x:Right="0.511811024" x:Top="0.78740157499999996"/>
   </PageSetup>
   <Unsynced/>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>' . "\n";	
	}
}