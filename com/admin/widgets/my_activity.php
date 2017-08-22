<?php
class my_activity{
	
	function __construct($apps=null){		
			$this->apps = $apps;
			global $LOCALE,$CONFIG;
			$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
			$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
			$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
			$this->apps->assign('user',$this->apps->user);
	}

	function main(){
		$pages = strip_tags($this->apps->_request('page'));
		$start = intval($this->apps->_request('start'));
		$maxrecord = 8;
		
		$uid = intval($this->apps->_g('uid'));
	
		
		// pr($isuser);
		$data = $this->apps->activityHelper->getA360activity($start,$maxrecord,true);
	
		$this->apps->assign('activity',$data['content']);
		$this->apps->assign('totalactivity',$data['total']);
		
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/my-activity.html");
	
	}


}


?>