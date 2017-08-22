<?php
include_once $GLOBAL_PATH."core/Smarty/Smarty.class.php";
/**
 * a Smarty Driver for Frozbite
 * @package com.parser.Smarty
 * @author Hapsoro Renaldy <renaldy@dufronte.com>
 */
class SmartyDriver extends Smarty{
	var $templates = array();
	 function gettemp($filename,$name){
			//print("$filename-->$name<br>");
		$this->templates[$name] = $filename;
	}
	 function display($str){
		//die($this->templates[$str]);
		//print $this->template_dir;
		//print $this->toString(str);
	    //	die("yes");
		parent::display($this->templates[$str]);
	}
	 function toString($str){
		return parent::fetch($this->templates[$str]);
	 }
	 function setbasedir($path){
	 	global $GLOBAL_PATH,$_SMARTY;
		$this->template_dir = $path."/"; 
       	//$this->compile_dir  = $GLOBAL_PATH.'tmp/'; 
		$this->compile_dir = $_SMARTY['compile_path'];
      //	print $this->compile_dir;
        $this->config_dir   = $GLOBAL_PATH.'config/'; 
       // $this->cache_dir    = $GLOBAL_PATH.'cache/'; 
        $this->caching = false; 
	}
}
?>
