<?php 
/** 
* @package Neos\Asset 
*/
$e='_'.end(explode('/',$_SERVER['REQUEST_URI']));$dir=dirname(__FILE__).DIRECTORY_SEPARATOR;if(file_exists($dir.$e)){$headers=apache_request_headers();$etag=(isset($headers['If-None-Match']))?str_replace('"','',$headers['If-None-Match']):'';$lmod=(isset($headers['If-Modified-Since']))?strtotime($headers['If-Modified-Since']):0;$row=MD::fileEtag($e);if($row===false)criar($e,$etag,$dir);$data=MD::getData();if($data[$row]['ETAG']==$etag && $data[$row]['TIME']<=$lmod)noModify($etag,$lmod);criar($e,$etag,$dir);}else{naoExiste();}function criar($e,$etag,$dir){if($etag=='')$etag=md5(microtime(true));$lmod=filemtime($dir.$e);$row=MD::fileEtag($e);if($row!==false){$data=MD::getData();$etag=$data[$row]['ETAG'];$data[$row]['TIME']=$lmod;}else{$data=MD::getData();$c=count($data);$data[$c]['ETAG']=$etag;$data[$c]['FILE']=$e;$data[$c]['TIME']=$lmod;}MD::setData($data);ob_start('ob_gzhandler');header('Content-type: text/javascript');header('Expires: '.gmdate('D, d M Y H:i:s',time()+31536000).' GMT');header('Last-Modified: '.gmdate('D, d M Y H:i:s',$lmod).' GMT');header('Cache-Control: max-age=31536000');header('X-Powered-By: NEOS PHP Framework');header('Etag: "'.$etag.'"');exit(file_get_contents($dir.$e));}function noModify($etag,$lmod){header('X-Powered-By: NEOS PHP Framework');header('Content-type: text/javascript');header('Last-Modified: '.gmdate('D, d M Y H:i:s',$lmod).' GMT');header('Etag: "'.$etag.'"',true,304);exit();}function naoExiste(){header('X-Powered-By: NEOS PHP Framework',true,404);exit();}class MD{static $THIS=null;public $dataFile;public $data;public static function this(){if(!isset(static::$THIS))static::$THIS=new static;return static::$THIS;}protected function __construct($dataFile=''){if($dataFile=='')$dataFile=dirname(__FILE__).'/microdata.mdata';$this->dataFile=$dataFile;if(!file_exists($this->dataFile))file_put_contents($this->dataFile,'');$d=file($dataFile,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);$r=array();foreach($d as $v){$c=count($r);$t=explode(' | ',$v);$r[$c]['ETAG']=$t[0];$r[$c]['FILE']=$t[1];$r[$c]['TIME']=$t[2];}$this->data=$r;}static function getData($row=''){$md=self::this();if($row!='' && isset($md->data[$row]))return $md->data[$row];return $md->data;}static function setData($row,$id='',$val=''){$md=self::this();if(is_array($row))return $md->data=$row;if(isset($md->data[$row]))return $md->data[$row][$id]=$val;return false;}function __destruct(){$md=self::this();$t='';foreach($md->data as $v){$t.=implode(' | ',$v)."\n";}return file_put_contents($md->dataFile,$t);}static function fileEtag($e){$md=self::this();foreach($md->data as $k=>$v){if($v['FILE']==$e)return $k;}return false;}}