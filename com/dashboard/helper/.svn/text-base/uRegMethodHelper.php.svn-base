<?php

class uRegMethodHelper {

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
		
		$this->webActivityHelper = $this->apps->useHelper('webActivityHelper');
	}	
	
	function onlineReg(){
		
		$sql = "SELECT COUNT( * ) num, DATE(register_date) dd
				FROM social_member 
				WHERE usertype=0 
				AND DATE(register_date) >= '{$this->startdate}'
				AND DATE(register_date) <= '{$this->enddate}' 
				GROUP BY dd ORDER BY dd ASC ";
				// pr($sql);
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		if($qData){
		$newdata = $this->webActivityHelper->fixeddate($qData,'dd','num');
		if($newdata) $data = $newdata ;
		else $data = $qData ;
		}else {
				$arrdata = false;
				$arrdata[0]['dd'] = date('Y-m-d');
				$arrdata[0]['num'] = 0;			 
				$newdata = $this->webActivityHelper->fixeddate($arrdata,'dd','num');
				// pr($newdata);
				if($newdata)$data = $newdata ;
		}
		if($data)	return $data;
		else return false;
	
	}
	
	function offlineReg(){
		
		$sql = "SELECT COUNT( * ) num, DATE(register_date) dd
				FROM social_member 
				WHERE usertype=1
				AND DATE(register_date) >= '{$this->startdate}'
				AND DATE(register_date) <= '{$this->enddate}' 
				GROUP BY dd ORDER BY dd ASC ";
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		if($qData){
		$newdata = $this->webActivityHelper->fixeddate($qData,'dd','num');
		if($newdata) $data = $newdata ;
		else $data = $qData ;
		}else {
				$arrdata = false;
				$arrdata[0]['dd'] = date('Y-m-d');
				$arrdata[0]['num'] = 0;			 
				$newdata = $this->webActivityHelper->fixeddate($arrdata,'dd','num');
				// pr($newdata);
				if($newdata)$data = $newdata ;
		}
		if($data)	return $data;
		else return false;
		
	}

	function verifiedReg(){
		
		$sql = "SELECT count( * ) num, DATE_FORMAT( register_date, '%Y-%m-%d' ) datetime
				FROM social_member WHERE  n_status = 1 
				AND DATE(register_date) >= '{$this->startdate}'
				AND DATE(register_date) <= '{$this->enddate}' 
				GROUP BY datetime ORDER BY datetime ASC ";
				// pr($sql);
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		if($qData){
		$newdata = $this->webActivityHelper->fixeddate($qData,'datetime','num');
		if($newdata) $data = $newdata ;
		else $data = $qData ;
		}else {
				$arrdata = false;
				$arrdata[0]['datetime'] = date('Y-m-d');
				$arrdata[0]['num'] = 0;			 
				$newdata = $this->webActivityHelper->fixeddate($arrdata,'datetime','num');
				// pr($newdata);
				if($newdata)$data = $newdata ;
		}
		if($data)	return $data;
		else return false;
		
	}
	
	function unverifiedReg(){
		
		$sql = "SELECT count( * ) num, DATE_FORMAT( register_date, '%Y-%m-%d' ) datetime
				FROM social_member WHERE n_status <> 1
				AND DATE(register_date) >= '{$this->startdate}'
				AND DATE(register_date) <= '{$this->enddate}' 
				GROUP BY datetime ORDER BY datetime ASC ";
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		if($qData){
		$newdata = $this->webActivityHelper->fixeddate($qData,'datetime','num');
		if($newdata) $data = $newdata ;
		else $data = $qData ;
		}else {
				$arrdata = false;
				$arrdata[0]['datetime'] = date('Y-m-d');
				$arrdata[0]['num'] = 0;			 
				$newdata = $this->webActivityHelper->fixeddate($arrdata,'datetime','num');
				// pr($newdata);
				if($newdata)$data = $newdata ;
		}
		if($data)	return $data;
		else return false;
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