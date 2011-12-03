<?php
/**
 * Este HELPER força o download do arquivo indicado e termina o NEOS (exit);
 * $f 	= 	nome do arquivo a ser enviado;
 * $d 	= 	diretório (teminado em "/") do arquivo, se '$dt' não for indicado;
 * $dt	=	opcionalmente pode conter o 'arquivo' para o download - neste caso '$d' é ignorado...
 * @package		Neos\Helper
 */

if(!function_exists('_download')){
	function _download($d = '', $f = '', $dt = ''){
		//arquivo existe??
		if(!file_exists($d . $f) && $dt == ''){exit();}
		//procurando o mime type
		include PATH_NEOS . 'config/mimes.php';
		$ext = explode('.', $f);
		$ext = end($ext);
		if(!isset($mimes[$ext])){$mime = 'application/octet-stream';}
		else{$mime = (is_array($mimes[$ext])) ? $mimes[$ext][0] : $mimes[$ext];}
		//pegando o arquivo
		if($dt == ''){$dt = file_get_contents($d . $f);}
		//limpando o cache OB, se existir
		ob_end_clean();		
		//ajustando o browser...
		if (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')){
			header('Content-Type: "' . $mime . '"');
			header('Content-Disposition: attachment; filename="' . $f . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header("Content-Transfer-Encoding: binary");
			header('Pragma: public');
			header('Content-Length: ' . strlen($dt));
		}else{
			header('Content-Type: ' . $mime . '');
			header('Content-Disposition: attachment; filename="' . $f . '"');
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Pragma: no-cache');
			header('Content-Length: ' . strlen($dt));
		}
		//saindo...
		exit($dt);
	}
}