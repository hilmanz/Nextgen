<?php
class home extends App{
	
	function beforeFilter(){
		global $LOCALE,$CONFIG;
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->passwordHelper  = $this->useHelper('passwordHelper');
		
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->searchHelper  = $this->useHelper('searchHelper');
		$this->messageHelper = $this->useHelper('messageHelper');
		$this->userHelper = $this->useHelper('userHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		$this->loginHelper = $this->useHelper('loginHelper');
		$this->registerHelper = $this->useHelper('registerHelper');
		$this->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);		
		$this->assign('locale', $LOCALE[1]);
		$this->assign('user', $this->user);
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));
		$data = $this->userHelper->getUserProfile(); 	
		$this->View->assign('userprofile',$data);
		
		 
	}
	
	function main(){
		$this->assign('startdate',$this->_p('startdate'));
		$this->assign('enddate',$this->_p('enddate'));
		$this->assign('search',$this->_p('search'));
				
		$this->View->assign('my_profile_box',$this->setWidgets('my_profile_box'));
		$this->View->assign('lates_engagement_box',$this->setWidgets('lates_engagement_box'));
		$this->View->assign('inbox_box',$this->setWidgets('inbox_box'));
		$this->View->assign('line_chart',$this->setWidgets('line_chart'));
		$this->View->assign('medium_banner',$this->setWidgets('medium_banner'));		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'/home.html');		
	}
	
	
 
	
	function changeit() {
	global $CONFIG;
		$data = $this->passwordHelper->changepassword();
		if($data){
				sendRedirect("{$CONFIG['ADMIN_DOMAIN']}home/profileEdit");
				return $this->out(TEMPLATE_DOMAIN_ADMIN . 'widgets/change-password.html');
				exit;
		}else{
		("{$CONFIG['ADMIN_DOMAIN']}home/profileDetail");
		return $this->out(TEMPLATE_DOMAIN_ADMIN .'widgets/profile-detail.html');
		}
	}
	
	function saveUser() {
		global $CONFIG;
		 
	 
		/*$name = strip_tags($this->_p('name'));
		$last_name = strip_tags($this->_p('last_name'));
		$nickname = strip_tags($this->_p('nickname'));
		$sex = strip_tags($this->_p('sex'));
		$phone_number = strip_tags($this->_p('phone_number'));
				
		$sql = "
				UPDATE social_member SET name='{$name}', last_name='{$last_name}', nickname='{$nickname}',sex='{$sex}' ,phone_number='{$phone_number}' WHERE id = '{$this->user->id}' LIMIT 1				
			";
			// pr($sql);
		$qData = $this->query($sql);*/
		$data = $this->userHelper->updateUserProfile();
		if(!$data) return false;
		sendRedirect ("{$CONFIG['ADMIN_DOMAIN']}home/profileDetail");
		
	}
	
	function profileDetail(){
	$this->View->assign('profile_detail',$this->setWidgets('profile_detail'));
	return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/profile-detail.html');
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
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/profile-edit.html");
	}

	function changePassword(){
	$this->View->assign('change_password',$this->setWidgets('change_password'));
	return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/change-password.html');
	}

	function editProfile(){
	$this->View->assign('edit_profile',$this->setWidgets('edit_profile'));
	return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/edit-profile.html');
	}
	
	function entourageList(){
		$this->View->assign('entourage_list',$this->setWidgets('entourage_list'));
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/entourage-list.html');
	}
	
	function entourageDetail(){
		$this->View->assign('entourage_detail',$this->setWidgets('entourage_detail'));
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/entourage-detail.html');
	}
	
	function ajax(){
		if($this->_p('action')=="a360activity") {
			$maxrecord = 2;
			$start = intval($this->_p('start'));
			$data = $this->activityHelper->getA360activity($start,$maxrecord);
			print json_encode($data['content']); exit;
		}
	}	
}
?>