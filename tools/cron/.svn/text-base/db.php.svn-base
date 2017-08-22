<?php
include_once "../../config/config.inc.php";
class db{
	protected $conn;
	protected $index = 0;
	function open_db(){
		GLOBAL $CONFIG;

		$host = $CONFIG['DATABASE'][$this->index]['HOST'];
		$user = $CONFIG['DATABASE'][$this->index]['USERNAME'];
		$password = $CONFIG['DATABASE'][$this->index]['PASSWORD'] ;
		
		$this->conn = mysql_connect($host,$user,$password);

		if($this->conn)$result =  "can used db";
		else $result =  "cant connect";
	
		return $result;

	}
	
	function setSocketDB($index=0){
		$this->index=$index;
	}
	function query($sql){
	
		$this->open_db();
		$query = mysql_query($sql,$this->conn);
		$this->close_db();
		if($query) return true;
		else return false;
		// return $sql;
		
	}
	
	function fetch($sql,$all=false){
		$this->open_db();
		$data = array();
		$query = mysql_query($sql,$this->conn);
		if($all==true) {
			while($row = mysql_fetch_object($query)){
			$data[] = $row;
			}
		
		}else $data = mysql_fetch_object($query);
		
		$this->close_db();
		return $data;
	}
	
	function close_db(){
		if($this->conn!=null){mysql_close($this->conn);}
	}

	
}


?>
