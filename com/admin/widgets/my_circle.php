<?php
class my_circle {
	
	function __construct($apps=null){		
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){	
		$data = $this->apps->userHelper->getCircleUser();
		$groupdata = $this->apps->userHelper->getGroupUser();
		// pr($data['total']);
		$this->apps->View->assign('group',$groupdata);
		if($data){
			$this->apps->View->assign('usercircle',$data['result']);
			$this->apps->View->assign('total',$data['total']);
			
		}else  $this->apps->View->assign('total',0);
		
		$friends = $this->apps->userHelper->getFriends(true,5);
		// pr($friends);
		if($friends){
			$this->apps->View->assign('newfriends',$friends['result']);
			$this->apps->View->assign('totalnewfriends',$friends['total']);
			
		}else  $this->apps->View->assign('totalnewfriends',0);
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/my-circle.html");	
	}
	
	
}
?>