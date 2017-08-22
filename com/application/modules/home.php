<?php
class home extends App{
	
	function beforeFilter(){
		global $LOCALE,$CONFIG;
			
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->passwordHelper  = $this->useHelper('passwordHelper');
  
		$this->searchHelper  = $this->useHelper('searchHelper'); 
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);		
		$this->assign('locale', $LOCALE[1]);
		$this->assign('user', $this->user);
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));
		$data = $this->userHelper->getUserProfile(); 	
		$this->View->assign('userprofile',$data);
		
		$this->assign('badetail',$this->userHelper->getsba());
		$loadCity = $this->contentHelper->loadCity();
		
		$this->assign('getCity',$loadCity);
		 
	}
	
	function main(){
			$this->assign('uid',strip_tags($this->_g('uid')));
			$this->assign('areaid',strip_tags($this->_g('areaid')));
			$this->assign('startdate',strip_tags($this->_g('startdate')));
			$this->assign('enddate',strip_tags($this->_g('enddate')));
			
			$totalquery =  $this->userHelper->getgamestrackall();
			// pr($totalquery);
			$this->assign('totalentourage',intval($totalquery['totalunique']));
			$this->assign('totalplay',intval($totalquery['totalplay'])); 
			$this->assign('totalwin',intval($totalquery['totalwin']));
			$this->assign('totallose',intval($totalquery['totallose']));
			
			$games = $this->userHelper->getgamestrack();
			$this->assign('games',$games);
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/home.html');		
	}
	 
	function changeit() {
	global $CONFIG;
		$data = $this->passwordHelper->changepassword();
		if($data){
				sendRedirect("{$CONFIG['BASE_DOMAIN']}home/profileEdit");
				return $this->out(TEMPLATE_DOMAIN_WEB . 'widgets/change-password.html');
				exit;
		}else{
		("{$CONFIG['BASE_DOMAIN']}home/profileDetail");
		return $this->out(TEMPLATE_DOMAIN_WEB .'widgets/profile-detail.html');
		}
	}
	
	function saveUser() {
		global $CONFIG;
		  
		$data = $this->userHelper->updateUserProfile();
		if(!$data) return false;
		sendRedirect ("{$CONFIG['BASE_DOMAIN']}home/profileDetail");
		
	}
	
	function profileDetail(){
	$this->View->assign('profile_detail',$this->setWidgets('profile_detail'));
	return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/profile-detail.html');
	}
	
	function profileDetailEdit(){
		global $CONFIG;
		if($this->_p('token')){
			$data = $this->uploadHelper->uploadThisImage($files=$_FILES['img'],$path=$CONFIG['LOCAL_PUBLIC_ASSET'].'user/photo/');
			if ($data){
				$saved = $this->userHelper->updateUserImageProfile($data['arrImage']['filename']);
				if ($saved)$this->View->assign('status', 1);
			}
			
			// return false;
		}
		
		
		$this->View->assign('profile_edit',$this->setWidgets('profile_edit'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/profile-edit.html");
	}

	function changePassword(){
	$this->View->assign('change_password',$this->setWidgets('change_password'));
	return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/change-password.html');
	}

	function editProfile(){
	$this->View->assign('edit_profile',$this->setWidgets('edit_profile'));
	return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/edit-profile.html');
	}
	
	function entourageList(){
		$this->View->assign('entourage_list',$this->setWidgets('entourage_list'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/entourage-list.html');
	}
	
	function entourageDetail(){
		$this->View->assign('entourage_detail',$this->setWidgets('entourage_detail'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/entourage-detail.html');
	}
	
	 
}
?>