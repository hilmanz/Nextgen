<?php 

class passwordHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) {
				$uid = intval($this->apps->_request('uid'));
				if($uid==0) $this->uid = intval($this->apps->user->id);
				else $this->uid = $uid;
		}

		$this->dbshema = "beat";	
		$this->topclass = array(100,4,6);
	}

	function changepassword(){
		
		$oldpass = strip_tags($this->apps->_p('oldpass'));
		$newpass = strip_tags($this->apps->_p('newpass'));
		$confirmnewpass = strip_tags($this->apps->_p('confirmnewpass'));
		$this->logger->log($oldpass.'-'.$newpass.'-'.$confirmnewpass);
		if($newpass!=$confirmnewpass) return false;
			$this->logger->log($oldpass.'-'.$newpass.'-'.$confirmnewpass);
		if(preg_match("/^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/",$newpass)) {	
			$sql = "SELECT * FROM social_member WHERE id={$this->uid} LIMIT 1";
			$this->logger->log($oldpass.'-'.$newpass.'-'.$confirmnewpass);
			// pr($sql);exit;
			$rs = $this->apps->fetch($sql);
			if(!$rs) return false;
			
			$oldhashpass = sha1($oldpass.$rs['salt']);
			
			if($oldhashpass!=$rs['password']) return false;
				
			$hashpass = sha1($newpass.$rs['salt']);
					
			$sql ="UPDATE social_member SET password='{$hashpass}' WHERE id={$this->uid} LIMIT 1";
			$rs = $this->apps->query($sql);
			// pr($sql);exit;

		}
		$this->logger->log($oldpass.'-'.$newpass.'-'.$confirmnewpass.'-'.'not have secury password');
		return false;
		
	}
	
}

?>

