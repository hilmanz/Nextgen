<?php
class search_event{
	
	function __construct($apps=null){		
			$this->apps = $apps;	
			global $LOCALE,$CONFIG;
			$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
			$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
			$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		$city = $this->apps->contentHelper->getCity();
		$cityid = intval($this->apps->_p('cityid'));
		$dateposted = intval($this->apps->_p('dateposted'));
		// pr($city);
		$this->apps->View->assign('city',$city);
		$this->apps->View->assign('cityid',$cityid);
		$this->apps->View->assign('dateposted',$dateposted);
	
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/search-event.html");
	
	}
	



}


?>