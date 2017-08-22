<?php 

class aspirationHelper {
	
	function __construct($apps){
		global $logger,$CONFIG;
		
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
		$this->dbshema= 'tbl';
	}
	
	function userList($start=null,$limit=10){
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
		
		$search = strip_tags($this->apps->_p('search'));
		// $notiftype = intval($this->apps->_p('notiftype'));
		// $publishedtype = intval($this->apps->_p('publishedtype'));
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		
		//RUN FILTER
		$filter = "";
		$filter = $search=="Search..." ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.username LIKE '%{$search}%' OR ap.aspiration LIKE '%{$search}%')";
		// $filter .= $notiftype!=0 ? " AND notiftype = {$notiftype}" : " AND notiftype = 3";
		// $filter .= $publishedtype ? "AND n_status = {$publishedtype}" : " AND n_status != 3";
		$filter .= $startdate ? " AND ap.submit_date >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND ap.submit_date < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT count(*) total
				FROM {$CONFIG['DATABASE_WEB']}.social_member sm
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.city_references cr ON sm.city = cr.id WHERE 1 {$filter}";
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "SELECT ap.*, sm.name, sm.last_name
				FROM {$CONFIG['DATABASE_WEB']}.my_aspiration ap
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.social_member sm ON ap.userid = sm.id WHERE 1 {$filter} LIMIT {$start},{$limit}
		";
		// pr($sql);
		$rqData = $this->apps->fetch($sql,1);
		if($rqData) {
			$no = $start+1;
			foreach($rqData as $key => $val){
				$val['no'] = $no++;
				$rqData[$key] = $val;
			}
			
			if($rqData) $qData=	$rqData;
			else $qData = false;
		} else $qData = false;
		
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		return $result;
	} 
	
	function ajax(){
		global $CONFIG;
		
		$n_status = $this->apps->_p('n_status'); 
		$id = $this->apps->_p('id');
		
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.my_aspiration SET n_status = {$n_status} WHERE id = {$id}";
			// pr($sql);
			$qData = $this->apps->query($sql);
			if(!$qData) return false;
			
	}
	
}
?>