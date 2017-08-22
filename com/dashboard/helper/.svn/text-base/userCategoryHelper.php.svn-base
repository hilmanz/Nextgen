<?php

class userCategoryHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshema = "athreesix";	
	}
	
	
	function allUserRegistration(){
		$sql = "SELECT count(*) num , DATE_FORMAT(register_date,'%Y-%m-%d') datetime 
				FROM social_member GROUP BY datetime ORDER BY datetime DESC "; 
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$data = false;
		foreach($qData as $val){	
			$data['data'][$val['datetime']] = $val['num'];
			$data['date'][$val['datetime']] = $val['datetime'];
		}	
		return $data;
		
	}
	
	function userUnverified(){
		$sql = "SELECT count(*) num , DATE_FORMAT(register_date,'%Y-%m-%d') datetime 
				FROM social_member AND n_status=0 GROUP BY  datetime ORDER BY datetime DESC "; 
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$data = false;
		foreach($qData as $val){	
				$data['data'][$val['datetime']] = $val['num'];
			$data['date'][$val['datetime']] = $val['datetime'];
		}		
		return $data;
		
	}
	
	function loginUser($type=7){
		$qTypeUser = "";
		$qActiveUser = "";
		if($type!=0) {
			$startdate =  $this->apps->_g('startdate');
			if($startdate=='') $startdate = " DATE_SUB(NOW() , INTERVAL {$type} DAY )";
			$qTypeUser = " AND date_time BETWEEN {$startdate} AND NOW() ";
			$qActiveUser = " HAVING count(*) > 1 ";
		}
		
		$sql = "SELECT count(*) num, DATE_FORMAT(date_time,'%Y-%m-%d') datetime 
				FROM `tbl_activity_log` WHERE action_id = 1 {$qTypeUser} 
				GROUP BY datetime {$qActiveUser} ORDER BY datetime DESC  "; 
		// $this->apps->open(1);
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$data = false;
		foreach($qData as $val){	
			$data['data'][$val['datetime']] = $val['num'];
			$data['date'][$val['datetime']] = $val['datetime'];
		}		
		return $data;
		
	}
	
	

	function getChartDataOf($searchData=null){
		
		if($searchData==null) return false;
		
		if(is_array($searchData)) {
			foreach($searchData as $val){
				$nuArr[] = "'{$val}'";
			}
			if($nuArr)	$searchData = implode(',',$nuArr);
			else return false;
		}
		
		$theactivity = "{$searchData}";
		
		/* get activity ID */
		$actionnamedata = $this->getactivitytype($theactivity);

		if($actionnamedata) {
			
			$activityID = implode(',',$actionnamedata['id']);
		}else $activityID = false;
			
		$sql = "SELECT count(*) total, DATE(date_time) dateformatonly  
				FROM tbl_activity_log WHERE action_id IN ({$activityId}) 
				ORDER BY dateformatonly GROUP BY dateformatonly LIMIT {$start},{$limit}";

		$getChartDataOf[$searchData] = $this->apps->fetch($sql);
		
		pr($getChartDataOf);
		exit;

	}

	function getactivitytype($dataactivity=null,$id=false){
			if($dataactivity==null)return false;
			if($id) $qAppender = " id IN ({$dataactivity}) ";
			else $qAppender = " activityName IN ({$dataactivity})  ";
			$theactivity = false;
			/* get activity  id */	
			$sql = " SELECT * FROM tbl_activity_actions WHERE {$qAppender} ";

			$qData = $this->apps->fetch($sql,1);
				
			if($qData) {
				foreach($qData as $val){
					$theactivity['id'][$val['id']]=$val['id'];
					$theactivity['name'][$val['id']]=$val['activityName'];
					
				}
			}
			return $theactivity;
		}


		function userActivity(){
			
			$data['2013-01-01'] = array ('activity'=>'login' , 'total'=>100);
			$data['2013-01-02'] = array ('activity'=>'login' , 'total'=>42);
			$data['2013-01-03'] = array ('activity'=>'login' , 'total'=>10);
			$data['2013-01-04'] = array ('activity'=>'login' , 'total'=>80);
			$data['2013-01-05'] = array ('activity'=>'login' , 'total'=>20);
			$data['2013-01-06'] = array ('activity'=>'login' , 'total'=>60);
			$data['2013-01-07'] = array ('activity'=>'login' , 'total'=>34);
			$data['2013-01-08'] = array ('activity'=>'login' , 'total'=>68);
			$data['2013-01-09'] = array ('activity'=>'login' , 'total'=>12);
			$data['2013-01-10'] = array ('activity'=>'login' , 'total'=>50);

			
			return $data;
		}
		
		
	function dataDummy(){
		$data['visitors'] = 1000;
		$data['unique'] = 3000;
		$data['pageview'] = 1500;
	
		return $data;
		exit;
	}
	
	

}

?>