<?php 

class notificationHelper {
	
	function __construct($apps){
		global $logger,$CONFIG;
		
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
		$this->dbshema= 'tbl';
	}
	
	function getNotification($start=null,$limit=10){
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
		$filter = $search=="Search..." ? "" : "AND (title LIKE '%{$search}%' OR brief LIKE '%{$search}%' OR content LIKE '%{$search}%')";
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
			SELECT * FROM {$CONFIG['DATABASE_WEB']}.notifications 
			WHERE 1 {$filter} ORDER BY created_date DESC,id DESC LIMIT {$start},{$limit}
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
	
	function addNotification(){
		global $CONFIG;
		// pr($_POST);exit;
		$title = strip_tags($this->apps->_p('title'));
		$brief = strip_tags($this->apps->_p('brief'));
		$content = strip_tags($this->apps->_p('content'));
		$status = intval($this->apps->_p('n_status'));
		$notiftype = $this->apps->_p('notiftype');

		if($this->apps->_p('input')==1){
			$sql = "INSERT INTO {$CONFIG['DATABASE_WEB']}.notifications (title,brief,content,n_status,created_date,posted_date,notiftype) values ('{$title}',\"{$brief}\",\"{$content}\",'{$status}',NOW(),NOW(),'{$notiftype}')";
			// pr($sql);exit;
			$qData = $this->apps->query($sql);
			if ($qData) $qData = true;
			else $qData = false;
		} else return false;
		
		$result['result'] = $qData;
		return $result;
	}
	
	function editNotification(){
		global $CONFIG;
		
		$id = intval($this->apps->_request('id'));
		$title = strip_tags($this->apps->_p('title'));
		$brief = strip_tags($this->apps->_p('brief'));
		$content = strip_tags($this->apps->_p('content'));
		$status = intval($this->apps->_p('n_status'));
		$notiftype = $this->apps->_p('notiftype');
		
		if($id!=0) $qid = " AND id = {$id} ";
		else $qid = "";
		
		$sql = "
			SELECT * FROM {$CONFIG['DATABASE_WEB']}.notifications WHERE 1 {$qid} ORDER BY created_date DESC,id DESC
		";
		
		$rqData = $this->apps->fetch($sql);
		
		if($rqData){
			foreach($rqData as $key => $val){
				$rqData[$key] = $val;
			}
			
			if($rqData) $qData=	$rqData;
			else $qData = false;
		} else $qData = false;
		
		if(!$qData) return false;

		if($qData && $this->apps->_p('update')==1){
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.notifications SET title = '{$title}',brief = \"{$brief}\",content = \"{$content}\",n_status = '{$status}', notiftype='{$notiftype}' WHERE id = {$id}";
			$qData = $this->apps->query($sql);
			if ($qData) $qData = true;
			else $qData = false;
		}
		
		$result['result'] = $qData;
		return $result;
	}
	
	function getHapus(){
		global $CONFIG;
		
		$cid = intval($this->apps->_request('id'));
		if($cid){
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.notifications SET n_status = 3 WHERE id = {$cid} ";
			$qdata  =  $this->apps->query($sql);
			if ($qdata) $data = true;
			else $data = false;
		}else {
			$data = false;	
		}
		return $data;		
	}
}
?>