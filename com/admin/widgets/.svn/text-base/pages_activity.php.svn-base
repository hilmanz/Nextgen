<?php
class pages_activity{
	
	function __construct($apps=null){
		$this->apps = $apps;
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		$pages = strip_tags($this->apps->_request('page'));
		$start = intval($this->apps->_request('start'));
		$limit = 8;
		$data = $this->apps->activityHelper->getA360Pagesactivity($start,$limit,true);
// pr($data);		
		$this->apps->assign('activity',$data['content']);
		$this->apps->assign('totalbandactivity',$data['total']);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/pages-activity.html");	
	}
}
?>