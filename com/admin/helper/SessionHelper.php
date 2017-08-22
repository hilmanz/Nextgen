<?php
class SessionHelper{
	var $namespace;
	function __construct($namespace){
		$this->namespace = $namespace;
	}
	function set($name,$val){
		//jika sessionnya kosong, maka create session baru
		if(!isset($_SESSION[$name])){
			$p = array($name=>$val);
			$_SESSION[$this->namespace] = urlencode64(json_encode($p));
		}else{
			$arr = json_decode(urldecode64($_SESSION[$this->namespace]));
			$arr->$name = $val;
			$_SESSION[$this->namespace] = urlencode64(json_encode($arr));
		}
	}
	function get($name){
	
		if(isset($_SESSION[$content])){
			$arr = json_decode(urldecode64($_SESSION[$this->namespace]));
			if($arr){
				if(property_exists($arr,$name)) return $arr->$name;
				else return false;
			}
		}
		return false;
	}
	
	function setSession($content,$name,$val){
		
			if(!isset($_SESSION[$content])){		
				
					$p = array($name=>$val);
					$_SESSION[$content] = urlencode64(json_encode($p));
				
			}else{
					$arr = json_decode(urldecode64($_SESSION[$content]));
					$arr->$name = $val;
					$_SESSION[$content] = urlencode64(json_encode($arr));
			}
		
	}
	
	function getSession($content,$name){
		if(isset($_SESSION[$content])){
			$arr = json_decode(urldecode64($_SESSION[$content]));
			if($arr){			
				if(property_exists($arr,$name)) return $arr->$name;
				else return false;
			}
		}
		return false;
	}
}
?>