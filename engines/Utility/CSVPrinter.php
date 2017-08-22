<?php
class CSVPrinter{
	var $_job;
	var $_QueryLimit;
	var $_docID;
	var $secretKey = "Th3L1ttl3H0bb1t5";
	var $fields;
	var $labels;
	var $_data;
	function CSVPrinter($docID){
		$this->_QueryLimit = 20;
		$this->_docID = $docID;
	}
	function getKey(){
		return md5($this->_docID.date("YmdHi").$this->secretKey);
	}
	function setFields(){
		$this->fields = func_get_args();
		//print_r($fields);
	}
	function setLabels(){
		$this->labels = func_get_args();
	}
	function setData($data){
		
		$this->_data = $data;
	}
	function output(){
		$k = sizeof($this->fields);
		$filename = $this->_docID.date("_YmdHis");
		$strOutput = "no";
		for($j=0;$j<$k;$j++){
			$strOutput.=",".$this->labels[$j];
		}
		//print $strOutput."<br/>";
		$n = sizeof($this->_data);
		
		for($i=0;$i<$n;$i++){
			$strOutput.="\n";
			$strOutput.="'".($i+1)."'";
			for($j=0;$j<$k;$j++){
				$strOutput.=",'".$this->_data[$i][$this->fields[$j]]."'";
			}
		}
		header("Content-type: application/octet-stream");
    	header("Content-Disposition: attachment; filename=".$filename.".csv");
    	header("Pragma: no-cache");
    	header("Expires: 0");
		print $strOutput;
		exit();
	}
	function printing($job,$requestID){
		if($this->authenticate($requestID)){
			$this->_job = $job;

			//we gonna do printing in the next 3 seconds.
			sleep(3);
			$this->_doPrinting();
		}else{
			print "Maaf, anda tidak dapat mendownload document ini. silahkan coba kembali beberapa saat lagi.";
		}
	}
	
	function authenticate($requestID){
		$the_key = md5($this->_docID.$requestID.$this->secretKey);
		if($the_key==$this->getKey()){
			return true;
		}
	}
	function _doPrinting(){
		$total_pages = $this->_job['pages'];
		$sql = $this->_job['sql'];
		$current = 0;
		$total_rows = $this->_QueryLimit;
		$nLoop = ceil($total_pages/$total_rows);
		//print $nLoop;
		//untuk sementara kita print bego dulu -->
		
		//-->
	}
	
}
?>