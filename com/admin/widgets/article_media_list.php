<?php
class article_media_list{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('pages', strip_tags($this->apps->_g('page')));
		$this->apps->assign('user',$this->apps->user);
	}

	function main(){
		$featarticle = $this->apps->contentHelper->getArticleFeatured();
		$this->apps->assign('featarticle',$featarticle[0]);
		$articlelist = $this->apps->contentHelper->getArticleContent();
		$this->apps->assign('start',intval($this->apps->_request('start')));
		$this->apps->assign('total',intval($articlelist['total']));
		$this->apps->assign('article',$articlelist['result']);
		$this->apps->assign('articleinter',$articlelist['articleinter']);
		$this->apps->assign('cid',intval($this->apps->_request('cid')));
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/article-media-list.html");
	}
}
?>