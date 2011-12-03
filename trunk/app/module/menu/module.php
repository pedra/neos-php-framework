<?php
/**
 * Module Menu - criação, gerenciamento e administração de menus
 * @copyright	NEOS PHP Framework - http://neosphp.com
 * @license		http://neosphp.com/license Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
 * @author		Paulo R. B. Rocha - prbr@ymail.com
 * @version		CAN : B4BC
 * @package		Module
 */

class Module_Menu_Module
    extends  \Neos\Base {

	function get($p){
        $id = _app('ID');
		if(!isset($p['book']) || !isset($p['baselink']) || !$id){return false;}
		$mn = '';
		
		$q = _db::query('	SELECT IND_ID,IND_PATH,IND_TITULO
							FROM INDICE
							WHERE IND_CAT=(SELECT CAT_ID FROM CATEGORIA WHERE UPPER(CAT_NOME)="' . strtoupper($p['book']) . '")
							ORDER BY IND_PATH,IND_ORDER,IND_ID');
		if($q){
			//transformando obj em array
			foreach($q as $v){$a[$v->IND_PATH][$v->IND_ID] = $v->IND_TITULO;}
			unset($q,$v);
			//formatação
			$t0 = "\n";
			$t1 = $t0 . "\t";
			$t2 = $t1 . "\t";
			$t3 = $t2 . "\t";
			$t4 = $t3 . "\t";
			$t5 = $t4 . "\t";
			//Pegando classe e id, se informados
			$mn .= '<ul';
			if(isset($p['class'])){$mn .= ' class="' . $p['class'] . '"';}
			if(isset($p['id'])){$mn .= ' id="' . $p['id'] . '"';}
			$mn .= '>';
			//loops para construção da listagem

			//nivel 1
			foreach($a[0] as $k=>$v){
				$temp[] = $k;
				if(end($temp) == $id){$classe = 'selected';}else{$classe = '';}
				$mn .= $t1 . '<li><a class="' . $classe . '" href="' . _app('URL_LINK') . trim($p['baselink'],' /') . '/' . end($temp) . '">' . $v . '</a>';

				if(isset($a[end($temp)])){
					$mn .= $t2 . '<ul>';
					//nível 2
					foreach($a[end($temp)] as $k1=>$v1){
						$temp[] = $k1;
						if(end($temp) == $id){$classe = 'selected';}else{$classe = '';}
						$mn .= $t3 . '<li><a class="' . $classe . '" href="' . _app('URL_LINK') . trim($p['baselink'],' /') . '/' . end($temp) . '">' . $v1 . '</a>';

						if(isset($a[end($temp)])){
							$mn .= $t4 . '<ul>';
							//nível 3
							foreach($a[end($temp)] as $k2=>$v2){
								$temp[] = $k2;
								if(end($temp) == $id){$classe = 'selected';}else{$classe = '';}
								$mn .= $t5 . '<li><a class="' . $classe . '" href="' . _app('URL_LINK') . trim($p['baselink'],' /') . '/' . end($temp) . '">' . $v2 . '</a>';

								if(isset($a[end($temp)])){

									$mn .= 'proximo loop, etc...';

								}else{array_pop($temp);}
								$mn .= '</li>';
							}
							$mn .= $t4 . '</ul>' . $t1;

						}else{array_pop($temp);}
						$mn .= '</li>';
					}
					$mn .= $t2 . '</ul>' . $t1;

				}else{array_pop($temp);}
				$mn .= '</li>';
			}
			$mn .= $t0 . '</ul>';

		}
		return $mn;
	}

}