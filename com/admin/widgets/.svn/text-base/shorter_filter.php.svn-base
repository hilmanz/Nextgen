<?php
class shorter_filter{
	
	function __construct($apps=null){		
			$this->apps = $apps;	
			global $LOCALE,$CONFIG;
			$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
			$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
			$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
			
	}

	function main(){
		$pages = strip_tags($this->apps->_request('page'));
		$act = strip_tags($this->apps->_request('act'));
	
		$this->apps->assign('categorytypevalue',intval($this->apps->_p('categorytype')));
		$this->apps->assign('bandcategoryvalue',intval($this->apps->_p('bandcategory')));
		$this->apps->assign('filtertypevalue',strip_tags($this->apps->_p('filtertype')));
		$this->apps->assign('q',strip_tags($this->apps->_p('q')));
		$this->apps->assign('act',$act);
		
		$category = $this->apps->contentHelper->getPagesCategory($pages);
		$this->apps->assign('category',$category);
		if($pages=='music' || $pages=='dj') {
			$band = $this->apps->contentHelper->getPagesCategory(21,false);
			$this->apps->assign('bandcategory',$band);
		}
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/shorter-filter.html");
	
	}
	


}


?>