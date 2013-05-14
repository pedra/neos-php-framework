<?php
namespace Library;
/**
 * Sistema de CACHE simples
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Neos\Library
 * @access 		public
 * @since		CAN : B4BC
 */

class Cache
	extends \NEOS {	
	
	static function start($func){
		$fname = \_cfg::this()->cache . 'cache_' . str_replace('/', '_', URL_SEG) . '.html';			
		
		if(file_exists($fname)){
			$fhead = file_get_contents($fname, NULL, NULL, 0, 50);
			$tm = strpos($fhead, '!!!');
			$val = 0 + substr($fhead, 0, $tm);
			if($val > time()){
				header('Expires: ' . gmdate('D, d M Y H:i:s', time() + \_cfg::this()->out_expires) . ' GMT');
				echo file_get_contents($fname, NULL, NULL, 3 + $tm);
				
				//gravando o log
				if(isset(\_cfg::this()->cache_log) && \_cfg::this()->cache_log != ''){
					$dd = URL . URL_SEG . ' || '. time() . ' || ' . $fname . "\n";
					file_put_contents(\_cfg::this()->cache . \_cfg::this()->cache_log, $dd, FILE_APPEND);
				}
				
				//chamando a barra de status
				//if(strpos(trim(\_cfg::this()->status),'file')!==false){$s=new NEOS_Status(true);}
				\_cfg::this()->out_filter = false;
				exit();	
			}
		}
	}
}
?>