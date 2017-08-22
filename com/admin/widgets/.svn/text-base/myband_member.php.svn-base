<?php
class myband_member {
	
	function __construct($apps=null){		
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);		
		$this->apps->assign('user',$this->apps->user);
	}

	function main(){
		$data = $this->apps->bandHelper->getMember();
		// pr($data);
		if($data){
			$this->apps->View->assign('bandmember',$data['result']);
			$this->apps->View->assign('total',$data['total']);
		}else  $this->apps->View->assign('total',0);
		$this->apps->View->assign('pages',$this->apps->_g('page'));
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/myband-member.html");	
	}
}
?>