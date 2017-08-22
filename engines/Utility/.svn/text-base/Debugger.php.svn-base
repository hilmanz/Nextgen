<?php
class Debugger{
	var $isDebug = false;
	var $path="../logs/";
	var $appName = "";
	function enable($foo){
		$this->isDebug = $foo;
	}
	function setAppName($app_name){
		$this->appName = $app_name;
	}
	function setDirectory($path="../logs/"){
		$this->path = $path;
	}
	function addLog($msg,$user,$wsdl,$ws_method,$sessionID,$sResult){
		if($this->isDebug){
		$sDate = date("d-m-Y H:i:s");
		//$sFilename = "/home/umild/logs/debug-".date("Ymd").".csv";
		$sFilename = $this->path."debug-".date("Ymd").".csv";
		if(!file_exists($sFilename)){
			$fp = fopen($this->path."debug-".date("Ymd").".csv","a+");
			if (flock($fp, LOCK_EX)) { // do an exclusive lock
				$sMsg = "Date,Activity,User,Web Service,Command,Response ,SessionID\n";
				fwrite($fp,$sMsg,strlen($sMsg));
				flock($fp, LOCK_UN); // release the lock
			}
				fclose($fp);	
			
		}
		$fp = fopen($this->path."debug-".date("Ymd").".csv","a+");
		if (flock($fp, LOCK_EX)) { // do an exclusive lock
			$sMsg = "\"".$sDate."\",\"".$msg."\",$user,\"".$wsdl."\",$ws_method,$sResult,$sessionID\n";
			fwrite($fp,$sMsg,strlen($sMsg));
			flock($fp, LOCK_UN); // release the lock
		}
		fclose($fp);	
		}
	}
	function info($msg){
		$msg = "[INFO]".$msg;
		if(array_key_exists('subdomain',$_SESSION)){
			if($_SESSION['subdomain']!=null){
				$msg="[".$_SESSION['subdomain']."] ".$msg;
			}
		}
		$this->log($msg);
		
	}
	function error($msg){
		$msg = "[ERROR]".$msg;
		$this->log($msg);
	}
	function status($msg,$flag){
		if($flag){
			$this->info($msg." OK");
		}else{
			$this->error($msg." ERROR");
		}
	}
	function log($msg){
		$msg = date("Y-m-d H:i:s")." ".$msg."\n";
		$sFilename = $this->path.$this->appName."-".date("Ymd").".log";
		$fp = fopen($sFilename,"a+");
		if (flock($fp, LOCK_EX)) { // do an exclusive lock
			
			fwrite($fp,$msg,strlen($msg));
			flock($fp, LOCK_UN); // release the lock
		}
		fclose($fp);	
	}
}
?>