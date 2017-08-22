<?php 

class userHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) {
				$uid = intval($this->apps->_request('uid'));
				if($uid==0) $this->uid = intval($this->apps->user->id);
				else $this->uid = $uid;
		}

		$this->dbshema = "admin";	
	 
	}
  
	
	function getUserProfile(){
		global $CONFIG;
	
		$uid = intval($this->apps->_request('uid'));
		if(!$uid) $uid = intval($this->uid);
		if($uid!=0 || $uid!=null) {
			$sql = "
			SELECT sm.id,sm.name,sm.last_name,sm.img,sm.sex,sm.username,sm.nickname,sm.register_date,sm.StreetName,sm.phone_number,sm.email,sm.last_login,sm.n_status,sm.sex,sm.birthday,cityref.city as cityname,sm.small_img ,sm.description , pagestype.name role, pages.type roletype
			FROM admin_users sm
			LEFT JOIN {$this->dbshema}_city_reference cityref ON sm.city = cityref.id
			LEFT JOIN my_pages pages ON sm.id = pages.ownerid
			LEFT JOIN my_pages_type pagestype ON pages.type = pagestype.id
			WHERE sm.id = {$uid} LIMIT 1";
			// pr($sql);
			$this->logger->log($sql);
			$qData = $this->apps->fetch($sql);
			if(!$qData)return false;
			 
		  
			if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/tiny_{$qData['img']}")) $qData['img'] = false;
			if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/cover/small_{$qData['small_img']}")) $qData['small_img'] = false;
			// if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/crop{$qData['img']}")) $qData['img'] = "crop{$qData['img']}";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/original_{$qData['img']}")) $qData['imgoriginal']= "original_{$qData['img']}";
			else $qData['imgoriginal'] = false;
			
			// $qData['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/";
			
			if($qData['img']) $qData['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/tiny_".$qData['img'];
			else $qData['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/default.jpg";
			
			if($qData['small_img']) $qData['cover_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/cover/small_".$qData['small_img'];
			else $qData['cover_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/cover/default.jpg";
			
		  
			return $qData;
		}
		return false;
	}
	 
	function changepassword(){
		global  $CONFIG;
		$oldpass = strip_tags($this->apps->_p('oldpass'));
		$newpass = strip_tags($this->apps->_p('newpass'));
		$confirmnewpass = strip_tags($this->apps->_p('confirmnewpass'));
		$this->logger->log($oldpass.'-'.$newpass.'-'.$confirmnewpass);

		if($newpass!=$confirmnewpass) return false;
			$this->logger->log($oldpass.'-'.$newpass.'-'.$confirmnewpass);
		if(preg_match("/^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/",$newpass)) {	
			$sql = "SELECT * FROM admin_users WHERE id={$this->uid} LIMIT 1";
			$this->logger->log($oldpass.'-'.$newpass.'-'.$confirmnewpass);
			$this->logger->log($sql);
			
			
			$rs = $this->apps->fetch($sql);
			$this->logger->log(json_encode($rs));
			if(!$rs) return false;
			
			$oldhashpass = sha1($oldpass.$rs['salt']);
			
			if($oldhashpass!=$rs['password']) return false;
				
			$hashpass = sha1($newpass.$rs['salt']);
					
			$sql ="UPDATE admin_users SET password='{$hashpass}' WHERE id={$this->uid} LIMIT 1";
			$rs = $this->apps->query($sql);
			// pr($sql);exit;
			if($rs){
				$sql ="UPDATE admin_users SET last_login=now(),login_count=login_count+1 WHERE id={$this->uid} LIMIT 1";
				$rs = $this->apps->query($sql);
				// pr($sql);exit;
				return true;
			}
		} 
		$this->logger->log($oldpass.'-'.$newpass.'-'.$confirmnewpass.'-'.'not have secury password');
		return false;
		
	}
	 
}

?>

