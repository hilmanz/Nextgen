<?php
class popup_article_detail{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[1]);
		$this->contentHelper = $this->apps->useHelper('contentHelper');
	}

	function main(){
		$article = $this->contentHelper->getDetailArticle();		
		
		// pr($article);exit;	
		$this->apps->log('read article',intval($this->apps->_request('id')));
	
		if($article) 
		{
			$comment  = $this->contentHelper->getComment();
			$data['result'] =  true;
			$data['article'] = $article['result'][0];
			$data['comment'] = $comment;
		
		}else {
			$data['result'] = false;
		}		
		print json_encode($data);
		exit;
	}
	
}
?>