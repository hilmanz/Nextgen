<?php

class uDemoHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshema = "marlborohunt";	
		
		$this->startdate = $this->apps->_g('startdate');
		$this->enddate = $this->apps->_g('enddate');	
		if($this->enddate=='') $this->enddate = date('Y-m-d');		
		if($this->startdate=='') $this->startdate = date('Y-m-d' ,  strtotime( '-7 day' ,strtotime($this->enddate)) );
	}	

	function participantByGender(){
		
		$sql = "SELECT count( * ) num, DATE( register_date ) datetime, sex
				FROM social_member WHERE n_status = 1 AND sex <> '' AND sex IS NOT NULL AND sex <> 'UNKNOWN' GROUP BY sex ORDER BY datetime DESC";
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		return $qData;
	}
	
	function paticipantByAge(){
		
		$sql = "SELECT count( * ) num, DATE_FORMAT( register_date, '%Y-%m-%d' ) datetime, sex, YEAR(
				CURRENT_TIMESTAMP ) - YEAR( birthday ) - ( RIGHT(
				CURRENT_TIMESTAMP , 5 ) < RIGHT( birthday, 5 ) ) AS age
				FROM social_member 
				WHERE register_date <> '0000-00-00' AND register_date IS NOT NULL 
				GROUP BY age
				HAVING age <> '2013' AND age >=0
				ORDER BY age ASC";
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		return $qData;
	
	}
	
	function participantByCity(){
		
		$sql = "SELECT count( * ) num, DATE_FORMAT( register_date, '%Y-%m-%d' ) datetime, mc.city
				FROM social_member sm
				LEFT JOIN marlborohunt_city_reference mc ON sm.city = mc.id
				WHERE mc.city <> '' AND mc.city IS NOT NULL AND mc.city not like '%not specified%'
				GROUP BY mc.city
				ORDER BY num DESC , datetime DESC LIMIT 10";
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		return $qData;
	
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
			
		$sql = "SELECT count(*) total, DATE(date_time) dateformatonly  FROM tbl_activity_log WHERE action_id IN ({$activityId}) ORDER BY dateformatonly GROUP BY dateformatonly LIMIT {$start},{$limit}";

		$getChartDataOf[$searchData] = $this->apps->fetch($sql);
		
		//pr($getChartDataOf);
		exit;

	}

	function getactivitytype($dataactivity=null,$id=false){
			if($dataactivity==null)return false;
			if($id) $qAppender = " id IN ({$dataactivity}) ";
			else $qAppender = " activityName IN ({$dataactivity})  ";
			$theactivity = false;
			/* get activity  id */	
			$sql = " SELECT * FROM tbl_activity_actions WHERE {$qAppender} ";

			$qData = $this->apps->open(1);
				
			if($qData) {
				foreach($qData as $val){
					$theactivity['id'][$val['id']]=$val['id'];
					$theactivity['name'][$val['id']]=$val['activityName'];
					
				}
			}
			return $theactivity;
		}

}

?>