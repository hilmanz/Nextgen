<?php
class inbox_box {
	
	function __construct($apps=null){		
			$this->apps = $apps;	
			global $LOCALE,$CONFIG;
			$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
			$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
			$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		// $data = $this->apps->userHelper->getUserProfile(); 
		
		// $this->apps->View->assign('userprofile',$data);
		// $this->apps->View->assign('user',$this->apps->user);
		// echo'masuk';
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/inbox-box.html");	
	}
}
?>