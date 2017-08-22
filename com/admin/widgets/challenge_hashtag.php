<?php
class challenge_hashtag {
	
	function __construct($apps=null){
		$this->apps = $apps;
		global $LOCALE,$CONFIG;
		
		$this->apps->contentHelper = $this->apps->useHelper('contentHelper');
		$this->apps->searchHelper = $this->apps->useHelper('searchHelper');
		$this->apps->messageHelper  = $this->apps->useHelper('messageHelper');
		$this->apps->userHelper = $this->apps->useHelper('userHelper');
		
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale', $LOCALE[1]);		
		$this->apps->assign('pages', strip_tags($this->apps->_g('page')));
		$this->apps->assign('user',$this->apps->user);
	}

	function main(){
		$cidStr = intval($this->apps->_request('id'));
		
		$article = $this->apps->contentHelper->getDetailArticle();
		$tags = $article['result'][0]['un_tags'];
		$challengeHashtag = $this->apps->contentHelper->getChallengeHashtag(0,3,$tags,3);
		
		$this->apps->assign('detail',$article['result']);
		$this->apps->assign('tags',$tags);
		$this->apps->assign('challengeHashtag',$challengeHashtag['result']);
		$this->apps->assign('start',intval($this->apps->_request('start')));
		$this->apps->assign('total_hashtag',$challengeHashtag['total']);
		$this->apps->assign('cidStr',$cidStr);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/challenge-hashtag-list.html");
	}
}
?>