<?php
include_once $ENGINE_PATH."System/System.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
class SystemApp extends SQLData{
	var $strHTML;
	var $View;
	var $system;
	function SystemApp($req){
		
		parent::SQLData();
		$this->autoconnect = false;
		$this->View = new BasicView();
		$this->Request = $req;
		$this->system = new System();
	}
	
	/****** End-User FUNCTIONS ********************************************/

	/****** ADMIN FUNCTIONS ********************************************/
	function admin(){
		$req = $this->Request;
		if($req->getPost("r")=="update"){
			$this->system->open();
			$q = $this->system->update($req->getPost("show_broadcast"),
												$req->getPost("broadcast_message"),
												$req->getPost("maintenance_mode"));
			$this->system->close();
			if($q){
				return $this->View->showMessage("System-wide Setting Saved !","index.php");
			}
		}else if($req->getParam("r")=="queue"){
			return $this->PageQueueCount();
		}else{
			return $this->SettingPage();
		}
	}
	function PageQueue($total_per_page=50){
		$start = $this->Request->getParam("st");
		if($start==NULL){$start=0;}
		$d = $this->Request->getParam("d");
		$this->open(3);
		$sql  ="SELECT datee, webs, websid, jeda,tglisidata,tglindex, bedatime
		        FROM db_publisher.sitti_publisher_leech
				WHERE (datee = '".$d."')
				AND jeda < 13979415
				ORDER BY jeda LIMIT ".$start.",".$total_per_page;
		$sql2  ="SELECT COUNT(*) as total
		        FROM db_publisher.sitti_publisher_leech
				WHERE (datee = '".$d."')
				AND jeda < 13979415
				LIMIT 1";
		$list = $this->fetch($sql,1);
		$cnt = $this->fetch($sql2);
		$this->close();
		$this->View->assign("list",$list);
		$this->View->assign("TotalQueue",$cnt['total']);
		//paging
		$this->Paging = new Paginate();
		//print $this->Inventory->found_rows;
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $cnt['total'], "?s=system&r=queue&d=".$d));
		
		//dropdown tanggal
		$date_list = array();
		$n=0;
		for($i=6;$i>=0;$i--){
			$date_list[$n] = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$i,date("Y")));
			$n++;
		}
		
		$this->View->assign("date_list",$date_list);
		
		return $this->View->toString("SITTIAdmin/system/queue.html");
	}
	function PageQueueCount(){
		$sql = "SELECT COUNT(`websid`) AS `jum_queue`, NOW() AS last_update
				FROM `db_publisher`.`sitti_publisher`
				WHERE websid > ( SELECT `websid`
				FROM `db_publisher`.`sitti_publisher_words`
				ORDER BY `websid` DESC LIMIT 1 )";

		$this->open(3);
		$rs = $this->fetch($sql);
		$this->close();
		$this->View->assign("rs",$rs);
		return $this->View->toString("SITTIAdmin/system/queue_count.html");
		
	}
	function SettingPage(){
		
		$this->View->assign("rs",$this->system->m_options);
		return $this->View->tostring("common/admin/broadcast.html");
	}
	function MaintenanceMode(){
		
	}
}
?>