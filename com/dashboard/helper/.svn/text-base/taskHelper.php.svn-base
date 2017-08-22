<?php

class taskHelper {

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

	function eachTaskCompleted(){
		
		$sql = "
		SELECT count( * ) num, DATE(taskdate) dd 
		FROM my_task 
		WHERE  userid NOT IN ({$this->apps->getadminemail()})
		GROUP BY dd ORDER BY dd DESC LIMIT 7";
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		return $qData;
	}
	
	function bhtaskComplete($start=0,$limit=10){
		
		$start = intval($this->apps->_g('st'));
	
		$sql = "SELECT COUNT( * ) num, bhname, bhid
				FROM tbl_report_letter_elusive
				GROUP BY bhid LIMIT {$start},{$limit}";
		$qData = $this->apps->fetch($sql,1);
		
		
		$sqlTotal = "
					SElECT COUNT(*) total FROM(
					SELECT COUNT( * ) num, bhname
					FROM tbl_report_letter_elusive
					GROUP BY bhid)a 
					";
		$qDataTotal = $this->apps->fetch($sqlTotal);
		
		return array('rec'=>$qData, 'total'=>$qDataTotal['total']);
	
	}
	
	function getBHdetail(){
		
		$id = strip_tags($this->apps->_p('bhid'));
		$sql = "SELECT * FROM tbl_report_letter_elusive WHERE bhid = '{$id}' ";
		
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		// pr($qData);
		
		if ($qData) return $qData;
		
		return false;
		// exit;
	
	}
	
	function  bhInvolvedOffline(){
	
		$sql = "SELECT COUNT(*)num, inv.histories,sm.email,inv.datetimes,inv.codeid,inv.codepublicityid
				FROM tbl_code_inventory inv
				LEFT JOIN social_member sm ON sm.id = inv.userid
				WHERE  inv.histories like '%get from event%' GROUP BY inv.histories ORDER BY inv.datetimes DESC";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		return $qData;
		
	}
	
	function getbhInvolvedOffline()
	{
		$id = strip_tags($this->apps->_p('histories'));
		
		$sql = "SELECT COUNT(*)num, inv.histories,sm.email,inv.datetimes,inv.codeid,inv.codepublicityid
				FROM tbl_code_inventory inv
				LEFT JOIN social_member sm ON sm.id = inv.userid
				WHERE  inv.histories like '%{$id}%' GROUP BY inv.histories ORDER BY inv.datetimes DESC";
		
		// pr($sql);
		$qData = $this->apps->fetch($sql);
		
		if ($qData) return $qData;
		
		return false;
	}
	
	function bhTotal(){
	
		$sql = "SELECT COUNT( * ) num
				FROM tbl_code_inventory
				WHERE histories LIKE '%get from event%'
				ORDER BY datetimes DESC ";
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		return $qData;
		
	}

	
	function mostPopularTask(){
		
		$sql = "SELECT count( * ) num, DATE(mt.taskdate) dd, mnc.title
				FROM my_task mt
				LEFT JOIN marlborohunt_news_content mnc ON mt.taskid = mnc.id
				WHERE mnc.title IS NOT NULL AND mnc.title <> ''
				AND mt.userid NOT IN ({$this->apps->getadminemail()})
				GROUP BY mnc.title
				ORDER BY num DESC";
		// pr($sql);
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		// pr($qData);
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