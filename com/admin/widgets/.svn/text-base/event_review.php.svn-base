<?php
class event_review{
	
	function __construct($apps=null){		
			$this->apps = $apps;	
			global $LOCALE,$CONFIG;
			$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
			$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
			$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
	
		$featarticle = $this->apps->contentHelper->getArticleFeatured();
		$this->apps->assign('featarticle',$featarticle[0]);
		// $articlelist = $this->apps->contentHelper->getArticleContent();
		$this->apps->assign('start',intval($this->apps->_request('start')));
		// $this->apps->assign('total',intval($articlelist['total']));
		// $this->apps->assign('article',$articlelist['result']);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/event-review.html");
	
	}
	

}


?>