<?php
class SQLData{
	var $schema;
	var $conn;
	var $rs;
	var $msg;
	var $lastInsertId;
	var $autoconnect=false;
	var $database;
	var $previous_query;
	var $idxdb=0;
	function SQLData(){
		global $CONFIG;
		$this->msg="";
		$this->host = $CONFIG['DATABASE'][0]['HOST'];
		$this->username = $CONFIG['DATABASE'][0]['USERNAME'];
		$this->password = $CONFIG['DATABASE'][0]['PASSWORD'];
		$this->database = $CONFIG['DATABASE'][0]['DATABASE'];
	}
	function getConnection(){
		return $this->conn;
	}
	function open($db=0){
		
		global $CONFIG;
		$this->idxdb = $db;
        $this->host = $CONFIG['DATABASE'][$db]['HOST'];
		$this->username = $CONFIG['DATABASE'][$db]['USERNAME'];
		$this->password = $CONFIG['DATABASE'][$db]['PASSWORD'];
		$this->database = $CONFIG['DATABASE'][$db]['DATABASE'];
		
		if($this->conn==NULL){
			
			$this->conn = mysql_connect($this->host,$this->username,$this->password);
			$this->addMessage("Open Connection -->".$this->conn);
			$this->setForceMySqlMode();
		}else{
			$this->addMessage("Connection already opened : ".$this->conn."<br/>");
		}	
		//print $this->database;
	}
	function addMessage($msg){
		$this->msg.=$msg."<br/>";
	}
	
	function setForceMySqlMode(){
	
		mysql_query("SET SESSION sql_mode = 'NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' ",$this->conn);
		
	}
	function close(){
		if($this->conn!=NULL){
			if(@mysql_close($this->conn)){
				$this->addMessage("Connection closed --> ".$this->conn);
				$this->conn=NULL;
			}
		}else{
			$this->addMessage("Connection already closed --> ".$this->conn);
		}
	}
	function setSchema($schema){
		$this->schema = $schema;
	}
	function force_utf8(){
		mysql_query('SET CHARACTER SET utf8');
	}
	/**
	 * @deprecated
	 */
	function force_connect($flag){
		$this->autoconnect = $flag;
	}
	function fetch($str,$flag=0){
		$rs = null;
		
		if($this->conn==null) $this->open($this->idxdb);
		$sql = $this->query($str);
		if($sql){
			if($flag){
				$n=0;
				while($fetch = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$rs[$n] = $fetch;
					$n++;
				}
			}else{
				$rs = mysql_fetch_array($sql,MYSQL_ASSOC);
			}
			mysql_free_result($sql);
		}
		if($this->conn==null) $this->close();
		$this->previous_query = $sql;
		return $rs;
	}
	function fetch_single($sql,$flag=false){
		if($flag)$this->open($this->idxdb);
		$q = $this->query($sql);
		$n=0;
		$fetch = @mysql_fetch_array($q,MYSQL_ASSOC);
		mysql_free_result($q);
		if($flag)$this->close();
		$this->previous_query = $sql;
		return $fetch;
	}
	function fetch_many($sql,$flag=false){
		if($flag)$this->open($this->idxdb);
		$q = $this->query($sql);
		$n=0;
		while($fetch = @mysql_fetch_array($q,MYSQL_ASSOC)){
			$rs[$n] = $fetch;
			$n++;
		}
		mysql_free_result($q);
		if($flag)$this->close();
		$this->previous_query = $sql;
		return $rs;
	}
	function reset(){
		$this->rs = NULL;
		
	}
	function query($sql,$flag=false){
		$this->addMessage("do query using ".$this->conn.":".PHP_EOL);
		if($this->conn==null) $this->open($this->idxdb);			
		$sDbQuery = mysql_select_db($this->database);
		
		
		
		$rs = mysql_query($sql,$this->conn);	
		
		//do these as default if the query have "INSERT" keyword
		if(@eregi("INSERT",$sql)){
			$this->lastInsertId = mysql_insert_id();
		}
		$this->addMessage($rs);
		$this->addMessage(mysql_error().PHP_EOL);
		
		if($this->conn==null) $this->close();	
		$this->previous_query = $sql;
		return $rs;
	}
	function query2($sql,$flag=0){
		$rs = mysql_query($sql);
		if($flag){
			$this->lastInsertId = mysql_insert_id();
		}
		$this->previous_query = $sql;
		return $rs;
	}
	function getMessage(){
		$msg=mysql_error();
		$msg.="<br/>";
		$msg.=$this->msg;
		return $msg;
	}
	function getConsoleMessage(){
		$msg=mysql_error();
		$msg.="\n";
		$msg.=str_replace("<br/>","\n",$this->msg);
		return $msg;
	}
	function getLastInsertId(){
		return $this->lastInsertId;
	}
	/**
	 * magic finder method
	 * @example
	 * $rs = $this->find('all',array('schema'=>'db_name.tbl_users',
													'conditions'=>array('n_status=1',"(name='foobar' OR name='Thorfinn Karlsefni')"),
													'limit'=>5,
													'start'=>1));
		$rs = $this->find('count',array('schema'=>'db_name.tbl_users',
													'aliases'=>array('db_name.tbl_users'=>'a','db_name.tbl_shops'=>'b'),
												    'joins'=>array(
												    				array('db_name.tbl_shops','b.user_id = a.id','LEFT')
																  ),
												    'limit'=>1,
												    'fields'=>'COUNT(a.id) as total',
												    'conditions'=>array('a.n_status<>0',"b.keywords = ''")
													));
		print $this->last_query()."<br/>";
	 */
	function find($type,$parameter){
		//the parameters
		$schema = $parameter['schema']; //string
		$aliases = $parameter['aliases']; //array
		$conditions=$parameter['conditions']; //array
		$joins=$parameter['joins']; //array
		$limit=intval($parameter['limit']); //int
		$fields=$parameter['fields']; //string
		$group = $parameter['groups']; //array
		
		//the query parts
		$startOffset = intval($parameter['start']); //int
		$strField = "*";
		$strJoins = $schema;
		if(is_array($aliases)){
			$strJoins.=" {$aliases[$schema]}";
		}
		$strWhere = "";
		$strLimit = "";
		$strGroupBy = "";
		if($fields!=null){
			$strField = $fields;
		}
		if($joins!=null){
			foreach($joins as $v){
				if($v[2]=="LEFT"){
					$join = "LEFT JOIN";
				}else if($v[2]=="RIGHT"){
					$join = "RIGHT JOIN";
				}else{
					$join = "INNER JOIN";
				}
				if(is_array($aliases)){
					$strJoins .= " {$join} {$v[0]} {$aliases[$v[0]]} ON {$v[1]}".PHP_EOL;
				}else{
					$strJoins .= " {$join} {$v[0]} ON {$v[1]}".PHP_EOL;
				}
			}
		}
		if(sizeof($conditions)>0){
			$strWhere = "WHERE ";
			foreach($conditions as $n=>$v){
				if($n>0){
					$strWhere.=" AND ";
				}
				$strWhere.= $v;
			}
		}
		if(sizeof($group)){
			$strGroupBy = "GROUP BY ";
			foreach($group as $n=>$v){
				if($n>0){
					$strGroupBy.=",";
				}
				$strGroupBy.="{$v}";
			}
		}
		if($type=='all'){
			if($limit==0){
				$limit = 20;
			}
			$strLimit = "LIMIT {$startOffset},{$limit}";
		}else{
			if($limit==0){
				$limit= 1;
			}
			$strLimit = "LIMIT {$limit};";
		}
		//wrapping everything
		$sql = "SELECT {$strField} FROM {$strJoins} {$strWhere} {$strGroupBy} {$strLimit}";
		$this->previous_query = $sql;
		if($limit==1){
			return $this->fetch_single($sql);
		}else{
			return $this->fetch_many($sql);
		}
	}
	public function last_query(){
		return $this->previous_query;
	}
}
?>