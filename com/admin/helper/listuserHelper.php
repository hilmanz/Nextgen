<?php 

class listuserHelper {
	
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
		$filter = $search=="Search..." ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%')";
		// $filter .= $notiftype!=0 ? " AND notiftype = {$notiftype}" : " AND notiftype = 3";
		// $filter .= $publishedtype ? "AND n_status = {$publishedtype}" : " AND n_status != 3";
		$filter .= $startdate ? " AND sm.register_date >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND sm.register_date < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT COUNT(1) total FROM social_member sm 
				LEFT JOIN beat_city_reference ct ON sm.city = ct.id 
				WHERE sm.n_status = 1 {$filter} ORDER BY sm.register_date DESC";
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "SELECT sm.id, sm.name, sm.last_name, sm.email, sm.register_date, sm.img, ct.city FROM social_member sm 
				LEFT JOIN beat_city_reference ct ON sm.city = ct.id 
				WHERE sm.n_status = 1 {$filter} ORDER BY sm.register_date DESC LIMIT {$start},{$limit}
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
		
		$photo_moderation = $this->apps->_p('photo_moderation'); 
		$id = $this->apps->_p('id');
		
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.social_member SET photo_moderation = {$photo_moderation} WHERE id = {$id}";
			// pr($sql);
			$qData = $this->apps->query($sql);
			if(!$qData) return false;
			
	}
	
	function registrantList($start=null,$limit=10){
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
		
		$search = strip_tags($this->apps->_p('search'));
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		
		//RUN FILTER
		$filter = "";
		$filter = $search=="Search..." ? "" : "AND (name LIKE '%{$search}%' OR last_name LIKE '%{$search}%' OR email LIKE '%{$search}%')";
		$filter .= $startdate ? " AND register_date >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND register_date < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT COUNT(1) total FROM my_entourage 
				WHERE 1 {$filter} ORDER BY sm.register_date DESC";
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "SELECT * FROM my_entourage WHERE 1 {$filter} ORDER BY register_date DESC LIMIT {$start},{$limit}
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
	
	
}
?>