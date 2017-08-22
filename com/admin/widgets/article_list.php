<?php
class article_list{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		
		$this->apps->assign('start',intval($this->apps->_request('start')));
		$this->apps->assign('cid',intval($this->apps->_request('cid')));
		$data_article = $this->apps->contentHelper->getArticleContent(null,10,0,array(0,3));
		$this->apps->assign('article_list',$data_article['result']);
		$this->apps->assign('total',$data_article['total']);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/article-list.html");
	}
}
?>