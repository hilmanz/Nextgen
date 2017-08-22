<?php
class track extends ServiceAPI{

	
	function beforeFilter(){
	
	 		$this->gamesHelper = $this->useHelper('gamesHelper');
	 		$this->userHelper = $this->useHelper('userHelper');
	 		$this->uploadHelper = $this->useHelper('uploadHelper');
	 		$this->BEATServHelper = $this->useHelper('BEATServHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));		
	}
	
	function activity(){
		$activity = $this->_p('activity');
		$this->log('surf',$activity);		 
		$data['status'] = true;
		$data['message'] = "success save activity";
		return $data;
	}
	function getUser(){
		 
		$user = $this->userHelper->getUserProfile(); 
		if($user){
			$profile['id'] = $user['id'];
			$profile['name'] = $user['name'];
			$profile['last_name'] = $user['last_name'];
		}else return false;
		$lastoken = $this->gamesHelper->getLastOldToken(); 
		$data['profile'] = $profile;	
		$data['playingat'] = date("YmdHi").$lastoken;	
		return $data;
	}
	
	function games(){
			global $CONFIG;
			
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."games/";
			$imagesfiles = false;
			if (isset($_FILES['images'])&&$_FILES['images']['name']!=NULL) {
				if (isset($_FILES['images'])&&$_FILES['images']['size'] <= 20000000) {
					$images = $this->uploadHelper->uploadThisImage($_FILES['images'],$path);						
				} else {
					$success = false;
				}
			} else {
				$success = false;
			}
		 
			if($images)$imagesfiles = $images['arrImage']['filename'];
			else $imagesfiles = false;
			
			$gamestrack =  $this->gamesHelper->playinggames(false,$imagesfiles);
			$offilenbadges =  $this->BEATServHelper->offlinebadges();
			if(!$offilenbadges) $offilenbadges="0";
			return array('gamesstatus'=>$gamestrack,'offlinestatus'=>$offilenbadges);
	}
	
}
?>
