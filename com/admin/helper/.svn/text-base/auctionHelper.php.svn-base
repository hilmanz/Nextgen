<?php

class auctionHelper{
	
	
	function __construct($apps){
	global $logger,$CONFIG;
	$this->logger = $logger;
	$this->apps = $apps;
	if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
	}
	else $this->uid = 0;
	$this->dbshema = "beat";
	/*0; unread, 1: deleted, 2:read and send */
	}
	
	function auctionList($start=null,$limit=10)
	{
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
		
		$search = strip_tags($this->apps->_p('search'));
		$notiftype = intval($this->apps->_p('notiftype'));
		// $publishedtype = intval($this->apps->_p('publishedtype'));
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		
		//RUN FILTER
		$filter = "";
		$filter = $search=="Search..." ? "" : "AND (title LIKE '%{$search}%' OR content LIKE '%{$search}%')";
		// $filter .= $notiftype!=0 ? " AND notiftype = {$notiftype}" : " AND notiftype = 3";
		// $filter .= $publishedtype ? "AND n_status = {$publishedtype}" : " AND n_status != 3";
		$filter .= $startdate ? " AND created_date >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND created_date < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT count(*) total FROM {$CONFIG['DATABASE_WEB']}.notifications  WHERE 1 {$filter}";
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT * FROM {$CONFIG['DATABASE_WEB']}.auctions 
			WHERE n_status = 1 {$filter} ORDER BY created_date DESC,id DESC LIMIT {$start},{$limit}
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
	
	function getHapus($cid=NULL){
		global $CONFIG;
		if($cid){
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.auctions SET n_status = 3 WHERE id = {$cid} ";
			$qdata  =  $this->apps->query($sql);
			if ($qdata) $data = true;
			else $data = false;
		}else {
			$data = false;	
		}
		return $data;		
	}
	
	function insertauctiondata()
	{
		global $CONFIG;
		// pr($_POST);exit;
		$title = $this->apps->_p('title'); 
		$content = $this->apps->_p('content');   
		$created_date = date('Y-m-d H:i:s');   
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		// $prizetype = $this->apps->_p('prizetype');
		// $points = $this->apps->_p('points');
		$minbid = $this->apps->_p('minbid');
		$n_status = $this->apps->_p('n_status');
		
		$submit = $this->apps->_p('submit');
		
		if(isset($submit)){
			$sql = "INSERT INTO {$CONFIG['DATABASE_WEB']}.auctions (`title`, `content`, `created_date`, `start_date`, `end_date`, `minimalBid`, `n_status` ) 
					VALUES ('{$title}', '{$content}', '{$created_date}', '{$startdate}', '{$enddate}',  '{$minbid}', '{$n_status}' )";
			// pr($sql);exit;
			$res = $this->apps->query($sql);
			return $this->apps->getLastInsertId();
		}
		
		return false;
	}
	
	function auctionimageupdate($insertauctiondata=false, $images=false)
	{
		global $CONFIG;
		//update
		// pr($images); exit;
		if (!$insertauctiondata) return false;
		
		$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.auctions SET img = '{$images}' WHERE id = {$insertauctiondata}";
		$res = $this->apps->query($sql);
		// pr($sql);exit;
	}
	
	function selectupdatedata($id=NULL)
	{
		
		global $CONFIG;
		$sql = "SELECT * FROM {$CONFIG['DATABASE_WEB']}.auctions WHERE id = {$id} LIMIT 1";
		// pr($sql);
		// fetch()
		$qData = $this->apps->fetch($sql);
		return $qData;
	
	}
	
	function updatethedata($id=NULL, $images=false){
		global $CONFIG;
		// pr($_POST);
		$title = $this->apps->_p('title'); 
		$content = $this->apps->_p('content');   
		$created_date = date('Y-m-d H:i:s');   
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		// $prizetype = $this->apps->_p('prizetype');
		// $points = $this->apps->_p('points');
		$minbid = $this->apps->_p('minbid');
		$n_status = $this->apps->_p('n_status'); 		 
		
		$submit = $this->apps->_p('submit');
		if(isset($submit)){
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.auctions SET `title` = '{$title}',  
																`content` = '{$content}', 
																`created_date` = '{$created_date}', 
																`start_date` = '{$startdate}', 
																`end_date` = '{$enddate}',
																`minimalBid` = '{$minbid}',
																`n_status` = '{$n_status}'
																WHERE id = '{$id}' "; 
			$res = $this->apps->query($sql);
			// pr($sql);exit;
			if($images!=false) 	$this->apps->query("UPDATE {$CONFIG['DATABASE_WEB']}.auctions SET img='{$images}' WHERE id={$id}");
			return $res;
		}
		
		return false;
	}
	
	function auctionwinnerlist($start=null,$limit=10)
	{
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
		$filter = $search=="Search..." ? "" : "AND (sm.username LIKE '%{$search}%' OR a.title LIKE '%{$search}%')";
		// $filter .= $notiftype!=0 ? " AND notiftype = {$notiftype}" : " AND notiftype = 3";
		// $filter .= $publishedtype ? "AND n_status = {$publishedtype}" : " AND n_status != 3";
		$filter .= $startdate ? " AND ma.winner_date >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND ma.winner_date < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT count(*) total FROM {$CONFIG['DATABASE_WEB']}.my_auctions ma
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.auctions a ON ma.auctionid = a.id
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.social_member sm ON ma.userid = sm.id
				WHERE 1 {$filter}";
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "SELECT ma.id, sm.username, a.title, ma.winner_date, a.currentBid
				FROM {$CONFIG['DATABASE_WEB']}.my_auctions ma
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.auctions a ON ma.auctionid = a.id
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.social_member sm ON ma.userid = sm.id
				WHERE 1 {$filter}
				ORDER BY ma.winner_date DESC LIMIT {$start}, {$limit}";
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
