<?php
/** 
 * UserAgents
 * @usage 		Para a classe USER e outros identificadores. 
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @version		CAN : B4BC
 * @package		Neos\Config
 
 * THIS CODE WAS COPIED OF CODEIGNITER FRAMEWORK (http://www.codeigniter.com) >>>
 * "Don't reinvent the wheel is the methodology!" Ou apenas: "Não reinvente a roda!"
 
*/

$_user_agents['platforms'] = 
array (
	'windows nt 6.1'		=> 'Windows Seven',
	'windows nt 6.0'		=> 'Windows Longhorn',
	'windows nt 5.2'		=> 'Windows 2003',
	'windows nt 5.0'		=> 'Windows 2000',
	'windows nt 5.1'		=> 'Windows XP',
	'windows nt 4.0'		=> 'Windows NT 4.0',
	'winnt4.0'				=> 'Windows NT 4.0',
	'winnt 4.0'				=> 'Windows NT',
	'winnt'					=> 'Windows NT',
	'windows 98'			=> 'Windows 98',
	'win98'					=> 'Windows 98',
	'windows 95'			=> 'Windows 95',
	'win95'					=> 'Windows 95',
	'windows'				=> 'Unknown Windows OS',
	'os x'					=> 'Mac OS X',
	'ppc mac'				=> 'Power PC Mac',
	'freebsd'				=> 'FreeBSD',
	'ppc'					=> 'Macintosh',
	'linux'					=> 'Linux',
	'debian'				=> 'Debian',
	'sunos'					=> 'Sun Solaris',
	'beos'					=> 'BeOS',
	'apachebench'			=> 'ApacheBench',
	'aix'					=> 'AIX',
	'irix'					=> 'Irix',
	'osf'					=> 'DEC OSF',
	'hp-ux'					=> 'HP-UX',
	'netbsd'				=> 'NetBSD',
	'bsdi'					=> 'BSDi',
	'openbsd'				=> 'OpenBSD',
	'gnu'					=> 'GNU/Linux',
	'unix'					=> 'Unknown Unix OS',
	'android'				=> 'Android'
);
// A ordem deste ARRAY não deve ser mudada. Muitos browsers retornam multiplos tipos e podemos, assim, identificar o sub-tipo primeiro.
$_user_agents['browsers'] = 
array(
	'Flock'					=> 'Flock',
	'Chrome/7'				=> 'Chrome 7',
	'Chrome/8'				=> 'Chrome 8',
	'Chrome/9'				=> 'Chrome 9',
	'Chrome/10'				=> 'Chrome 10',
	'Chrome'				=> 'Chrome',
	'Opera'					=> 'Opera',
	'MSIE 6'				=> 'Internet Explorer 6',
	'MSIE 7'				=> 'Internet Explorer 7',
	'MSIE 8'				=> 'Internet Explorer 8',
	'MSIE 9'				=> 'Internet Explorer 9',
	'MSIE'					=> 'Internet Explorer',
	'Internet Explorer'		=> 'Internet Explorer',
	'Shiira'				=> 'Shiira',
	'Firefox/2'				=> 'Firefox 2',
	'Firefox/3'				=> 'Firefox 3',
	'Firefox/4'				=> 'Firefox 4',
	'Firefox'				=> 'Firefox',
	'Chimera'				=> 'Chimera',
	'Phoenix'				=> 'Phoenix',
	'Firebird'				=> 'Firebird',
	'Camino'				=> 'Camino',
	'Netscape'				=> 'Netscape',
	'OmniWeb'				=> 'OmniWeb',
	'Safari'				=> 'Safari',
	'Mozilla'				=> 'Mozilla',
	'Konqueror'				=> 'Konqueror',
	'icab'					=> 'iCab',
	'Lynx'					=> 'Lynx',
	'Links'					=> 'Links',
	'hotjava'				=> 'HotJava',
	'amaya'					=> 'Amaya',
	'IBrowse'				=> 'IBrowse'
);
$_user_agents['mobiles'] = 
array(
	'mobileexplorer'		=> 'Mobile Explorer',
//	'openwave'				=> 'Open Wave',
//	'opera mini'			=> 'Opera Mini',
//	'operamini'				=> 'Opera Mini',
//	'elaine'				=> 'Palm',
	'palmsource'			=> 'Palm',
//	'digital paths'			=> 'Palm',
//	'avantgo'				=> 'Avantgo',
//	'xiino'					=> 'Xiino',
	'palmscape'				=> 'Palmscape',
//	'nokia'					=> 'Nokia',
//	'ericsson'				=> 'Ericsson',
//	'blackberry'			=> 'BlackBerry',
//	'motorola'				=> 'Motorola'
	// Telefones e fabricantes
	'motorola'				=> "Motorola",
	'nokia'					=> "Nokia",
	'palm'					=> "Palm",
	'iphone'				=> "Apple iPhone",
	'ipad'					=> "iPad",
	'ipod'					=> "Apple iPod Touch",
	'sony'					=> "Sony Ericsson",
	'ericsson'				=> "Sony Ericsson",
	'blackberry'			=> "BlackBerry",
	'cocoon'				=> "O2 Cocoon",
	'blazer'				=> "Treo",
	'lg'					=> "LG",
	'amoi'					=> "Amoi",
	'xda'					=> "XDA",
	'mda'					=> "MDA",
	'vario'					=> "Vario",
	'htc'					=> "HTC",
	'samsung'				=> "Samsung",
	'sharp'					=> "Sharp",
	'sie-'					=> "Siemens",
	'alcatel'				=> "Alcatel",
	'benq'					=> "BenQ",
	'ipaq'					=> "HP iPaq",
	'mot-'					=> "Motorola",
	'playstation portable'	=> "PlayStation Portable",
	'hiptop'				=> "Danger Hiptop",
	'nec-'					=> "NEC",
	'panasonic'				=> "Panasonic",
	'philips'				=> "Philips",
	'sagem'					=> "Sagem",
	'sanyo'					=> "Sanyo",
	'spv'					=> "SPV",
	'zte'					=> "ZTE",
	'sendo'					=> "Sendo",
	// SO
	'symbian'				=> "Symbian",
	'SymbianOS'				=> "SymbianOS",
	'elaine'				=> "Palm",
	'palm'					=> "Palm",
	'series60'				=> "Symbian S60",
	'windows ce'			=> "Windows CE",
	// Browsers
	'obigo'					=> "Obigo",
	'netfront'				=> "Netfront Browser",
	'openwave'				=> "Openwave Browser",
	'mobilexplorer'			=> "Mobile Explorer",
	'operamini'				=> "Opera Mini",
	'opera mini'			=> "Opera Mini",
	// Outros
	'digital paths'			=> "Digital Paths",
	'avantgo'				=> "AvantGo",
	'xiino'					=> "Xiino",
	'novarra'				=> "Novarra Transcoder",
	'vodafone'				=> "Vodafone",
	'docomo'				=> "NTT DoCoMo",
	'o2'					=> "O2",
	// Genéricos (!?)
	'mobile'				=> "Generic Mobile",
	'wireless'				=> "Generic Mobile",
	'j2me'					=> "Generic Mobile",
	'midp'					=> "Generic Mobile",
	'cldc'					=> "Generic Mobile",
	'up.link'				=> "Generic Mobile",
	'up.browser'			=> "Generic Mobile",
	'smartphone'			=> "Generic Mobile",
	'cellphone'				=> "Generic Mobile"
);
$_user_agents['robots'] = 
array(
	'googlebot'				=> 'Googlebot',
	'msnbot'				=> 'MSNBot',
	'slurp'					=> 'Inktomi Slurp',
	'yahoo'					=> 'Yahoo',
	'askjeeves'				=> 'AskJeeves',
	'fastcrawler'			=> 'FastCrawler',
	'infoseek'				=> 'InfoSeek Robot 1.0',
	'lycos'					=> 'Lycos'
);
$_user_agents['lang'] = 
array(
	'pt-BR'					=> 'Português Brasileiro',
	'pt'					=> 'Português',
	'en-US'					=> 'USA English',
	'en-UK'					=> 'United Kingdom English',
	'en'					=> 'English'
);
return $_user_agents;