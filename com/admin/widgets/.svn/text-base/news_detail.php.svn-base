<?php
class news_detail{
	
	function __construct($apps=null){		
			$this->apps = $apps;	
			global $LOCALE,$CONFIG;
			$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
			$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
			$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
			
			$this->apps->assign('user',$this->apps->user);
			
	}

	function main(){

		$data = $this->apps->contentHelper->getDetailArticle(null,1,1);		
		// pr($data);
		if($data){
			if($data['result'])	$this->apps->assign('article',$data['result'][0]);
			if($data['total'])	$this->apps->assign('totalnews',$data['total']);
		}
	
		$this->apps->assign('start',intval($this->apps->_request('start')));
		if($data['total']) $this->apps->log('read article',intval($data['result'][0]['id']));
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/news-detail.html");
	
	}


}


?>