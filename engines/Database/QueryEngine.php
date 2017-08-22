<?php
/**
* @package kana.Database
* QueryEngine class
* @author Hapsoro Renaldy N <hapsoro.renaldy@kana.co.id>
*/
class QueryEngine extends SQLData{
	var $schema = "";
	var $fields;
	var $structs;
	var $last_query_stmt;
	//constant
	var $SQL_SKIP = "0xff001";
	var $SQL_NOW = "0xff002";
	
	//other
	var $FoundRows = 0;
	var $joinTables;
	var $joinStmt = "";
	
	
	/**
	* @params string $schema schema / table to use with these class.
	* @parmas array $fields  --> under development
	*/
	function QueryEngine($schema,$fields = NULL){
		if($schema!=NULL){
			$this->schema = $schema;
			$this->fields = $fields;
			SQLData::SQLData();
			//retrieving schema informations
			$this->getInfo();
		}else{
			
			$this->error("the schema has not been specified. Please check your configuration !");
			die($this->getErrorMessage());
		}
	}
	
	/**
	 * get schema fields
	 */
	function getInfo(){
		$this->open();
		$rs = $this->fetch("SHOW FIELDS FROM ".$this->schema,1);
		//print_r($rs);
		$this->close();
		
		for($i=0;$i<sizeof($rs);$i++){
			if($rs[$i]['Field']!="id"){
				$this->structs[] = $rs[$i];	
			}
		}
	}
	/**
	 * Insert data into table
	 * @param array $params array of parameters
	 * @return boolean
	 */
	function insert($params){
		$this->open();
		//INSERT INTO schema(field1,field2,field3) VALUES('param1','param2','param3')
		$stmt = "INSERT INTO ".$this->schema."(";
		for($i=0;$i<sizeof($this->structs);$i++){
			if($i!=0){
				$stmt.=",";	
			}
			$stmt.=$this->structs[$i]['Field'];
		}
		$stmt.=") VALUES(";
		for($i=0;$i<sizeof($params);$i++){
			if($i!=0){
				$stmt.=",";	
			}
			$stmt.="'".$params[$i]."'";
		}
		$stmt.=")";
		
		//execute the statement
		$rs = $this->query($stmt);
		
		if(!$rs){
			$this->error("Error in Query Statement -> ".mysql_error());	
		}
		//print $stmt;
		$this->last_query_stmt[] = $stmt;
		$this->close();
		return $rs;
	}
	/**
	 * Retrieve one row from specified id
	 * @param int $id Primary Key to get the data from
	 * @return array
	 */
	function retrieve($id){
		$this->open();
		$rs = $this->fetch("SELECT * FROM ".$this->schema." WHERE id='".$id."' LIMIT 1");
		$this->close();
		return $rs;
	}
	/**
	 * Retrieve one row from specified id with condition
	 * @param int $id Primary Key to get the data from
	 * @return array
	 */
	function retrieveWithCondition($id,$condition){
		$strCond = "";
		for($i=0;$i<sizeof($condition);$i++){
			if($i>0){
				$strCond.=" AND ";
			}
			$strCond.= $condition[$i][0].".".$condition[$i][1]." ".$condition[$i][2]." '".$condition[$i][3]."'";
		}
		$this->open();
		$rs = $this->fetch("SELECT * FROM ".$this->schema." WHERE id='".$id."' AND ".$strCond." LIMIT 1");
		//print "SELECT * FROM ".$this->schema." WHERE id='".$id."' AND ".$strCond." LIMIT 1";
		$this->close();
		return $rs;
	}
	
	/**
	 * Retrieve from Joined Table
	 * @param int $id Primary Key to get the data from
	 * @return array
	 */
	function retrieveFromJoint($id){
		
		$schema = array();
		$fields = array();
		$foo = $this->getJoinedSchemas($schema,"",$fields);
		$schema_used = array();
		$fields = $foo[2];
		$stmtFields = "";
		$n=0;
	//	print_r($fields);
		foreach($fields as $name=>$val){
			for($j=0;$j<sizeof($val);$j++){
				if($n>0){
					$stmtFields.=",";
				}
				$stmtFields.=$name.".".$val[$j]['Field']." as ".$name."_".$val[$j]['Field'];
				$n++;
			}
		}
		$stmt = "SELECT ".$stmtFields." FROM ".$this->schema;
		for($i=0;$i<sizeof($foo[0]);$i++){
			if(!$schema_used[$foo[0][$i]]){
				$stmt.=",".$foo[0][$i];
				$schema_used[$foo[0][$i]] = true;
			}
		}
		$stmt.=" WHERE ".$this->schema.".id='".$id."' AND ".$foo[1]." ORDER BY ".$this->schema.".id DESC LIMIT 1";
		$this->open();
		$result = $this->fetch($stmt,1);
		//print $stmt;
		
		
		$this->close();
		return $result;
	}
	/**
	 * Retrieve from Joined Table with condition
	 * @param int $id Primary Key to get the data from
	 * @param array $condition array of conditions to be met.
	 * @return array
	 */
	function retrieveFromJointWithCondition($id,$condition){
		$strCond = "";
		for($i=0;$i<sizeof($condition);$i++){
			if($i>0){
				$strCond.=" AND ";
			}
			$strCond.= $condition[$i][0].".".$condition[$i][1]." ".$condition[$i][2]." '".$condition[$i][3]."'";
		}
		$schema = array();
		$fields = array();
		$foo = $this->getJoinedSchemas($schema,"",$fields);
		$schema_used = array();
		$fields = $foo[2];
		$stmtFields = "";
		$n=0;
	//	print_r($fields);
		foreach($fields as $name=>$val){
			for($j=0;$j<sizeof($val);$j++){
				if($n>0){
					$stmtFields.=",";
				}
				$stmtFields.=$name.".".$val[$j]['Field']." as ".$name."_".$val[$j]['Field'];
				$n++;
			}
		}
		$stmt = "SELECT ".$stmtFields." FROM ".$this->schema;
		for($i=0;$i<sizeof($foo[0]);$i++){
			if(!$schema_used[$foo[0][$i]]){
				$stmt.=",".$foo[0][$i];
				$schema_used[$foo[0][$i]] = true;
			}
		}
		$stmt.=" WHERE ".$this->schema.".id='".$id."' AND ".$foo[1]." AND ".$strCond." ORDER BY ".$this->schema.".id DESC LIMIT 1";
		$this->open();
		$result = $this->fetch($stmt,1);
		//print $stmt;
		
		
		$this->close();
		return $result;
	}
	/**
	 * Retrieve all rows from the table.
	 * @param int start row
	 * @param int total row to retrieve
	 * @return array
	 */
	function grab($start,$total){
		$this->open();
		//print "SELECT SQL_CALC_FOUND_ROWS * FROM ".$this->schema." ORDER BY id DESC LIMIT ".$start.",".$total;
		$result = $this->fetch("SELECT SQL_CALC_FOUND_ROWS * 
							   FROM ".$this->schema." 
							   ORDER BY id DESC 
							   LIMIT ".$start.",".$total,1);
		$rs = $this->fetch("SELECT FOUND_ROWS() as total");
		$this->FoundRows = $rs['total'];
		$this->close();
		return $result;
	}
	/**
	 * Retrieve all rows from the table with conditions involved.
	  * @param array $condition array of conditions to be met.
	 * @param int start row
	 * @param int total row to retrieve
	 * @return array
	 */
	function grabWithCondition($condition,$start,$total){
		$this->open();
		//print "SELECT SQL_CALC_FOUND_ROWS * FROM ".$this->schema." ORDER BY id DESC LIMIT ".$start.",".$total;
		$strCond = "";
		for($i=0;$i<sizeof($condition);$i++){
			if($i>0){
				$strCond.=" AND ";
			}
			$strCond.= $condition[$i][0].".".$condition[$i][1]." ".$condition[$i][2]." '".$condition[$i][3]."'";
		}
		
		$result = $this->fetch("SELECT SQL_CALC_FOUND_ROWS * 
							   FROM ".$this->schema." WHERE ".$strCond."
							   ORDER BY id DESC 
							   LIMIT ".$start.",".$total,1);
		$rs = $this->fetch("SELECT FOUND_ROWS() as total");
		$this->FoundRows = $rs['total'];
		$this->close();
		return $result;
	}
	/**
	 * join another table / schema
	 * this method is called before calling grabFromJoint() method.
	 * @param $schema the target table schema you want to join
	 * @param $fk field of the table that act as foreign key (FK) to target table's Primary Key.
	 * @param $obj the target table's instance
	 * @return array
	 */
	function join($schema,$fk,$obj){
		$this->joinTables[] = array($schema,$fk,$obj);
	}
	/**
	 * Retrieve all rows after all the joined table are set using join() method.
	 * @param int start row
	 * @param int total row to retrieve
	 * @return array
	 */
	function grabFromJoint($start,$total){
		//$this->joinStmt = "";
		//$this->join_schemas[] = $this->schema;
		$schema = array();
		$fields = array();
		$foo = $this->getJoinedSchemas($schema,"",$fields);
		$schema_used = array();
		$fields = $foo[2];
		$stmtFields = "";
		$n=0;
	//	print_r($fields);
		foreach($fields as $name=>$val){
			for($j=0;$j<sizeof($val);$j++){
				if($n>0){
					$stmtFields.=",";
				}
				$stmtFields.=$name.".".$val[$j]['Field']." as ".$name."_".$val[$j]['Field'];
				$n++;
			}
		}
		$stmt = "SELECT SQL_CALC_FOUND_ROWS ".$stmtFields." FROM ".$this->schema;
		for($i=0;$i<sizeof($foo[0]);$i++){
			if(!$schema_used[$foo[0][$i]]){
				$stmt.=",".$foo[0][$i];
				$schema_used[$foo[0][$i]] = true;
			}
		}
		$stmt.=" WHERE ".$foo[1]." ORDER BY ".$this->schema.".id DESC LIMIT ".$start.",".$total;
		$this->open();
		$result = $this->fetch($stmt,1);
		//print $stmt;
		
		$rs = $this->fetch("SELECT FOUND_ROWS() as total");
		$this->FoundRows = $rs['total'];
		$this->close();
		return $result;
	}
	/**
	 * Retrieve all rows after all the joined table are set using join() method with some conditions defined.
	  * @param array $condition array of conditions to be met.
	 * @param int start row
	 * @param int total row to retrieve
	 * @return array
	 */
	function grabFromJointWithCondition($condition,$start,$total){
		//$this->joinStmt = "";
		//$this->join_schemas[] = $this->schema;
		
		$strCond = "";
		for($i=0;$i<sizeof($condition);$i++){
			if($i>0){
				$strCond.=" AND ";
			}
			$strCond.= $condition[$i][0].".".$condition[$i][1]." ".$condition[$i][2]." '".$condition[$i][3]."'";
		}
		

		$schema = array();
		$fields = array();
		$foo = $this->getJoinedSchemas($schema,"",$fields);
		$schema_used = array();
		$fields = $foo[2];
		$stmtFields = "";
		$n=0;
	//	print_r($fields);
		foreach($fields as $name=>$val){
			for($j=0;$j<sizeof($val);$j++){
				if($n>0){
					$stmtFields.=",";
				}
				$stmtFields.=$name.".".$val[$j]['Field']." as ".$name."_".$val[$j]['Field'];
				$n++;
			}
		}
		$stmt = "SELECT SQL_CALC_FOUND_ROWS ".$stmtFields." FROM ".$this->schema;
		for($i=0;$i<sizeof($foo[0]);$i++){
			if(!$schema_used[$foo[0][$i]]){
				$stmt.=",".$foo[0][$i];
				$schema_used[$foo[0][$i]] = true;
			}
		}
		$stmt.=" WHERE ".$foo[1]." AND ".$strCond." ORDER BY ".$this->schema.".id DESC LIMIT ".$start.",".$total;
		$this->open();
		$result = $this->fetch($stmt,1);
		//print $stmt;
		
		$rs = $this->fetch("SELECT FOUND_ROWS() as total");
		$this->FoundRows = $rs['total'];
		$this->close();
		return $result;
	}
	/**
	 * Internal method
	 */
	function getJoinedSchemas($schemas,$joinStmt,$fields){
		$fields[$this->schema] = array_merge(array(array("Field"=>"id")),$this->structs);
		for($i=0;$i<sizeof($this->joinTables);$i++){
			if(sizeof($schemas)>0){
				$joinStmt.=" AND ";
			}
			$joinStmt .= $this->schema.".".$this->joinTables[$i][1]." = ".$this->joinTables[$i][0].".id";
			$schemas[] = $this->joinTables[$i][0];
			$foo = $this->joinTables[$i][2]->getJoinedSchemas($schemas,$joinStmt,$fields);
			$schemas = $foo[0];
			$joinStmt = $foo[1];
			$fields = $foo[2];
		}
		return array($schemas,$joinStmt,$fields);
	}
	/*
	function getJoinedSchemas($parent){
		for($i=0;$i<sizeof($this->joinTables);$i++){
			if(sizeof($this->join_schemas)>0){
				$this->joinStmt.=" AND ";
			}
			$this->joinStmt .= $parent.".".$this->joinTables[$i][1]." = ".$this->joinTables[$i][0].".id";
			$this->join_schemas[] = $this->joinTables[$i][0];
			//$this->getJoinedSchemas($this->joinTables[$i][0]);
		}
	}
	*/
	
	/**
	 * Get found rows from the latest retrieve
	 * @return int
	 */
	function getFoundRows(){
		return $this->FoundRows;
	}
	
	/**
	 * Update data from a table
	 * @param int $id Primary Key
	 * @param array $params array of parameters
	 * @return boolean
	 */
	function update($id,$params){
		$this->open();
		//INSERT INTO schema(field1,field2,field3) VALUES('param1','param2','param3')
		$stmt = "UPDATE ".$this->schema." SET ";
		for($i=0;$i<sizeof($this->structs);$i++){
			if($params[$i]!=$this->SQL_SKIP){
				if($i!=0){
					$stmt.=",";	
				}
				if($params[$i]==$this->SQL_NOW){
					$stmt.=$this->structs[$i]['Field']."=NOW()";
				}else{
					$stmt.=$this->structs[$i]['Field']."='".$params[$i]."'";
				}
			}
		}
		$stmt.=" WHERE id='".$id."'";
		
		//execute the statement

		$rs = $this->query($stmt);
		
		if(!$rs){
			$this->error("Error in Query Statement -> ".mysql_error());	
		}
		//print $stmt;
		$this->last_query_stmt[] = $stmt;
		$this->close();
		return $rs;
	}
	/**
	 * Delete a data from table
	 * @param int $id Primary Key
	 * @return boolean
	 */
	function delete($id){
		$this->open();
		$rs = $this->query("DELETE FROM ".$this->schema." WHERE id='".$id."'");
		$this->close();
		return $rs;
	}
	/**
	 * get all list of queries which has been called so far...
	 * @return array
	 */
	function peek(){
		return $this->last_query_stmt;
	}
	/**
	 * reset the last query statement array
	 * 
	 */
	function reset(){
		while(sizeof($this->last_query_stmt)>0){
			array_pop($this->last_query_stmt);	
		}
		$this->last_query_stmt = NULL;	
	}
	
	/**
	 * Internal function
	 */
	function error($msg){
		$this->err_msg = "QueryEngine<br/>----------------------------------------<br/>Error : ".$msg."<br/>";	
	}
	
	/**
	 * get latest occured error message
	 * @return string
	 */
	function getErrorMessage(){
		return $this->err_msg;	
	}
}
?>