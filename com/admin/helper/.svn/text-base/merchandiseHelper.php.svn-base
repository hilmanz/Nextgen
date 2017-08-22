<?php 

class merchandiseHelper {
	
	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
		$this->dbshema= 'tbl';
	}
	
	function merchandiseList($start=null,$limit=10)
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
		$filter = $search=="Search..." ? "" : "AND (name LIKE '%{$search}%' OR detail LIKE '%{$search}%')";
		// $filter .= $notiftype!=0 ? " AND notiftype = {$notiftype}" : " AND notiftype = 3";
		// $filter .= $publishedtype ? "AND n_status = {$publishedtype}" : " AND n_status != 3";
		$filter .= $startdate ? " AND postdate >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND postdate < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT count(*) total FROM {$CONFIG['DATABASE_WEB']}.collectables  WHERE 1 {$filter}";
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT * FROM {$CONFIG['DATABASE_WEB']}.collectables 
			WHERE n_status = 1 {$filter} ORDER BY postdate DESC,id DESC LIMIT {$start},{$limit}
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
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.collectables SET n_status = 3 WHERE id = {$cid} ";
			$qdata  =  $this->apps->query($sql);
			if ($qdata) $data = true;
			else $data = false;
		}else {
			$data = false;	
		}
		return $data;		
	}
	
	function insertnewdata(){
		
		global $CONFIG;
		// pr($_POST);exit;
		$name = $this->apps->_p('name'); 
		$detail = $this->apps->_p('detail');   
		$stock = $this->apps->_p('stock');   
		$point = $this->apps->_p('point');       
		$published_date = $this->apps->_p('published_date');  
		$n_status = $this->apps->_p('n_status'); 
				
		$submit = $this->apps->_p('submit');
		// var_dump($submit);
		if($submit){
			$sql = "INSERT INTO {$CONFIG['DATABASE_WEB']}.collectables (`name`, `detail`, `stock`, `point`, `postdate`, `n_status` ) 
					VALUES ('{$name}', '{$detail}', '{$stock}', '{$point}', '{$published_date}', '{$n_status}' )";
			// pr($sql);exit;
			$res = $this->apps->query($sql);
			return $this->apps->getLastInsertId();
		}
		
		return false;
	}
	
	function newsupdate($insertnewdata=false, $images=false)
	{
		global $CONFIG;
		//update
		// pr($images); exit;
		if (!$insertnewdata) return false;
		
		$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.collectables SET image = '{$images}' WHERE id = {$insertnewdata}";
		$res = $this->apps->query($sql);
		// pr($sql);exit;
	}
	
	function selectupdatedata($id=NULL)
	{
		
		global $CONFIG;
		$sql = "SELECT * FROM {$CONFIG['DATABASE_WEB']}.collectables WHERE id = {$id} LIMIT 1";
		// pr($sql);
		// fetch()
		$qData = $this->apps->fetch($sql);
		return $qData;
	
	}
	
	function updatethedata($id=NULL, $images=false){
		global $CONFIG;
		// pr($_POST);
		// pr($_FILES);
		$name = $this->apps->_p('name'); 
		$detail = $this->apps->_p('detail');   
		$stock = $this->apps->_p('stock');   
		$point = $this->apps->_p('point');       
		$published_date = $this->apps->_p('published_date');  
		$n_status = $this->apps->_p('n_status'); 
				  
		$submit = $this->apps->_p('submit');
		if(isset($submit)){
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.collectables SET `name` = '{$name}',  
																`detail` = '{$detail}', 
																`stock` = '{$stock}', 
																`point` = '{$point}',  
																`postdate` = '{$published_date}',  
																`n_status` = '{$n_status}'
																WHERE id = '{$id}' "; 
			$res = $this->apps->query($sql);
			
			if($images!=false) 	$this->apps->query("UPDATE {$CONFIG['DATABASE_WEB']}.collectables SET image='{$images}' WHERE id={$id}");
			return $res;
		}
		
		return false;
	}
	
	function redeemlist($start=null, $limit=10){
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
		$filter = $search=="Search..." ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%')";
		// $filter .= $notiftype!=0 ? " AND notiftype = {$notiftype}" : " AND notiftype = 3";
		// $filter .= $publishedtype ? "AND n_status = {$publishedtype}" : " AND n_status != 3";
		$filter .= $startdate ? " AND mc.redeemdate >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND mc.redeemdate < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT count(*) total FROM {$CONFIG['DATABASE_WEB']}.collectables  WHERE 1 {$filter}";
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "SELECT mc.id, sm.name, sm.last_name, mc.email, mc.address, c.name merchname, mc.redeemdate, mc.phonenumber, mc.n_status
				FROM {$CONFIG['DATABASE_WEB']}.my_collectables mc
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.social_member sm ON mc.userid = sm.id
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.collectables c ON mc.merchandiseid = c.id
				WHERE 1 {$filter} 
				ORDER BY mc.redeemdate
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
		
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.my_collectables SET n_status = {$n_status} WHERE id = {$id}";
			// pr($sql);
			$qData = $this->apps->query($sql);
			if(!$qData) return false;
			
	}	
	
	function ajaxcheckdate(){
		global $CONFIG;
		
		$n_status = $this->apps->_p('n_status'); 
		$id = $this->apps->_p('id');
		
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.my_collectables SET n_status = {$n_status} WHERE id = {$id}";
			// pr($sql);
			$qData = $this->apps->query($sql);
			if(!$qData) return false;
			
	}
	
	
}
?>
