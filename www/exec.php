<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<?php

class index 
	extends abstract_ 
	implements interface_ {
		
	protected static $_this = null;
	protected static $cfg = array();
	private static $test = 'propriedade static $test';	
	
	function set($a = array()){ self::$cfg = $a; echo '<p>set da classe: ' . __CLASS__ . '</p>';		}
	function xset($a = array()){ self::$cfg = $a; echo '<p>xset da classe: ' . __CLASS__ . '</p>';		}	
}

class xindex 
	extends abstract_ 
	implements interface_ {
		
	protected static $_this = null;
	protected static $cfg = array();
	private static $test = 'propriedade static $test';	
	
	function set($a = array()){ self::$cfg = $a; echo '<p>set da classe: ' . __CLASS__ . '</p>';		}
	function xset($a = array()){ self::$cfg = $a; echo '<p>xset da classe: ' . __CLASS__ . '</p>';		}	
}

abstract class abstract_ {
	
	protected static $cfg = array();
		
	function __construct(){echo '<h3>classe construída: ' . get_called_class() . '</h3>';}
	function __destruct(){echo '<h3>classe destruída: ' . get_called_class() . '</h3>';}
	static function this(){return (!isset(static::$_this)) ? static::$_this = new static : static::$_this;}
	
	//abstract function set($a = array());
	function _set($a = array()){ self::$cfg = $a;echo '<p>_set da classe: ' . __CLASS__ . '</p>';}
	function _get()	{ 			return self::$cfg;	}	
	function get()	{ 			return static::$cfg;	}
}

interface interface_ {
	
	static function this();
		
	function set($a = array());
	function get();
	function _get();
}



$pt = function ($v){echo '<pre>' . print_r($v, true) . '</pre>';};

//index::this();

echo '<h4>Gravando o Array</h4>';

$a = array('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro7');
$b = array('parametro1', 'parametro2', 'parametro3');
$c = array('par1', 'par2', 'par3');

index::set($a);
xindex::set($a);

index::_set($c);
xindex::_set($c);

$index = new index;
$xindex = new xindex;

$index->_set($a);
$index->set($b);


$xindex->_set($c);
$xindex->set($a);

echo '<h2>index::get()</h2>';
$pt(index::get());
echo '<h2>index::_get()</h2>';
$pt(index::_get());

echo '<h2>xindex::get()</h2>';
$pt(xindex::get());
echo '<h2>xindex::_get()</h2>';
$pt(xindex::_get());

echo '<h2>$index::get()</h2>';
$pt($index::get());
echo '<h2>$index::_get()</h2>';
$pt($index::_get());

echo '<h2>$xindex::get()</h2>';
$pt($xindex::get());
echo '<h2>$xindex::_get()</h2>';
$pt($xindex::_get());


xindex::set('novo valor');
abstract_::_set('valor iiii');

$pt($xindex->_get());
$pt($index->_get());

echo '<h4>Pegando a Classe</h4>';
$pt(index::this());
$pt($index->this());
echo '<h4>Pegando a xClasse</h4>';
$pt(xindex::this());
$pt($xindex->this());


?> 


</body>
</html>