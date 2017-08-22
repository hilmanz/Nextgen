<?php
class my_profile_box {
	
	function __construct($apps=null){		
			$this->apps = $apps;	
			global $LOCALE,$CONFIG;
			$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
			$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
			$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){

		$this->apps->View->assign('user',$this->apps->user);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/my-profie-box.html");	
	}
}
?>