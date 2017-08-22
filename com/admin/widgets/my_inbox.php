<?php
class my_inbox {
	
	function __construct($apps=null){		
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
		$this->apps->assign('user',$this->apps->user);
	}

	function main(){	
		$data = $this->apps->newsHelper->getInboxUser();
		// pr($data);
		if($data){
			$this->apps->assign('totalinboxdata',$data['total']);
			$this->apps->assign('activity',$data['result']);
		}
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/my-inbox.html");	
	}
	
	
}
?>