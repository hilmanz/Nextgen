<?php
class edit_profile{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
		$this->apps->assign('user',$this->apps->user);
	}

	function main(){
		global $CONFIG;
		$id = $this->apps->_g('id');
		$authorid = intval($this->apps->_p("authorid"));
		$sql = "SELECT * FROM social_member WHERE id={$id} LIMIT 1";
		$qData = $this->apps->fetch($sql);
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/edit-profile.html");
	}
	
}
?>