<?php 

class leaderboardHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
			
			
		}

		$this->dbshema = "beat";	
	}

	
	
	function getEntourageList(){
		global $CONFIG;
			
		$sql = "SELECT count(*) totalentourage, sm.id,sm.name, sm.last_name, sm.img
				FROM `my_entourage` entourage
				LEFT JOIN social_member sm ON sm.id=entourage.referrerbybrand 
				LEFT JOIN my_pages pages ON sm.id=pages.ownerid
				WHERE pages.type = 1  AND entourage.n_status = 1
				GROUP BY `referrerbybrand` ORDER BY totalentourage DESC,sm.id DESC LIMIT 10";	
		$qData = $this->apps->fetch($sql,1);		
		if(!$qData)return false;
		foreach($qData as $key => $val ){
			if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$qData[$key]['img']}")) $qData[$key]['img'] = false;
			if($qData[$key]['img']) $qData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/".$qData[$key]['img'];
			else $qData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/default.jpg";
			
			$qData[$key]['name'] =  ucwords(strtolower($qData[$key]['name']." ".$qData[$key]['last_name']));
			$qData[$key]['last_name'] =  ucwords($qData[$key]['last_name']);
		}
		// pr($qData);exit;
		return $qData;
		
		
	}
	
	
	function topplacelist(){
		global $CONFIG;
			$limit = 10;
		$sql = "SELECT count(*) total, checkin.venueid, master.venuename, master.city,master.provinceName, master.id,master.venuecategory
				FROM my_checkin checkin
				LEFT JOIN beat_venue_master master ON checkin.venueid=master.id
				WHERE checkin.venueid<>0 AND master.id IS NOT NULL
				GROUP BY checkin.venueid
				ORDER BY total DESC LIMIT {$limit} ";	
		$qData = $this->apps->fetch($sql,1);		
		foreach( $qData as $key => $val ){
			
		 
			$qData[$key]['venuename'] = ucwords(strtolower($val['venuename'].", ".$val['city'].", ".$val['provinceName']));
			$qData[$key]['city'] = ucwords(strtolower($val['city']));
			$qData[$key]['provinceName'] = ucwords(strtolower($val['provinceName']));
		 
			
		 
		}
		return $qData;
		
		
	}
	
	
	function usercheckinlists(){
		global $CONFIG;
		$limit = 50;
		$venueid = intval($this->apps->_p('venueid'));
		if($venueid==0) return false;
		
		$sql = "SELECT COUNT(*) total, checkin.userid 
				FROM my_checkin checkin
				LEFT JOIN beat_venue_master master ON checkin.venueid=master.id				 
				WHERE checkin.venueid ={$venueid}
				GROUP BY checkin.venueid,checkin.userid
				ORDER BY total DESC LIMIT {$limit} ";	
		$qData = $this->apps->fetch($sql,1);		
		if($qData){
			$sdata = false;
			$socialData = false;
			foreach($qData as $key => $val){
				$qData[$key]['userdetail'] = false;
				$sdata[$val['userid']] = $val['userid'];
			}
			
			if($sdata){
				$strsdata = implode(',',$sdata);
				$socialData= $this->getSocialData($strsdata);				
			}
			
			if($socialData){
				foreach($qData as $key => $val){
					if(array_key_exists($val['userid'],$socialData)) $qData[$key]['userdetail'] = $socialData[$val['userid']];
				}			
			}
		}
		return $qData;		
	}
	
	function getSocialData($strsdata=false){
		global $CONFIG;
		if($strsdata==false) return false;
		$data =false;
	
		
		
		$sql ="
		SELECT sm.id,sm.name,sm.last_name,sm.img, ptype.id pagetypeid
		FROM social_member sm
		LEFT JOIN my_pages pages ON pages.ownerid = sm.id
		LEFT JOIN my_pages_type ptype ON ptype.id = pages.type		
		WHERE sm.id IN ({$strsdata}) ";		
		// pr($sql);
			$sQdata = $this->apps->fetch($sql,1);	
			// pr($sQdata);	
			if($sQdata){
				$arrmessagetype[1] = "direct";
				$arrmessagetype[2] = "direct";
				$arrmessagetype[3] = "brand";
				$arrmessagetype[4] = "brand";
				$arrmessagetype[5] = "brand";
				$arrmessagetype[100] = "challenge";
				foreach($sQdata as $key => $val){
				
							$sQdata[$key]['messagetype'] = @$arrmessagetype[$val['pagetypeid']];
							
							if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$val['img']}")) $val['img'] = false;
							if($val['img']) $sQdata[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/".$val['img'];
							else $sQdata[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/default.jpg";
							$data[$val['id']]=$sQdata[$key];			
				}
			}
			
		return $data;
	
	}	
	
	
}

?>

