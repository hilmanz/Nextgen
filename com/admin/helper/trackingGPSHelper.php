<?php
class trackingGPSHelper{
	
	function __construct($apps){
		$this->apps = $apps;
		if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
		
		}		
	}
	
	function senddata(){
			$coor= strip_tags($this->apps->_request('coor'));
			$type= intval($this->apps->_request('type'));
			// $coor = "-6.273954,106.820999";
		
			if($coor=='') return false;
			
				$arrcoor = explode(',',$coor);
				if(is_array($arrcoor)){
					$lat = $arrcoor[0];				
					$lon = $arrcoor[1];		
				
				$datetime = date("Y-m-d H:i:s");
				$datetime_ts = time();
				$sql = "INSERT IGNORE INTO tbl_activity_gps 
						(userid,latitude,longitude,datetime,datetime_ts,n_status) 
						VALUES 
						('{$this->uid}',{$lat},{$lon},'{$datetime}','{$datetime_ts}','{$type}')
						";
				// pr($sql);
				$this->apps->query($sql);
				
				if($this->apps->getLastInsertId()) return true;
				else return false;
			}
		
	}
	
	
}
?>
