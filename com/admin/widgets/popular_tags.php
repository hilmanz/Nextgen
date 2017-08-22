<?php
class popular_tags{
	
	function __construct($apps=null){		
			$this->apps = $apps;	
			global $LOCALE,$CONFIG;
			$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
			$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
			$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		
		$data = $this->apps->contentHelper->populartags();
		$this->apps->assign('tags',$data);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/popular-tags.html");
	
	}
		


}


?>